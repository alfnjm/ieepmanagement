<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title><?= $title ?? 'IEEP Management System' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #3b82f6; /* Corporate Blue */
            --color-text-dark: #1f2937; /* Dark Gray */
            --color-bg-light: #f8fafc; /* Light gray page background */
        }

        body {
            background-color: var(--color-bg-light);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--color-text-dark);
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            color: var(--color-primary) !important;
            font-weight: 700 !important;
            font-size: 1.25rem;
        }

        .navbar .nav-link {
            color: #334155;
            font-weight: 500;
        }

        .navbar .nav-link.active {
            color: var(--color-primary);
            font-weight: 600;
        }

        .content-wrapper {
            flex: 1; /* Makes the content area grow to fill space */
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }
        }

        .footer {
            background-color: #ffffff;
            color: #475569;
            padding: 1rem 2rem;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('user/dashboard') ?>">IEEP Management</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'user/dashboard') ? 'active' : '' ?>" href="<?= base_url('user/dashboard') ?>">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'user/certificates') ? 'active' : '' ?>" href="<?= base_url('user/certificates') ?>">
                            <i class="bi bi-patch-check-fill"></i> My Certificates
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('user/profile') ?>">
                                <i class="bi bi-person-circle"></i> <?= esc(session()->get('name')) ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger btn-sm ms-lg-2 mt-2 mt-lg-0" href="<?= base_url('auth/logout') ?>">
                                <i class="bi bi-box-arrow-left"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('auth/login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="<?= base_url('auth/register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="content-wrapper">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="footer">
        IEEP Management System © <?= date('Y') ?>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>