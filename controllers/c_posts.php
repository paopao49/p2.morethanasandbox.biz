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
		$this->template->content->user_id = $this->user->user_id;

		# Always see own posts
		# Sort in reverse chronological order
		$q = "
			SELECT
				posts.content,
				posts.created,
				posts.user_id AS post_user_id,
				users_users.user_id AS follower_id,
				users.first_name,
				users.last_name
			FROM posts
			LEFT JOIN users_users
				ON posts.user_id = users_users.user_id_followed
			INNER JOIN users
				on posts.user_id = users.user_id
			WHERE
				(users_users.user_id = ".$this->user->user_id."
				OR users.user_id =".$this->user->user_id.")
			ORDER BY 
				posts.created desc"
			;

		$posts = DB::instance(DB_NAME)->select_rows($q);

		$this->template->content->posts = $posts;

		echo '<pre>';
		print_r($posts);
		echo '</pre>';

		# echo $this->template;

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

			Router::redirect("/posts/");

		} else {

			Router::redirect("/posts/add");
		}

	}

	public function users($error = NULL) {

		$this->template->content = View::instance("v_posts_users");
		$this->template->title = "Users";
		$this->template->content->error = $error;

		# Filter out self so user cannot follow self
		$q = "
			SELECT *
			FROM users
			WHERE
				user_id !=".$this->user->user_id
			;

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

	public function follow($user_id_followed = NULL) {

		# Test if user tries to call follow method without an argument
		if(!$user_id_followed) {

			Router::redirect("/posts/users");

		} else {

	        $q_follow_user = "
	            SELECT user_id_followed
	            FROM users_users
	            WHERE
	            	user_id = '".$this->user->user_id."'
	            	AND user_id_followed = ".$user_id_followed
	        ;

	        $followed_user = DB::instance(DB_NAME)->select_field($q_follow_user);	

	        $q_any_user = "
	            SELECT user_id
	            FROM users
	            WHERE
	            	user_id = ".$user_id_followed
	        ;

	        $any_user = DB::instance(DB_NAME)->select_field($q_any_user);	     

        	# Test if user is trying to follow someone the user is already following AND
	       	# Test if the user is trying to follow someone who doesn't exist
	      	if($followed_user or !$any_user) {

	      		Router::redirect("/posts/users/error");

	      	} else {

				$data = Array(
					"created" => Time::now(),
					"user_id" => $this->user->user_id,
					"user_id_followed" => $user_id_followed
					);

				DB::instance(DB_NAME)->insert('users_users', $data);

				Router::redirect("/posts/users");

			}
		}
	}

	public function unfollow($user_id_followed) {

		$where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;

		DB::instance(DB_NAME)->delete('users_users', $where_condition);

		Router::redirect("/posts/users");
	}

} # end of class
?>