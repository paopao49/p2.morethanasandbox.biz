<!-- Error message given 'like_error' parameter -->
<?php if($error == 'like_error'): ?>

    <p id='error'>You can't like a post that you already like or like a post that doesn't exist.</p>

<?php endif; ?>

<!-- Display following message if user has no posts to show -->
<?php if(!$posts): ?>

	<p>Either you haven't made any posts or you aren't following anyone!</p>
	<br><br>

	<a class='button' href='/posts/users'>Make friends!</a>

	<a class='button' href='/posts/add'>Make a post!</a>

<?php else: ?>

	<!-- Display each $post in $posts array -->
	<?php foreach($posts as $post): ?>

		<article class='post_row'>

			<!-- Display "like" if a post has one like, otherwise show "likes" for plural -->
			<?php if($post['like_count'] == 1): ?>

				<span class='like_display'>1 like</span>

			<?php else: ?>

				<span class='like_display'><?=$post['like_count']?> likes</span>

			<?php endif; ?>

			<!-- like/unlike button -->
			<?php if(!$post['like_id']): ?>

				<span class='like_button'><a href='/posts/like/<?=$post['post_id']?>'>Like</a></span>

			<?php else: ?>

				<span class='like_button'><a href='/posts/unlike/<?=$post['post_id']?>'>Unlike</a></span>

			<?php endif; ?>

			<!-- Text of actual post -->
			<p class='post_content'><?=$post['content']?></p>

			<!-- Name of post author -->
			<p class='name_display'><?=$post['first_name']?> <?=$post['last_name']?></p>

			<!-- Publication time of post -->
			<p class='time'>
				<?=Time::display($post['created'])?>
			</p>

			<!-- Delete post feature. User can only delete own posts. -->
			<?php if($user_id == $post['post_user_id']): ?>

				<a class='delete_button' href='/posts/delete/<?=$post['post_id']?>'>Delete Post</a>

			<?php endif; ?>			

			<!-- Blank div for float clearing purposes -->
			<div class='clear_block'></div>

		</article>

	<?php endforeach; ?>

<?php endif; ?>