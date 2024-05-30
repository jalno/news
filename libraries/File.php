<?php

namespace packages\news;

use packages\base\DB\DBObject;
use packages\base\Packages;

class File extends DBObject
{
    protected $dbTable = 'news_files';
    protected $primaryKey = 'id';
    protected $dbFields = [
        'post' => ['type' => 'int'],
        'file' => ['type' => 'text', 'required' => true],
        'name' => ['type' => 'text', 'required' => true],
        'size' => ['type' => 'int'],
        'md5' => ['type' => 'text', 'required' => true],
    ];
    protected $relations = [
        'post' => ['hasOne', NewPost::class, 'post'],
    ];

    public function url($absolute = false)
    {
        return Packages::package('news')->url($this->file, $absolute);
    }
}
