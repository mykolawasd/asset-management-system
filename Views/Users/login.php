<h1>Login</h1>
<?php if (isset($errors['login'])): ?>
    <?php foreach ($errors['login'] as $error): ?>
        <p><?php e($error); ?></p>
    <?php endforeach; ?>
<?php endif; ?>
<form action="" method="post">
    <div>
        <label for="username">Username</label>

        <input type="text" name="username" placeholder="Username">
    </div>
    <div>
        <label for="password">Password</label>

        <input type="password" name="password" placeholder="Password">
    </div>
    <div>
        <input type="submit" value="Login">
    </div>
</form>
