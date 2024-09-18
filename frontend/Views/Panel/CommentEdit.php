<?php

namespace themes\clipone\Views\News\Panel;

use packages\base\Frontend\Theme;
use packages\base\Translator;
use themes\clipone\Navigation;
use themes\clipone\Views\FormTrait;
use themes\clipone\ViewTrait;

class CommentEdit extends \packages\news\Views\Panel\CommentEdit
{
    use ViewTrait;
    use FormTrait;

    public function __beforeLoad()
    {
        $this->setTitle([
            t('comment'),
            t('edit'),
        ]);
        Navigation::active('news/comments');
        $this->addAssets();
    }

    protected function addAssets()
    {
        $this->addJSFile(Theme::url('assets/plugins/ckeditor/ckeditor.js'));
    }

    protected function setNewsForSelect()
    {
        $news = [];
        foreach ($this->getNews() as $new) {
            $news[] = [
                'title' => $new->title,
                'value' => $new->id,
            ];
        }

        return $news;
    }
}
