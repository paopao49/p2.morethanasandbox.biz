<?php if(!$posts): ?>

	Either you haven't made any posts or you aren't following anyone!
	<br><br>

	<a href='/posts/users'>Make friends!</a>
	<br><br>

	<a href='/posts/add'>Make a post!</a>

<?php else: ?>

	<?php foreach($posts as $post): ?>

		<article>

			<h1><?=$post['first_name']?> <?=$post['last_name']?> posted:</h1>

			<p><?=$post['content']?></p>

			<time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
				<?=Time::display($post['created'])?>
			</time>

			<p>
				<?php
					if($user_id == $post['post_user_id']) {
						echo "<a href='/posts/delete'>Delete Post</a>";
					}
				?>
			</p>

		</article>

	<?php endforeach; ?>

<?php endif; ?>