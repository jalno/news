<?php

namespace themes\clipone\Views\News\Panel;

use packages\base\View\Error;
use packages\news\Views\Panel\Index as NewsIndex;
use packages\userpanel;
use themes\clipone\Navigation;
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
