<?php

namespace packages\news\Views\Panel;

use packages\news\Views\Form;

class CommentDelete extends Form
{
    public function setComment($comment)
    {
        $this->setData($comment, 'comment');
    }

    public function getComment()
    {
        return $this->getData('comment');
    }
}
