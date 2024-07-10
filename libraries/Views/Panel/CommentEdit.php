<?php

namespace packages\news\Views\Panel;

use packages\news\Views\Form;

class CommentEdit extends Form
{
    public function setComment($comment)
    {
        $this->setData($comment, 'comment');
    }

    public function getComment()
    {
        return $this->getData('comment');
    }

    public function setNews($news)
    {
        $this->setData($news, 'news');
    }

    public function getNews()
    {
        return $this->getData('news');
    }
}
