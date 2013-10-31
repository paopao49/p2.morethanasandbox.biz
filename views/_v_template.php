<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
	
	<link rel="stylesheet" type="text/css" href="/css/_v_template.css">

	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	

	<!-- Menu that is persistent throughout application -->
	<div class='menu'>

		<!-- Home link -->
		<a href='/'>Chatster</a>

		<?php if($user): ?>

			<a class='menu_right' href='/users/logout'>Log out</a>			
			<a class='menu_right' href='/users/profile'>Profile</a>

		<?php else: ?>

			<a class='menu_right' href='/users/login'>Log In</a>
			<a class='menu_right' href='/users/signup'>Sign Up</a>			

		<?php endif; ?>

	</div>

	<div class='sub_menu'>
		<a href='/posts/users'>Friends</a>
		<a href='/posts/add'>Post</a>
		<a href='/posts'>Read</a>

		<!-- empty div to wrap border around floating sub_menu links -->
		<div id='clear_div'></div>	
	</div>		
	<br>

	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>

</body>
</html>