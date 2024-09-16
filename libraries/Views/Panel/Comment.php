<?php

namespace packages\news\Views\Panel;

use packages\news\Authorization;
use packages\news\View;

class Comment extends View
{
    protected $canAdd;
    protected $canEdit;
    protected $canDel;
    protected static $navigation;

    public function __construct()
    {
        $this->canAdd = Authorization::is_accessed('add');
        $this->canEdit = Authorization::is_accessed('edit');
        $this->canDel = Authorization::is_accessed('edit');
    }

    public function setComments($news)
    {
        $this->setData($news, 'news');
    }

    public function getComments()
    {
        return $this->getData('news');
    }
}
