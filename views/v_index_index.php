<!--
<p>
	Hello World! You have successfully spawned a new application.
</p>

<p>
	This message is being triggered via the c_index.php controller, within the index() method.
</p>

<p>
	<strong>Since everything is in working order, you should now delete <?php echo APP_PATH?>diagnostics.php</strong>
</p>
-->

<div>

	<?php
		if($user) {
			echo '<h1>Hi '.$user->first_name.', welcome to Chatster!</h1>';
		} else {
			echo '<h1>Welcome to Chatster!</h1>';
		}
	?>
	<p>Chat, chat, chat away :)</p>

</div>