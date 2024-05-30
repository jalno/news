<?php

namespace packages\news;

use packages\userpanel\Authorization as UserPanelAuthorization;

class Authorization extends UserPanelAuthorization
{
    public static function is_accessed($permission, $prefix = 'news')
    {
        return parent::is_accessed($permission, $prefix);
    }

    public static function haveOrFail($permission, $prefix = 'news')
    {
        parent::haveOrFail($permission, $prefix);
    }
}
