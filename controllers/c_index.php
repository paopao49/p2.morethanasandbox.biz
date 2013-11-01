<?php

class index_controller extends base_controller {
	
	public function __construct() {
		parent::__construct();
	} 

	public function index() {
		
		# Any method that loads a view will commonly start with this
		# First, set the content of the template with a view file
			$this->template->content = View::instance('v_index_index');
			
		# Now set the <title> tag
			$this->template->title = "Chatster";
	
		# CSS/JS includes
			$client_files_head = Array(
				"/css/v_index_index_and_users_profile.css"
			);

	    	$this->template->client_files_head = Utils::load_client_files($client_files_head);
	    		      					     		
		# Render the view
			echo $this->template;

	} # End of method
	
	
} # End of class
