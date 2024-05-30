<?php
namespace packages\news\Controllers;
use \packages\base;
use \packages\base\DB;

use \packages\news\Controller;
use \packages\news\View;
use \packages\news\NewPost;
use \packages\news\Comment;

use \packages\userpanel\Date;

use \packages\sitemap\Item;

class SiteMap extends Controller{
	public function import(){
		return array_merge($this->build_posts(), $this->build_authors(), $this->build_archive());
	}
	private function build_posts(){
		$new = new NewPost();
		$new->where('status', NewPost::published);
		$new->orderBy('date', 'DESC');
		$news = $new->get();
		$items = array();
		foreach($news as $new){
			$item = new Item();
			$item->setURI(base\url('news/view/'.$new->id, array(), true));
			$item->SetChangeFreq(Item::monthly);
			$item->setLastModified($new->date);
			$item->setPriority(0.4);
			$items[] = $item;
		}
		return $items;
	}
	private function build_authors(){
		$items = array();
		DB::where("status", NewPost::published);
		DB::setQueryOption('DISTINCT');
		$authors = array_column(db::get("news_posts",null, array("author")), 'author');
		foreach($authors as $author){
			DB::where("author", $author);
			DB::where("status", NewPost::published);
			$lastmodified = DB::getValue("news_posts", "date");

			$item = new Item();
			$item->setURI(base\url('news/author/'.$author, array(), true));
			$item->SetChangeFreq(Item::weekly);
			$item->setLastModified($lastmodified);
			$item->setPriority(0.3);
			$items[] = $item;
		}
		return $items;
	}
	private function build_archive(){

		$items = array();
		$new = new NewPost();
		$new->where('status', NewPost::published);
		$news = $new->get();

		$months = array();
		foreach($news as $new){
			$month = Date::format("m", $new->date);
			$yearl = Date::format('Y', $new->date);
			$months[] = Date::mktime(0, 0, 0, $month, 1, $yearl);

		}
		foreach(array_unique($months) as $month){
			$period = Date::format("Y/m", $month);
			$last = $month + 86400*30;
			$lastmodified = 0;
			foreach($news as $new){
				if($new->date >= $month and $new->date <= $last and $lastmodified < $new->date){
					$lastmodified = $new->date;
				}
			}
			$item = new Item();
			$item->setURI(base\url('news/archive/'.$period, array(), true));
			$item->SetChangeFreq(Item::monthly);
			$item->setLastModified($lastmodified);
			$item->setPriority(0.3);
			$items[] = $item;
		}



		return $items;
	}
}
