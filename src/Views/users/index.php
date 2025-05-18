<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>User List</h1>
    <a href="/users/create" class="btn btn-primary">Create New User</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= htmlspecialchars($user->name) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td><?= $user->created_at ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/users/<?= $user->id ?>" class="btn btn-info">View</a>
                                <a href="/users/<?= $user->id ?>/edit" class="btn btn-warning">Edit</a>
                                <a href="/users/<?= $user->id ?>/delete" class="btn btn-danger">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>