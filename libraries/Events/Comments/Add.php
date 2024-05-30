<?php
namespace packages\news\Events\Comments;
use \packages\base\DB;
use \packages\base\Event;
use \packages\userpanel\User;
use \packages\notifications\Notifiable;
use \packages\news\Comment;
class Add extends Event implements Notifiable{
	private $comment;
	public function __construct(Comment $comment){
		$this->comment = $comment;
	}
	public function getComment():Comment{
		return $this->comment;
	}
	public static function getName():string{
		return 'news_comment_add';
	}
	public static function getParameters():array{
		return [Comment::class];
	}
	public function getArguments():array{
		return [
			'comment' => $this->getComment()
		];
	}
	public function getTargetUsers():array{
		$users = [];
		DB::join('userpanel_usertypes', 'userpanel_usertypes.id=userpanel_users.type', 'INNER');
		DB::join('userpanel_usertypes_permissions', 'userpanel_usertypes_permissions.type=userpanel_usertypes.id', 'LEFT');
		$user = new User();
		$user->where('userpanel_usertypes_permissions.name', 'news_comments_edit');
		$users = $user->get(null, 'userpanel_users.*');
		return $users;
	}
}