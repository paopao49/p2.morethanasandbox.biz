<form method='POST' action='/users/p_signup'>

	<?php if($error=='error'): ?>
		<p id='error'>All fields required. Please try again.<p>
		<br>
		
	<?php elseif($error=='duplicate_email'): ?>
		<p id='error'>Email already exists in system.</p>
		<br>
		
	<?php endif; ?>

	Welcome to Chatster!<br><br>
	Fill in your details below to get started (all fields required).
	<br><br><br>

	First Name<br>
	<input type='text' name='first_name'>
	<br><br>

	Last Name<br>
	<input type='text' name='last_name'>
	<br><br>

	Email<br>
	<input type='text' name='email'>
	<br><br>

	Password<br>
	<input type='password' name='password'>
	<br><br>

	<input type='submit' value='Sign Up'>

</form>