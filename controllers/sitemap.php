<?php
namespace packages\news\controllers;
use \packages\base;
use \packages\base\db;

use \packages\news\controller;
use \packages\news\view;
use \packages\news\newpost;
use \packages\news\comment;

use \packages\userpanel\date;

use \packages\sitemap\item;

class sitemap extends controller{
	public function import(){
		return array_merge($this->build_posts(), $this->build_authors(), $this->build_archive());
	}
	private function build_posts(){
		$new = new newpost();
		$new->where('status', newpost::published);
		$new->orderBy('date', 'DESC');
		$news = $new->get();
		$items = array();
		foreach($news as $new){
			$item = new item();
			$item->setURI(base\url('news/view/'.$new->id, array(), true));
			$item->SetChangeFreq(item::monthly);
			$item->setLastModified($new->date);
			$item->setPriority(0.4);
			$items[] = $item;
		}
		return $items;
	}
	private function build_authors(){
		$items = array();
		db::where("status", newpost::published);
		db::setQueryOption('DISTINCT');
		$authors = array_column(db::get("news_posts",null, array("author")), 'author');
		foreach($authors as $author){
			db::where("author", $author);
			db::where("status", newpost::published);
			$lastmodified = db::getValue("news_posts", "date");

			$item = new item();
			$item->setURI(base\url('news/author/'.$author, array(), true));
			$item->SetChangeFreq(item::weekly);
			$item->setLastModified($lastmodified);
			$item->setPriority(0.3);
			$items[] = $item;
		}
		return $items;
	}
	private function build_archive(){

		$items = array();
		$new = new newpost();
		$new->where('status', newpost::published);
		$news = $new->get();

		$months = array();
		foreach($news as $new){
			$month = date::format("m", $new->date);
			$yearl = date::format('Y', $new->date);
			$months[] = date::mktime(0, 0, 0, $month, 1, $yearl);

		}
		foreach(array_unique($months) as $month){
			$period = date::format("Y/m", $month);
			$last = $month + 86400*30;
			$lastmodified = 0;
			foreach($news as $new){
				if($new->date >= $month and $new->date <= $last and $lastmodified < $new->date){
					$lastmodified = $new->date;
				}
			}
			$item = new item();
			$item->setURI(base\url('news/archive/'.$period, array(), true));
			$item->SetChangeFreq(item::monthly);
			$item->setLastModified($lastmodified);
			$item->setPriority(0.3);
			$items[] = $item;
		}



		return $items;
	}
}
