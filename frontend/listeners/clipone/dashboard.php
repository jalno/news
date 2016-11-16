<?php
namespace themes\clipone\listeners\news;
use \packages\base;
use \packages\base\translator;
use \packages\news\newpost;
use \themes\clipone\views\dashboard as view;
use \themes\clipone\views\dashboard\panel;
class dashboard{
	public function initialize(){
		$panel = new panel("lastnews");
		$panel->icon = 'clip-note';
		$panel->size = 12;
		$panel->title = translator::trans('news');
		$news = newpost::where("status", newpost::published)->orderby('date', 'desc')->get(5);
		foreach($news as $new){
			$html  = '<div class="well well-sm noticeboard">';
			$html .= '<a href="'.base\url('news/view/'.$new->id).'" target="_blank"><h4>'.$new->title.'</h4></a>';
			$html .= strip_tags($new->firstPartContent(), '<p><a><b><strong><ul><li><i><u><s><blockquote>');
			$html .= '<a href="'.base\url('news/view/'.$new->id).'" class="readmore">'.translator::trans('post.readmore').'</a>';
			$html .= '</div>';
			$panel->html .= $html;
		}
		view::addBox($panel);
	}
}
