<?php

namespace themes\clipone\Views\News\Panel;

use packages\base\Translator;
use packages\base\View\Error;
use packages\news\Views\Panel\Index as NewsIndex;
use packages\userpanel;
use themes\clipone\Navigation;
use themes\clipone\Navigation\MenuItem;
use themes\clipone\Views\FormTrait;
use themes\clipone\Views\ListTrait;
use themes\clipone\ViewTrait;

class Index extends NewsIndex
{
    use ViewTrait;
    use ListTrait;
    use FormTrait;

    public function __beforeLoad()
    {
        $this->setTitle([
            t('list'),
            t('news'),
        ]);
        Navigation::active('news/index');
        $this->setButtons();
        if (empty($this->getNews())) {
            $this->addNotFoundError();
        }
    }

    private function addNotFoundError()
    {
        $error = new Error();
        $error->setType(Error::NOTICE);
        $error->setCode('news.post.notfound');
        if ($this->canAdd) {
            $error->setData([
                [
                    'type' => 'btn-success',
                    'txt' => t('news.post.add'),
                    'link' => userpanel\url('news/add'),
                ],
            ], 'btns');
        }
        $this->addError($error);
    }

    public static function onSourceLoad()
    {
        parent::onSourceLoad();
        if (parent::$navigation) {
            $addnew = new MenuItem('addnew');
            $addnew->setTitle(t('new.add'));
            $addnew->setIcon('fa fa-plus');
            $addnew->setURL(userpanel\url('news/add'));

            $comments = new MenuItem('comments');
            $comments->setTitle(t('news.comments'));
            $comments->setIcon('fa fa-comments-o');
            $comments->setURL(userpanel\url('news/comments'));

            $index = new MenuItem('index');
            $index->setTitle(t('news'));
            $index->setIcon('fa fa-newspaper-o');
            $index->setURL(userpanel\url('news'));

            $item = new MenuItem('news');
            $item->setTitle(t('news'));
            $item->setIcon('fa fa-newspaper-o');
            $item->addItem($index);
            $item->addItem($addnew);
            $item->addItem($comments);
            Navigation::addItem($item);
        }
    }

    public function setButtons()
    {
        $this->setButton('news_edit', $this->canEdit, [
            'title' => t('edit'),
            'icon' => 'fa fa-edit',
            'classes' => ['btn', 'btn-xs', 'btn-teal'],
        ]);
        $this->setButton('news_delete', $this->canDel, [
            'title' => t('delete'),
            'icon' => 'fa fa-times',
            'classes' => ['btn', 'btn-xs', 'btn-bricky'],
        ]);
    }

    public function getComparisonsForSelect()
    {
        return [
            [
                'title' => t('search.comparison.contains'),
                'value' => 'contains',
            ],
            [
                'title' => t('search.comparison.equals'),
                'value' => 'equals',
            ],
            [
                'title' => t('search.comparison.startswith'),
                'value' => 'startswith',
            ],
        ];
    }
}
