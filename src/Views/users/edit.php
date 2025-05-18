<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit User</h1>
    <a href="/users" class="btn btn-secondary">Back to List</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="/users/<?= $user->id ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user->name) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (leave empty to keep current)</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Only fill this if you want to change the password</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>