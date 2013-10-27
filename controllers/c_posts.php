<?php
class posts_controller extends base_controller {

	public function __construct() {

		parent::__construct();

		if(!$this->user) {
			die("Members only. <a href='/users/login'>Log In</a>");
		}		
	}

	public function index() {

		$this->template->content = View::instance('v_posts_index');
		$this->template->title = "All Posts";

		$q = "
			SELECT
				posts.content,
				posts.created,
				posts.user_id AS post_user_id,
				users_users.user_id AS follower_id,
				users.first_name,
				users.last_name
			FROM posts
			INNER JOIN users_users
				ON posts.user_id = users_users.user_id_followed
			INNER JOIN users
				on posts.user_id = users.user_id
			WHERE
				users_users.user_id = ".$this->user->user_id
			;

		$posts = DB::instance(DB_NAME)->select_rows($q);

		$this->template->content->posts = $posts;

		echo $this->template;

	}	

	public function add() {

		$this->template->content = View::instance('v_posts_add');
		$this->template->title = "New Post";

		echo $this->template;
	}

	public function p_add() {

		if($_POST) {

			$_POST['user_id'] = $this->user->user_id;

			$_POST['created'] = Time::now();
			$_POST['modified'] = Time::now();

			DB::instance(DB_NAME)->insert('posts',$_POST);

			echo "Your post has been added. <a href='/posts/add'>Add another</a>";

		} else {

			Router::redirect("/posts/add");
		}

	}

	public function users() {

		$this->template->content = View::instance("v_posts_users");
		$this->template->title = "Users";

		$q = "
			SELECT *
			FROM users
			";

		$users = DB::instance(DB_NAME)->select_rows($q);

		$q = "
			SELECT *
			FROM users_users
			WHERE
				user_id = ".$this->user->user_id
			;		

		$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

		$this->template->content->users = $users;
		$this->template->content->connections = $connections;

		echo $this->template;

	}

	public function follow($user_id_followed) {

		$data = Array(
			"created" => Time::now(),
			"user_id" => $this->user->user_id,
			"user_id_followed" => $user_id_followed
			);

		DB::instance(DB_NAME)->insert('users_users', $data);

		Router::redirect("/posts/users");
	}

	public function unfollow($user_id_followed) {

		$where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;

		DB::instance(DB_NAME)->delete('users_users', $where_condition);

		Router::redirect("/posts/users");
	}

} # end of class
?>