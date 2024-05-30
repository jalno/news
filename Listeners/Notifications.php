<?php
namespace packages\news\Listeners;
use \packages\notifications\Events;
use \packages\news\Events as NewsEvents;
class Notifications{
	public function events(Events $events){
		$events->add(NewsEvents\Posts\Add::class);
		$events->add(NewsEvents\Comments\Add::class);
	}
}