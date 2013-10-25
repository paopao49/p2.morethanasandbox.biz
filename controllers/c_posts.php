<?php
class posts_controller extends base_controller {

	public function __construct() {
		parent::__construct();

		if(!$this->user) {
			die("Members only. <a href='/users/login'>Log In</a>");
		}		
	}

	public function add() {
		$this->template->content = View::instance('v_posts_add');
		$this->template->title = "New Post";

		echo $this->template;
	}

	public function p_add() {
		$_POST['user_id'] = $this->user->user_id;

		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();

		DB::instance(DB_NAME)->insert('posts',$_POST);

		echo "Your post has been added. <a href='/posts/add'>Add another</a>";

	}

	public function index() {
		$this->template->content = View::instance('v_posts_index');
		$this->template->title = "Posts";

		$q = "
			SELECT
				posts.*,
				users.first_name,
				users.last_name
			FROM posts
			INNER JOIN users
				on posts.user_id = users.user_id
			";

		$posts = DB::instance(DB_NAME)->select_rows($q);

		$this->template->content->posts = $posts;

		echo $this->template;

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
				user_id = ".$this->user_id
			;			
	}

} # end of class
?>