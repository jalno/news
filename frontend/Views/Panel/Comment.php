<?php

namespace themes\clipone\Views\News\Panel;

use packages\base\Translator;
use packages\news\Views\Panel\Comment as NewsComment;
use themes\clipone\Navigation;
use themes\clipone\Views\ListTrait;
use themes\clipone\ViewTrait;

class Comment extends NewsComment
{
    use ViewTrait;
    use ListTrait;

    public function __beforeLoad()
    {
        $this->setTitle([
            Translator::trans('news'),
            Translator::trans('list'),
            Translator::trans('news.unpublished'),
        ]);
        Navigation::active('news/comments');
        $this->setButtons();
    }

    public function setButtons()
    {
        $this->setButton('comment_edit', $this->canEdit, [
            'title' => Translator::trans('edit'),
            'icon' => 'fa fa-edit',
            'classes' => ['btn', 'btn-xs', 'btn-warning'],
        ]);
        $this->setButton('comment_delete', $this->canDel, [
            'title' => Translator::trans('delete'),
            'icon' => 'fa fa-times',
            'classes' => ['btn', 'btn-xs', 'btn-bricky'],
        ]);
    }
}
