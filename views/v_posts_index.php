<?php if($error == 'like_error'): ?>
    <p>You can't like a post that you already like or like a post that doesn't exist.</p>
<?php endif; ?>

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
			<br><br>

			<!-- Display "like" if a post as one like, otherwise show "likes" for plural -->
			<?php if($post['like_count'] == 1): ?>
				<p>1 like</p>
			<?php else: ?>
				<p>
					<?=$post['like_count']?> likes
				</p>
			<?php endif; ?>

			<?php if(!$post['like_id']): ?>
				<a href='/posts/like/<?=$post['post_id']?>'>Like</a>
			<?php else: ?>
				<a href='/posts/unlike/<?=$post['post_id']?>'>Unlike</a>
			<?php endif; ?>
			<br><br>

			<!-- Delete post feature. User can only delete own posts. -->
			<?php if($user_id == $post['post_user_id']): ?>
				<a href='/posts/delete/<?=$post['post_id']?>'>Delete Post</a>
			<?php endif; ?>

		</article>

	<?php endforeach; ?>

<?php endif; ?>