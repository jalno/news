<?php

namespace themes\clipone\Views\News\Panel;

use packages\base\Translator;
use packages\news\Views\Panel\Delete as NewDelete;
use themes\clipone\Navigation;
use themes\clipone\Views\FormTrait;
use themes\clipone\ViewTrait;

class Delete extends NewDelete
{
    use ViewTrait;
    use FormTrait;

    public function __beforeLoad()
    {
        $this->setTitle([
            t('news'),
            t('delete'),
        ]);
        Navigation::active('news/index');
    }
}
