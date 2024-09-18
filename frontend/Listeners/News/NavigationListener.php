<?php
namespace themes\clipone\Listeners\News;

use packages\news\Authorization;
use themes\clipone\Navigation;
use themes\clipone\Navigation\MenuItem;

use function packages\userpanel\url;

class NavigationListener {
	public function initial(): void
	{
		if (Authorization::is_accessed('list')) {
			$addnew = new MenuItem('addnew');
			$addnew->setTitle(t('new.add'));
			$addnew->setIcon('fa fa-plus');
			$addnew->setURL(url('news/add'));

			$comments = new MenuItem('comments');
			$comments->setTitle(t('news.comments'));
			$comments->setIcon('fa fa-comments-o');
			$comments->setURL(url('news/comments'));

			$index = new MenuItem('index');
			$index->setTitle(t('news'));
			$index->setIcon('fa fa-newspaper-o');
			$index->setURL(url('news'));

			$item = new MenuItem('news');
			$item->setTitle(t('news'));
			$item->setIcon('fa fa-newspaper-o');
			$item->addItem($index);
			$item->addItem($addnew);
			$item->addItem($comments);
			Navigation::addItem($item);
		}
	}
}