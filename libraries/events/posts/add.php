<?php
namespace packages\news\events\posts;
use \packages\base\db;
use \packages\base\event;
use \packages\userpanel\user;
use \packages\notifications\notifiable;
use \packages\news\newpost as post;
class add extends event implements notifiable{
	private $post;
	public function __construct(post $post){
		$this->post = $post;
	}
	public function getPost():post{
		return $this->post;
	}
	public static function getName():string{
		return 'news_post_add';
	}
	public static function getParameters():array{
		return [post::class];
	}
	public function getArguments():array{
		return [
			'newpost' => $this->getPost()
		];
	}
	public function getTargetUsers():array{
		$users = [];
		db::join('userpanel_usertypes', 'userpanel_usertypes.id=userpanel_users.type', 'INNER');
		db::join('userpanel_usertypes_permissions', 'userpanel_usertypes_permissions.type=userpanel_usertypes.id', 'LEFT');
		$user = new user();
		$user->where('userpanel_usertypes_permissions.name', 'news_edit');
		foreach($user->get(null, 'userpanel_users.*') as $user){
			$users[$user->id] = $user;
		}
		unset($users[$this->post->author->id]);
		return array_values($users);
	}
}