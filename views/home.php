<?php
    use SmyPhp\Core\Application;
?>
<h1>Home</h1>
<p>Welcome <?php echo $name ?></p>
<?php if(Application::$app->isGuest()): ?>
<a href="/register">Register</a>
<a href="/login">Login</a>
<?php else: ?>
<h4>Welcome <?php echo Application::$app->user->getDisplayName() ?></h4>
<a href="/profile">My Profile</a>
<a href="/logout">Logout</a>
<?php endif; ?>