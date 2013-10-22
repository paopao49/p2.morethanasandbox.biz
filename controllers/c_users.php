<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
    } 

    public function index() {
        echo "This is the index page";
    }

    public function signup() {

        $this->template->content = View::instance('v_users_signup');
        $this->template->title = "Sign up with Chatster!";

        $client_files_head = Array(
            );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $client_files_body = Array(
            );
        $this->template->client_files_body = Utils::load_client_files($client_files_body);

        echo $this->template;
    }

    public function p_signup() {

        $_POST['created'] = Time::now();
        $_POST['modified'] = Time::now();

        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        $_POST['token'] = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());

        $user_id = DB::instance(DB_NAME)->insert('users',$_POST);

        # developing instant sign-in after sign up
        Router::redirect("/users/login");
    }

    public function login($error = NULL) {

        $this->template->content = View::instance('v_users_login');
        $this->template->content->error = $error;

        $this->template->title = "Log in to Chatster!";

        $client_files_head = Array(
            );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $client_files_body = Array(
            );
        $this->template->client_files_body = Utils::load_client_files($client_files_body);

        echo $this->template;
    }

   /* 
    public function p_login() {

        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        $q = "
            SELECT token
            FROM users
            WHERE
                email = '".$_POST['email']."'
                AND password = '".$_POST['password']."'
            ";

        $token = DB::instance(DB_NAME)->select_field($q);

        if(!$token) {
            Router::redirect("/users/login/error");
        } else {
            setcookie("token",$token,strtotime('+1 year'),'/');

            Router::redirect("/");
        }
    }
    */
        

    
    public function p_login() {

        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        $q_token = "
            SELECT token
            FROM users
            WHERE
                email = '".$_POST['email']."'
                AND password = '".$_POST['password']."'
            ";

        $q_email = "
            SELECT email
            FROM users
            WHERE
                email = '".$_POST['email']."'
            ";

        $token = DB::instance(DB_NAME)->select_field($q_token);
        $em = DB::instance(DB_NAME)->select_field($q_email);

        if(!$em) {

            Router::redirect("/users/login/no_email");

        } elseif(!$token) {

            Router::redirect("/users/login/no_token");

        } else {

            setcookie("token",$token,strtotime('+1 year'),'/');

            Router::redirect("/");
        }
    }
    

    public function logout() {
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());

        $data = Array("token" => $new_token);

        DB::instance(DB_NAME)->update("users",$data,"WHERE token = '".$this->user->token."'");

        setcookie("token","",strtotime('-1 year'),'/');

        Router::redirect("/");
    }

    public function profile() {

        if(!$this->user){
            Router::redirect('/users/login');
        }

        $this->template->content = View::instance('v_users_profile');
        $this->template->title = "Profile of ".$this->user->first_name;

        $client_files_head = Array(
            "/css/widgets.css",
            "/css/profile.css"
            );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $client_files_body = Array(
            "/js/profile.min.js",
            "/js/widgets.min.js"
            );
        
        $this->template->client_files_body = Utils::load_client_files($client_files_body);

        echo $this->template;

    }

} # end of the class