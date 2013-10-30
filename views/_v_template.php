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
	<br>

	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>

</body>
</html>