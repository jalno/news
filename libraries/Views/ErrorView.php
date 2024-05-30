<?php

namespace packages\news\Views;

use packages\news\View;

class ErrorView extends View
{
    protected $errorcode;
    protected $errortext;

    public function __construct()
    {
        parent::__construct('errors.php');
    }

    public function setErrorCode($code)
    {
        $this->errorcode = $code;
    }

    public function setErrorText($text)
    {
        $this->errortext = $text;
    }
}
