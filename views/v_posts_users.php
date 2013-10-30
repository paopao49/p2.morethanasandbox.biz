<?php if($error == 'follow_error'): ?>
    <p id='error'>You cannot follow a user that you are already following or follow a user that does not exist.</p>
<?php endif; ?>

<?php foreach($users as $user): ?>

    <div class='user_row'>

        <?=$user['first_name']?> <?=$user['last_name']?>

        <!-- Display "Follow" if user isn't following listed person. Otherwise, display "Unfollow" -->
        <?php if(isset($connections[$user['user_id']])): ?>

            <a class='unfollow' href='/posts/unfollow/<?=$user['user_id']?>'>Stop Following</a>

        <?php else: ?>

            <a class='follow' href='/posts/follow/<?=$user['user_id']?>'>Follow</a>

        <?php endif; ?>

    </div>

<?php endforeach; ?>