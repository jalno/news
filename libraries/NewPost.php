<?php

namespace packages\news;

use packages\base\DB;
use packages\base\DB\DBObject;

class NewPost extends DBObject
{
    public const published = 1;
    public const unpublished = 2;
    protected $dbTable = 'news_posts';
    protected $primaryKey = 'id';
    protected $dbFields = [
        'title' => ['type' => 'text', 'required' => true],
        'date' => ['type' => 'int', 'required' => true],
        'description' => ['type' => 'text', 'required' => true],
        'author' => ['type' => 'int', 'required' => true],
        'content' => ['type' => 'text', 'required' => true],
        'view' => ['type' => 'int'],
        'image' => ['type' => 'text'],
        'status' => ['type' => 'int', 'required' => true],
    ];
    protected $relations = [
        'author' => ['hasOne', \packages\userpanel\User::class, 'author'],
        'comments' => ['hasMany', Comment::class, 'post'],
        'files' => ['hasMany', File::class, 'post'],
    ];

    public function breakContent()
    {
        $content = [$this->content, ''];
        if (preg_match("/([\s\S]*)<div+.+style=\"page-break-after:always\".*>.*<\/div>([\s\S]*)/im", $this->content, $matches)) {
            $content = [$matches[1], $matches[2]];
        }

        return $content;
    }

    public function firstPartContent()
    {
        return $this->breakContent()[0];
    }

    public function secondPartContent()
    {
        return $this->breakContent()[1];
    }

    public function getCountPostCommnets()
    {
        DB::where('post', $this->id);
        DB::where('status', Comment::accepted);

        return DB::getValue('news_comments', 'count(*)');
    }

    public function setAttachments(array $attachments)
    {
        $files = $this->files;
        foreach ($attachments as $attachment) {
            $found = false;
            foreach ($files as $key => $file) {
                if ($file->id == $attachment->id) {
                    $found = true;
                    unset($files[$key]);
                    break;
                }
            }
            if (!$found) {
                $attachment->post = $this->id;
                $attachment->save();
            }
        }
        foreach ($files as $file) {
            $file->post = null;
            $file->save();
        }
    }
}
