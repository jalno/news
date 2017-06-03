<?php
namespace packages\news\listeners;
use \packages\notifications\events;
use \packages\news\events as newsEevents;
class notifications{
	public function events(events $events){
		$events->add(newsEevents\posts\add::class);
		$events->add(newsEevents\comments\add::class);
	}
}