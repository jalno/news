<?php

namespace packages\news\Controllers\Panel;

use packages\base\Http;
use packages\base\InputValidation;
use packages\base\Views\FormError;
use packages\news\Authorization;
use packages\news\Comment;
use packages\news\Views\Panel;
use packages\userpanel;
use packages\userpanel\Controller;
use packages\userpanel\Date;
use packages\userpanel\View;
use themes\clipone\Views\News\Panel as Views;

class Comments extends Controller
{
    protected $authentication = true;

    public function index($data)
    {
        Authorization::haveOrFail('comments_list');
        $view = View::byName(Views\Comment::class);
        $comment = new Comment();
        if ($data['id']) {
            $comment->where('post', $data['id']);
        }
        $comment->orderBy('date', 'DESC');
        $view->setComments($comment->get());
        $this->response->setView($view);

        return $this->response;
    }

    public function delete($data)
    {
        Authorization::haveOrFail('comments_delete');
        $view = View::byName(Views\CommentDelete::class);

        $comment = Comment::byId($data['id']);
        $view->setComment($comment);
        $this->response->setStatus(false);
        if (HTTP::is_post()) {
            try {
                $comment->delete();
                $this->response->setStatus(true);
                $this->response->Go(userpanel\url('news/comments'));
            } catch (InputValidation $error) {
                $view->setFormError(FormError::fromException($error));
            }
        } else {
            $this->response->setStatus(true);
        }
        $this->response->setView($view);

        return $this->response;
    }

    public function edit($data)
    {
        Authorization::haveOrFail('comments_edit');
        $view = View::byName(Views\CommentEdit::class);

        $comment = Comment::byId($data['id']);
        $view->setComment($comment);
        $inputsRules = [
            'name' => [
                'type' => 'string',
                'optional' => true,
            ],
            'email' => [
                'type' => 'email',
                'optional' => true,
            ],
            'text' => [
                'optional' => true,
            ],
            'date' => [
                'type' => 'date',
                'optional' => true,
            ],
            'status' => [
                'type' => 'number',
                'optional' => true,
                'values' => [Comment::accepted, Comment::pending, Comment::unverified],
            ],
        ];
        $this->response->setStatus(false);
        if (HTTP::is_post()) {
            try {
                $inputs = $this->checkinputs($inputsRules);
                $inputs['date'] = Date::strtotime($inputs['date']);
                if ($inputs['date'] <= 0) {
                    throw new InputValidation('date');
                }
                if (isset($inputs['name'])) {
                    $comment->name = $inputs['name'];
                }
                if (isset($inputs['email'])) {
                    $comment->email = $inputs['email'];
                }
                if (isset($inputs['text'])) {
                    $comment->text = $inputs['text'];
                }
                if (isset($inputs['date'])) {
                    $comment->date = $inputs['date'];
                }
                if (isset($inputs['status'])) {
                    $comment->status = $inputs['status'];
                }
                $comment->save();
                $this->response->setStatus(true);
                $this->response->Go(userViews\url('news/comment/edit/'.$comment->id));
            } catch (InputValidation $error) {
                $view->setFormError(FormError::fromException($error));
            }
        } else {
            $this->response->setStatus(true);
        }
        $this->response->setView($view);

        return $this->response;
    }
}
