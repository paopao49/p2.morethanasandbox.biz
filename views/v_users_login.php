<form method='POST' action='/users/p_login'>

	Log in with your account details.<br><br>

	Email<br>
	<input type='text' name='email'>
	<br><br>

	Password<br>
	<input type='password' name='password'>
	<br><br>

	<!-- When appropriate, display error messages -->
	<?php

		if($error == 'no_email') {

			echo '<div class="error">No email registered with us. Sign up today!</div><br>';

		} elseif ($error == 'no_token') {

			echo '<div class="error">Incorrect password. Please try again.</div><br>';

		}

	?>		

	<input type='submit' value='Log In'>

</form>