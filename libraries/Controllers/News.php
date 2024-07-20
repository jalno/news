<?php

namespace packages\news\Controllers;

use packages\base;
use packages\base\Http;
use packages\base\InputValidation;
use packages\base\NotFound;
use packages\base\NoViewException;
use packages\base\Response;
use packages\base\Views\FormError;
use packages\news\Comment;
use packages\news\Controller;
use packages\news\Events;
use packages\news\NewPost;
use packages\news\View;
use packages\news\Views;
use packages\userpanel;
use packages\userpanel\Date;

class News extends Controller
{
    public function index(): Response
    {
        $view = View::byName(Views\News\Index::class);
        if ($view) {
            $this->response->setView($view);
            $new = new NewPost();
            $new->orderBy('date', 'DESC');
            $new->where('status', NewPost::published);
            $new->pageLimit = $this->items_per_page;
            $news = $new->paginate($this->page);
            $view->setDataList($news);
            $view->setPaginate($this->page, base\DB::totalCount(), $this->items_per_page);
            $view->setNews($news);
            $this->response->setStatus(true);
        }

        return $this->response;
    }

    public function view($data)
    {
        if (!$new = NewPost::byId($data['id'])) {
            throw new NotFound();
        }
        try {
            $view = View::byName(Views\News\View::class);
        } catch (NoViewException $error) {
            $this->response->Go(userpanel\url('news/view/'.$new->id));
        }
        ++$new->view;
        $new->save();
        $view->setNew($new);
        $comment = new Comment();
        $comment->where('post', $new->id);
        $comment->where('status', Comment::accepted);
        $view->setData($comment->get(), 'comments');
        if (HTTP::is_post()) {
            $this->response->setStatus(false);
            $inputsRules = [
                'reply' => [
                    'type' => 'number',
                    'optional' => true,
                    'empty' => true,
                ],
                'name' => [
                    'type' => 'string',
                ],
                'email' => [
                    'type' => 'email',
                ],
                'text' => [
                    'type' => 'string',
                ],
            ];
            try {
                $inputs = $this->checkinputs($inputsRules);
                if (isset($inputs['reply'])) {
                    if ($inputs['reply']) {
                        if (!$inputs['reply'] = Comment::byId($inputs['reply'])) {
                            throw new InputValidation('reply');
                        }
                    } else {
                        unset($inputs['reply']);
                    }
                }
                $comment = new Comment();
                $comment->post = $new->id;
                foreach (['email', 'name', 'text'] as $item) {
                    $comment->$item = $inputs[$item];
                }
                if (isset($inputs['reply'])) {
                    $comment->reply = $inputs['reply']->id;
                }
                $comment->save();
                $event = new Events\Comments\Add($comment);
                $event->trigger();
                $this->response->setStatus(true);
            } catch (InputValidation $error) {
                $view->setFormError(FormError::fromException($error));
            }
            $view->setDataForm($this->inputsvalue($inputsRules));
        } else {
            $this->response->setStatus(true);
        }
        $this->response->setView($view);

        return $this->response;
    }

    public function archive($data)
    {
        $first = Date::mktime(0, 0, 0, $data['month'], 1, $data['year']);
        $month = $data['month'];
        $year = $data['year'];
        if (12 == $month) {
            $month = 1;
            ++$year;
        } else {
            ++$month;
        }
        $last = Date::mktime(0, 0, 0, $month, 1, $year);
        $new = new NewPost();
        $new->orderBy('date', 'DESC');
        $new->where('date', $first, '>=');
        $new->where('date', $last, '<');
        $new->where('status', NewPost::published);
        $new->pageLimit = $this->items_per_page;
        if (!$news = $new->paginate($this->page)) {
            throw new NotFound();
        }
        $view = View::byName(Views\News\Archive::class);
        $view->setNews($news);
        $view->setPaginate($this->page, base\DB::totalCount(), $this->items_per_page);
        $this->response->setStatus(true);
        $this->response->setView($view);

        return $this->response;
    }

    public function author($data)
    {
        $new = new NewPost();
        $new->where('author', $data['id']);
        $new->orderBy('date', 'DESC');
        $new->where('status', NewPost::published);
        $new->pageLimit = $this->items_per_page;
        if (!$news = $new->paginate($this->page)) {
            throw new NotFound();
        }
        $view = View::byName(Views\News\Author::class);
        $view->setNews($news);
        $view->setPaginate($this->page, base\DB::totalCount(), $this->items_per_page);
        $this->response->setStatus(true);
        $this->response->setView($view);

        return $this->response;
    }
}
