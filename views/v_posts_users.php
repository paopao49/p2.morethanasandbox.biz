<?php if($error == 'follow_error'): ?>
    <p>You can't follow a user that you're already following or follow a user that doesn't exist.</p>
<?php endif; ?>

<?php foreach($users as $user): ?>

    <?=$user['first_name']?> <?=$user['last_name']?>
    <br>

    <?php if(isset($connections[$user['user_id']])): ?>
        <a href='/posts/unfollow/<?=$user['user_id']?>'>Unfollow</a>
    <?php else: ?>
        <a href='/posts/follow/<?=$user['user_id']?>'>Follow</a>
    <?php endif; ?>

    <br><br>

<?php endforeach; ?>