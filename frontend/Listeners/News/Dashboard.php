<?php

namespace themes\clipone\Listeners\News;

use packages\base;
use packages\news\NewPost;
use themes\clipone\Views\Dashboard as View;
use themes\clipone\Views\Dashboard\Panel;

class Dashboard
{
    public function initialize()
    {
        $news = NewPost::where('status', NewPost::published)->orderby('date', 'desc')->get(5);
        if ($news) {
            $panel = new Panel('lastnews');
            $panel->icon = 'clip-note';
            $panel->size = 12;
            $panel->title = t('news');
            foreach ($news as $new) {
                $html = '<div class="well well-sm noticeboard">';
                $html .= '<a href="'.base\url('news/view/'.$new->id).'" target="_blank"><h4>'.$new->title.'</h4></a>';
                $html .= strip_tags($new->firstPartContent(), '<p><a><b><strong><ul><li><i><u><s><blockquote>');
                $html .= '<a href="'.base\url('news/view/'.$new->id).'" class="readmore">'.t('post.readmore').'</a>';
                $html .= '</div>';
                $panel->html .= $html;
            }
            View::addBox($panel);
        }
    }
}
