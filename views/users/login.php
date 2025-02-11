<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-3 fw-normal text-center">Login</h1>
                    
                    <?php if (isset($errors['login'])): ?>
                        <?php foreach ($errors['login'] as $error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php e($error); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="d-grid">
                            <input type="submit" value="Login" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
