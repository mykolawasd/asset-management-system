<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-3 fw-normal text-center">Register</h1>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            <?php if (isset($errors['username'])): ?>
                                <?php foreach ($errors['username'] as $error): ?>
                                    <div class="invalid-feedback d-block">
                                        <?php e($error); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <?php if (isset($errors['password'])): ?>
                                <?php foreach ($errors['password'] as $error): ?>
                                    <div class="invalid-feedback d-block">
                                        <?php e($error); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password2" class="form-label">Repeat Password</label>
                            <input type="password" name="password2" id="password2" class="form-control" placeholder="Repeat Password">
                            <?php if (isset($errors['password2'])): ?>
                                <?php foreach ($errors['password2'] as $error): ?>
                                    <div class="invalid-feedback d-block">
                                        <?php e($error); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid">
                            <input type="submit" class="btn btn-primary" value="Register">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
