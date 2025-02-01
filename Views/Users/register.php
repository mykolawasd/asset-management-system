<h1>Register</h1>
<form action="" method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Username">
        <?php if (isset($errors['username'])): ?>
            <?php foreach ($errors['username'] as $error): ?>
                <p><?php e($error); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Password">
        <?php if (isset($errors['password'])): ?>
            <?php foreach ($errors['password'] as $error): ?>
                <p><?php e($error); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div>
        <label for="password2">Repeat Password</label>
        <input type="password" name="password2" placeholder="Password">
        <?php if (isset($errors['password2'])): ?>
            <?php foreach ($errors['password2'] as $error): ?>
                <p><?php e($error); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div>
        <input type="submit" value="Register">
    </div>
    

</form>
