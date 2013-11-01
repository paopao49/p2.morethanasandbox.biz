<?php
class users_controller extends base_controller {

    public function __construct() {

        parent::__construct();

    } 

    public function index() {

        if(!$this->user) {

            Router::redirect("/users/login");

        } else {

            Router::redirect("/");
        }
        
    } # End of method

    public function signup($error = NULL) {

        if(!$this->user) {

            $this->template->content = View::instance('v_users_signup');
            $this->template->title = "Sign up with Chatster!";
            $this->template->content->error = $error;            

            $client_files_head = Array(
                "/css/v_users_signup_and_users_login.css"
            );

            $this->template->client_files_head = Utils::load_client_files($client_files_head);        

            echo $this->template;

        } else {

            Router::redirect("/");

        }
    } # End of method

    public function p_signup() {

        # Only allow if $_POST is not null
        if($_POST) {

            # Test if all fields are entered
            if( !$_POST['first_name'] or
                !$_POST['last_name'] or
                !$_POST['email'] or
                !$_POST['password']
            ) {

                Router::redirect("/users/signup/error");

            } else {

                # Used to test if user is registering with email that is already registered
                $q_email = "
                    SELECT email
                    FROM users
                    WHERE
                        email = '".$_POST['email']."'
                ";

                $existing_email = DB::instance(DB_NAME)->select_field($q_email);

                # Test if email already exists
                if($existing_email) {

                    Router::redirect("/users/signup/duplicate_email");

                } else {

                    $_POST['created'] = Time::now();
                    $_POST['modified'] = Time::now();

                    $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

                    $_POST['token'] = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());

                    $user_id = DB::instance(DB_NAME)->insert('users',$_POST);

                    $q_token = "
                        SELECT token
                        FROM users
                        WHERE
                            user_id = ".$user_id
                    ;

                    $token = DB::instance(DB_NAME)->select_field($q_token);

                    setcookie("token",$token,strtotime('+1 year'),'/');

                    Router::redirect("/");

                } # End of inner else

            } # End of outer else

        } else {

            Router::redirect("/users/signup");

        }
    } # End of method

    public function login($error = NULL) {

        # Redirect users already logged in to home page
        if(!$this->user) {

        $this->template->content = View::instance('v_users_login');
        $this->template->title = "Log in to Chatster!";
        $this->template->content->error = $error;        

        $client_files_head = Array(
            "/css/v_users_signup_and_users_login.css"
        );

        $this->template->client_files_head = Utils::load_client_files($client_files_head);        

        echo $this->template;

        } else {

            Router::redirect("/");

        }

    } # End of method
    
    public function p_login() {

        # Redirect to home page if $_POST is null
        if($_POST) {

            $_POST = DB::instance(DB_NAME)->sanitize($_POST);

            $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

            # Only get token if password matches
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

            # Get token given user credentials
            $token = DB::instance(DB_NAME)->select_field($q_token);

            # Get email given user credentials
            $em = DB::instance(DB_NAME)->select_field($q_email);

            # No email error redirect
            if(!$em) {

                Router::redirect("/users/login/no_email");

            # Wrong password redirect
            } elseif(!$token) {

                Router::redirect("/users/login/no_token");

            # Successful login
            } else {

                setcookie("token",$token,strtotime('+1 year'),'/');

                Router::redirect("/");
            }

        } else {

            Router::redirect("/");
        }

    } # End of method
    

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
        $this->template->title = $this->user->first_name;

        $client_files_head = Array(
            "/css/v_index_index_and_users_profile.css"
        );

        $this->template->client_files_head = Utils::load_client_files($client_files_head);        

        echo $this->template;

    }

} # end of the class