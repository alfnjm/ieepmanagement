<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'IEEP Management System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modern Color Palette */
        :root {
            --color-primary: #3b82f6; /* Corporate Blue */
            --color-primary-dark: #2563eb;
            --color-text-dark: #1f2937; /* Dark Gray */
            --color-bg-light: #ffffff;
            --color-nav-bg: #374151; /* Slate Gray for Navbar */
        }
        
        body {
            background-color: #f3f4f6; /* Light background */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--color-text-dark);
        }
        
        /* ----------------------- */
        /* Navbar Styling */
        /* ----------------------- */
        .navbar {
            background-color: var(--color-nav-bg);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            color: #fff !important;
            font-weight: 700 !important;
            font-size: 1.25rem;
        }
        
        .nav-item .btn-nav {
            background-color: var(--color-primary);
            color: #fff !important;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            margin-left: 10px;
            transition: background-color 0.2s, box-shadow 0.2s;
        }
        
        .nav-item .btn-nav:hover {
            background-color: var(--color-primary-dark);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }
        
        /* Custom Dropdown for User Menu */
        .user-menu .dropdown-toggle {
            background-color: transparent !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .user-menu .dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* ----------------------- */
        /* Content & Footer */
        /* ----------------------- */
        main.content {
            flex: 1;
            padding: 30px 0;
            width: 100%;
            max-width: 1200px; /* Max width for content */
            margin: 0 auto;
        }
        
        footer {
            background-color: var(--color-bg-light);
            color: var(--color-text-dark);
            border-top: 1px solid #e5e7eb;
            font-size: 0.85rem;
            box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <?php $session = session(); ?>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="<?= session()->get('isLoggedIn') ? base_url(session()->get('role') . '/dashboard') : base_url('/') ?>">IEEP Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <?php if ($session->get('isLoggedIn')): ?>
                        <!-- Logged In User Menu -->
                        <li class="nav-item dropdown user-menu">
                            <a class="nav-link dropdown-toggle btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                👤 <?= esc($session->get('userName') ?? 'User') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
    <?php $role = $session->get('role'); ?>
    
    <li><a class="dropdown-item" href="<?= base_url($role . '/dashboard') ?>">📊 Dashboard</a></li>
    
    <?php if ($role === 'user'): ?>
        <li><a class="dropdown-item" href="<?= base_url('user/profile') ?>">⚙️ Profile</a></li>
    <?php endif; ?>
    
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">🚪 Logout</a></li>
</ul>
                        </li>
                    <?php else: ?>
                        <!-- Logged Out Links -->
                        <li class="nav-item">
                            <a class="btn btn-nav" href="<?= base_url('auth/login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-nav" href="<?= base_url('auth/register') ?>">Register</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <main class="content">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <?= $this->include('layouts/footer') ?>
