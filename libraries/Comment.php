<?php

namespace packages\news;

use packages\base\DB\DBObject;

class Comment extends DBObject
{
    public const accepted = 1;
    public const pending = 2;
    public const unverified = 3;
    protected $dbTable = 'news_comments';
    protected $primaryKey = 'id';
    protected $dbFields = [
        'post' => ['type' => 'int', 'required' => true],
        'reply' => ['type' => 'int'],
        'email' => ['type' => 'text', 'required' => true],
        'name' => ['type' => 'text', 'required' => true],
        'date' => ['type' => 'int', 'required' => true],
        'text' => ['type' => 'text', 'required' => true],
        'status' => ['type' => 'int', 'required' => true],
    ];
    protected $relations = [
        'new' => ['hasOne', NewPost::class, 'post'],
    ];

    protected function preLoad($data)
    {
        if (!isset($data['date'])) {
            $data['date'] = time();
        }
        if (!isset($data['status'])) {
            $data['status'] = self::pending;
        }

        return $data;
    }
}
