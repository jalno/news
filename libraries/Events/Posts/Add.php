<?php

namespace packages\news\Events\Posts;

use packages\base\DB;
use packages\base\Event;
use packages\news\NewPost as Post;
use packages\notifications\Notifiable;
use packages\userpanel\User;

class Add extends Event implements Notifiable
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public static function getName(): string
    {
        return 'news_post_add';
    }

    public static function getParameters(): array
    {
        return [Post::class];
    }

    public function getArguments(): array
    {
        return [
            'newpost' => $this->getPost(),
        ];
    }

    public function getTargetUsers(): array
    {
        $users = [];
        DB::join('userpanel_usertypes', 'userpanel_usertypes.id=userpanel_users.type', 'INNER');
        DB::join('userpanel_usertypes_permissions', 'userpanel_usertypes_permissions.type=userpanel_usertypes.id', 'LEFT');
        $user = new User();
        $user->where('userpanel_usertypes_permissions.name', 'news_edit');
        foreach ($user->get(null, 'userpanel_users.*') as $user) {
            $users[$user->id] = $user;
        }
        unset($users[$this->post->author->id]);

        return array_values($users);
    }
}
