<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .navbar .dropdown-item {
            display: flex;
            align-items: center;
        }
        
        .navbar .dropdown-item i {
            margin-right: 0.75rem;
            width: 20px;
        }

        main.content {
            flex: 1; /* Makes the content area grow */
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
    </style>
</head>
<body>
    <?php $session = session(); ?>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= session()->get('isLoggedIn') ? base_url(session()->get('role') . '/dashboard') : base_url('/') ?>">
                IEEP Management
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($session->get('isLoggedIn')): ?>
                        <?php $role = $session->get('role'); ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> <?= esc($session->get('name') ?? 'User') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?= base_url($role . '/dashboard') ?>">
                                        <i class="bi bi-grid-fill"></i> Dashboard
                                    </a>
                                </li>
                                <?php if ($role === 'user'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('user/profile') ?>">
                                        <i class="bi bi-person-fill"></i> My Profile
                                    </a>
                                </li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
                                        <i class="bi bi-box-arrow-left"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-secondary btn-sm me-2" href="<?= base_url('auth/login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="<?= base_url('auth/register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="content">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layouts/footerT') ?>