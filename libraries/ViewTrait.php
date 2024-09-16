<?php

namespace packages\news;

trait ViewTrait
{
    protected $shortdescription;

    public function setShortDescription($description)
    {
        $this->shortdescription = $description;
    }

    public function getShortDescription()
    {
        return $this->shortdescription;
    }
}