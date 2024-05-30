<?php

namespace packages\news\Views\Panel;

use packages\news\Authorization;
use packages\news\Views\Form;

class Add extends Form
{
    protected static $navigation;

    public static function onSourceLoad()
    {
        self::$navigation = Authorization::is_accessed('list');
    }
}
