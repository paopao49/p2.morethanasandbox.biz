<?php if($user): ?>

	<h1>Hi <?=$user->first_name?>, welcome to Chatster!</h1>

	<a href="/posts/users">Find friends.</a>
	<br>

	<a href="/posts/add">Make a post.</a>
	<br>

	<a href="/posts/">Your post feed!</a>

<?php else: ?>

	<h1>Welcome to Chatster!</h1>
	<br>
	<p>Extra features:</p>
	<ul>
		<li>Ability to delete posts</li>
		<li>Ability to like or unlike posts (as well as like counter)</li>
	</ul>	

	<a href="/users/signup">Sign up!</a>
	<br><br>

	<a href="/users/login">Log In</a>

<?php endif; ?>