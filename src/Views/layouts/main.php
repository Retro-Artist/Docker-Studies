<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Clean MVC CRUD' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .header {
            padding-bottom: 1rem;
            border-bottom: .05rem solid #e5e5e5;
            margin-bottom: 2rem;
        }
        .footer {
            padding-top: 1.5rem;
            color: #777;
            border-top: .05rem solid #e5e5e5;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header d-flex justify-content-between align-items-center">
            <h3 class="float-left">Clean MVC CRUD</h3>
            <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                <a class="me-3 py-2 text-dark text-decoration-none" href="/">Home</a>
                <a class="me-3 py-2 text-dark text-decoration-none" href="/users">Users</a>
            </nav>
        </header>

        <main>
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['flash_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <?= $content ?>
        </main>

        <footer class="footer">
            <p>&copy; <?= date('Y') ?> Clean MVC CRUD</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>