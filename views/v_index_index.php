<!-- Home page if signed in -->
<?php if($user): ?>

	<h1>Hi <?=$user->first_name?>!</h1>
	<br>

	<!-- Groups app buttons together -->
	<div id='app_buttons'>
		<a href="/posts/users">Make friends.</a>

		<a href="/posts/add">Say something.</a>

		<a href="/posts/">Read your stream!</a>
	</div>

<!-- Home page if not signed in -->
<?php else: ?>

	<h1>Welcome to Chatster!</h1>

	<p>Extra features:</p>
	<ul>
		<li>Ability to delete posts</li>
		<li>Ability to like or unlike posts (as well as like counter)</li>
	</ul>	

	<a href="/users/signup">Sign up!</a>
	<br><br>

	<a href="/users/login">Log In</a>

<?php endif; ?>