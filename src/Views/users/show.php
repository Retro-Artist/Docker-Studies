<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>User Details</h1>
    <div>
        <a href="/users" class="btn btn-secondary">Back to List</a>
        <a href="/users/<?= $user->id ?>/edit" class="btn btn-warning">Edit</a>
        <a href="/users/<?= $user->id ?>/delete" class="btn btn-danger">Delete</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        User #<?= $user->id ?>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-3 fw-bold">Name:</div>
            <div class="col-md-9"><?= htmlspecialchars($user->name) ?></div>
        </div>
        
        <div class="row mb-2">
            <div class="col-md-3 fw-bold">Email:</div>
            <div class="col-md-9"><?= htmlspecialchars($user->email) ?></div>
        </div>
        
        <div class="row mb-2">
            <div class="col-md-3 fw-bold">Created:</div>
            <div class="col-md-9"><?= $user->created_at ?></div>
        </div>
        
        <div class="row mb-2">
            <div class="col-md-3 fw-bold">Last Updated:</div>
            <div class="col-md-9"><?= $user->updated_at ?></div>
        </div>
    </div>
</div>