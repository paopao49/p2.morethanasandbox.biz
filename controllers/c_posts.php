<?php
class posts_controller extends base_controller {

	public function __construct() {

		parent::__construct();

		# Posts class only accessible by authenticated users
		if(!$this->user) {
			Router::redirect("/index/index/error");
		}		

	} # End of method

	public function index($error = NULL) {

		$this->template->content = View::instance('v_posts_index');
		$this->template->title = "All Posts";
		$this->template->content->user_id = $this->user->user_id;
		$this->template->content->error = $error;		

        $client_files_head = Array(
            "/css/v_posts_index.css"
        );

        $this->template->client_files_head = Utils::load_client_files($client_files_head); 		

		# Always see own posts in addition to posts of users followed
		# Sort in reverse chronological order
		# Add like/unlike flag - likes.like_id will be null if user has not liked post
		# Follower_id isn't used - this was included as a result of following class curriculum
		$q = "
			SELECT
				posts.post_id,
				posts.content,
				posts.created,
				posts.user_id AS post_user_id,
				users_users.user_id AS follower_id,
				users.first_name,
				users.last_name,
				likes.like_id
			FROM posts
			JOIN users
				ON posts.user_id = users.user_id
			LEFT JOIN users_users				
				ON (posts.user_id = users_users.user_id_followed
				AND users_users.user_id = ".$this->user->user_id.")
			LEFT JOIN likes
				ON (posts.post_id = likes.post_id
				AND likes.user_id = ".$this->user->user_id.")					
			WHERE
				posts.user_id in (
						SELECT
							DISTINCT user_id_followed
						FROM users_users
						WHERE
							user_id = ".$this->user->user_id."

						UNION ALL

						SELECT
							DISTINCT user_id
						FROM users
						WHERE
							user_id = ".$this->user->user_id."
						)
			ORDER BY 
				posts.created desc"
		; # End of $q

		# Create posts array
		$posts = DB::instance(DB_NAME)->select_rows($q);

		# Add like_count to every post within $posts
		foreach($posts as $array_key => &$array) {

				# Pull likes for given post_id
				$q = "
					SELECT *
					FROM likes
					WHERE
						post_id =".$array['post_id']
				;

				# Count likes for given post_id
				$post_likes = count(DB::instance(DB_NAME)->select_array($q,'like_id'));

				# Assign like count value to given post
				$array['like_count'] = $post_likes;

		} # End of foreach

		$this->template->content->posts = $posts;

		echo $this->template;

	} # End of method	

	public function add() {

		$this->template->content = View::instance('v_posts_add');
		$this->template->title = "New Post";

        $client_files_head = Array(
            "/css/v_posts_add.css"
        );

        $this->template->client_files_head = Utils::load_client_files($client_files_head); 				

		echo $this->template;

	} # End of method

	public function p_add() {

		# Only update 'posts' database if $_POST has values
		if($_POST) {

			$_POST['user_id'] = $this->user->user_id;

			$_POST['created'] = Time::now();
			$_POST['modified'] = Time::now();

			DB::instance(DB_NAME)->insert('posts',$_POST);

			Router::redirect("/posts/");

		} else {

			Router::redirect("/posts/add");
		}

	} # End of method

	# Extra feature: delete posts
	public function delete($post_id_delete = NULL) {

		# Error checking if method is called without argument
		if(!$post_id_delete) {

			Router::redirect("/posts/");

		}			

		# Can only delete own posts
		$where_for_delete = 'WHERE post_id = '.$post_id_delete.' AND user_id = '.$this->user->user_id;

		DB::instance(DB_NAME)->delete('posts', $where_for_delete);

		Router::redirect("/posts/");

	} # End of method

	public function users($error = NULL) {

		$this->template->content = View::instance("v_posts_users");
		$this->template->title = "Users";
		$this->template->content->error = $error;

        $client_files_head = Array(
            "/css/v_posts_users.css"
        );

        $this->template->client_files_head = Utils::load_client_files($client_files_head);        

		# Filter out self so user cannot follow self
		$q = "
			SELECT *
			FROM users
			WHERE
				user_id !=".$this->user->user_id
		;

		$users = DB::instance(DB_NAME)->select_rows($q);

		$q2 = "
			SELECT *
			FROM users_users
			WHERE
				user_id = ".$this->user->user_id
		;		

		$connections = DB::instance(DB_NAME)->select_array($q2, 'user_id_followed');

		$this->template->content->users = $users;
		$this->template->content->connections = $connections;

		echo $this->template;

	} # End of method

	public function follow($user_id_followed = NULL) {

		# Test if user tries to call follow method without an argument
		if(!$user_id_followed) {

			Router::redirect("/posts/users");

		} else {

			# Used to test if $user_id_followed is already being followed
	        $q_follow_user = "
	            SELECT user_id_followed
	            FROM users_users
	            WHERE
	            	user_id = '".$this->user->user_id."'
	            	AND user_id_followed = ".$user_id_followed
	        ;

	        $followed_user = DB::instance(DB_NAME)->select_field($q_follow_user);	

	        # Used to test if $user_id_followed exists
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

	      		Router::redirect("/posts/users/follow_error");

	      	} else {

				$data = Array(
					"created" => Time::now(),
					"user_id" => $this->user->user_id,
					"user_id_followed" => $user_id_followed
				);

				DB::instance(DB_NAME)->insert('users_users', $data);

				Router::redirect("/posts/users");

			} # End of inner else 

		} # End of outer else

	} # End of method

	public function like($post_id_like = NULL) {

		# Test if user tries to call like method without an argument
		if(!$post_id_like) {

			Router::redirect("/posts/");

		} else {

			# Used to test if $post_id_like is already being liked
	        $q_user_like = "
	            SELECT like_id
	            FROM likes
	            WHERE
	            	user_id = '".$this->user->user_id."'
	            	AND post_id = ".$post_id_like
	        ;

	        $user_like = DB::instance(DB_NAME)->select_field($q_user_like);	

	        # Used to test if post that user is trying to like exists
	        $q_any_like = "
	            SELECT post_id
	            FROM posts
	            WHERE
	            	post_id = ".$post_id_like
	        ;

	        $any_like = DB::instance(DB_NAME)->select_field($q_any_like);	     

        	# Test if user is trying to like a post the user already likes AND
	       	# Test if the user is trying to like a post that doesn't exist
	      	if($user_like or !$any_like) {

	      		Router::redirect("/posts/index/like_error");

	      	} else {

				$data = Array(
					"created" => Time::now(),
					"user_id" => $this->user->user_id,
					"post_id" => $post_id_like
					);

				DB::instance(DB_NAME)->insert('likes', $data);

				Router::redirect("/posts/");

			} # End of inner else
			
		} # End of outer else
		
	} # End of method

	public function unfollow($user_id_followed) {

		# Error checking if method is called without argument
		if(!$user_id_followed) {

			Router::redirect("/posts/users");

		}

		$where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;

		DB::instance(DB_NAME)->delete('users_users', $where_condition);

		Router::redirect("/posts/users");
	}

	public function unlike($post_id_unlike) {

		# Error checking if method is called without argument
		if(!$post_id_unlike) {

			Router::redirect("/posts/users");

		}		

		$where_condition = 'WHERE user_id = '.$this->user->user_id.' AND post_id = '.$post_id_unlike;

		DB::instance(DB_NAME)->delete('likes', $where_condition);

		Router::redirect("/posts/");
	}	

} # end of class
?>