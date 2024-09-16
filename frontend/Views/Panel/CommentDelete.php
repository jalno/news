<?php

namespace themes\clipone\Views\News\Panel;

use packages\base\Translator;
use themes\clipone\Navigation;
use themes\clipone\Views\FormTrait;
use themes\clipone\ViewTrait;

class CommentDelete extends \packages\news\Views\Panel\CommentDelete
{
    use ViewTrait;
    use FormTrait;

    public function __beforeLoad()
    {
        $this->setTitle([
            t('comment'),
            t('delete'),
        ]);
        Navigation::active('news/comments');
    }
}
