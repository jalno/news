<?php
namespace packages\news\events\comments;
use \packages\base\db;
use \packages\base\event;
use \packages\userpanel\user;
use \packages\notifications\notifiable;
use \packages\news\comment;
class add extends event implements notifiable{
	private $comment;
	public function __construct(comment $comment){
		$this->comment = $comment;
	}
	public function getComment():comment{
		return $this->comment;
	}
	public static function getName():string{
		return 'news_comment_add';
	}
	public static function getParameters():array{
		return [comment::class];
	}
	public function getArguments():array{
		return [
			'comment' => $this->getComment()
		];
	}
	public function getTargetUsers():array{
		$users = [];
		db::join('userpanel_usertypes', 'userpanel_usertypes.id=userpanel_users.type', 'INNER');
		db::join('userpanel_usertypes_permissions', 'userpanel_usertypes_permissions.type=userpanel_usertypes.id', 'LEFT');
		$user = new user();
		$user->where('userpanel_usertypes_permissions.name', 'news_comments_edit');
		$users = $user->get(null, 'userpanel_users.*');
		return $users;
	}
}