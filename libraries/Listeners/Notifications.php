<?php

namespace packages\news\Listeners;

use packages\news\Events as NewsEvents;
use packages\notifications\Events;

class Notifications
{
    public function events(Events $events)
    {
        $events->add(NewsEvents\Posts\Add::class);
        $events->add(NewsEvents\Comments\Add::class);
    }
}
