<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Delete User</h1>
    <a href="/users" class="btn btn-secondary">Back to List</a>
</div>

<div class="card">
    <div class="card-header bg-danger text-white">
        Confirm Delete
    </div>
    <div class="card-body">
        <h5 class="card-title">Are you sure you want to delete this user?</h5>
        <p class="card-text">This action cannot be undone!</p>
        
        <div class="mb-4">
            <div><strong>ID:</strong> <?= $user->id ?></div>
            <div><strong>Name:</strong> <?= htmlspecialchars($user->name) ?></div>
            <div><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></div>
        </div>
        
        <form action="/users/<?= $user->id ?>/delete" method="post">
            <button type="submit" class="btn btn-danger">Delete User</button>
            <a href="/users" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>