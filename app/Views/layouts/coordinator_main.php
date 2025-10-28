<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'IEEP Coordinator Panel' ?></title>
    <!-- Use Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modern Color Palette */
        :root {
            --color-sidebar-bg: #1e293b; /* Dark Slate Blue/Gray */
            --color-sidebar-text: #e2e8f0; /* Light text for dark background */
            --color-sidebar-active: #334155; /* Slightly lighter shade for hover/active */
            --color-accent: #3b82f6; /* Bright Blue for emphasis */
            --color-bg-main: #f8fafc; /* Very Light Gray/White for content area */
            --color-shadow: rgba(0, 0, 0, 0.1);
            --color-text-dark: #334155; /* Slate Gray for footer text */
            --color-border: #e2e8f0; /* Light border for contrast */
            --sidebar-width: 250px;
        }

        body {
            background-color: var(--color-bg-main);
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            margin: 0;
            padding-left: var(--sidebar-width); /* Push content over */
        }

        /* ----------------------- */
        /* Sidebar Styling */
        /* ----------------------- */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: var(--color-sidebar-bg);
            color: var(--color-sidebar-text);
            padding: 20px 0;
            z-index: 1000;
            box-shadow: 2px 0 10px var(--color-shadow);
        }
        
        .sidebar-header {
            padding: 0 20px 20px 20px;
            text-align: center;
        }

        .sidebar h4 {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .sidebar a {
            color: var(--color-sidebar-text);
            display: flex;
            align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }
        
        .sidebar a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background-color: var(--color-sidebar-active);
            color: #fff;
            border-left: 4px solid var(--color-accent);
            padding-left: 16px;
        }
        
        .sidebar hr {
            margin: 1rem 0;
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* ----------------------- */
        /* Main Content & Footer */
        /* ----------------------- */
        .content {
            padding: 2rem 3rem;
            min-height: calc(100vh - 50px);
        }
        
        /* Fixed Footer */
        footer {
            background-color: #ffffff;
            color: var(--color-text-dark);
            padding: 0.8rem 2rem;
            text-align: left;
            position: fixed;
            bottom: 0;
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            border-top: 1px solid var(--color-border);
            box-shadow: 0 -2px 5px var(--color-shadow);
            z-index: 999;
            font-size: 0.9rem;
        }
        
        /* Icon styles */
        .icon-dashboard::before { content: "📊"; }
        .icon-proposals::before { content: "📝"; }
        .icon-registration::before { content: "⚙️"; }
        .icon-upcoming::before { content: "📅"; }
        .icon-approvals::before { content: "✅"; }
        .icon-logout::before { content: "🚪"; }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Coordinator Panel</h4>
            <small class="text-secondary">IEEP Coordination</small>
        </div>
        
        <!-- Navigation Links -->
        <a href="<?= base_url('coordinator/dashboard') ?>" class="<?= (uri_string() == 'coordinator/dashboard') ? 'active' : '' ?>">
            <i class="icon-dashboard"></i> Dashboard
        </a>
        <a href="<?= base_url('coordinator/proposals') ?>" class="<?= (uri_string() == 'coordinator/proposals') ? 'active' : '' ?>">
            <i class="icon-proposals"></i> Program Proposals
        </a>
        <a href="<?= base_url('coordinator/registration') ?>" class="<?= (uri_string() == 'coordinator/registration') ? 'active' : '' ?>">
            <i class="icon-registration"></i> Registration Control
        </a>
        <a href="<?= base_url('coordinator/upcoming') ?>" class="<?= (uri_string() == 'coordinator/upcoming') ? 'active' : '' ?>">
            <i class="icon-upcoming"></i> Upcoming Events
        </a>
        <a href="<?= base_url('coordinator/approvals') ?>" class="<?= (uri_string() == 'coordinator/approvals') ? 'active' : '' ?>">
            <i class="icon-approvals"></i> Organizer Approvals
        </a>
        <hr>
        <a href="<?= base_url('auth/logout') ?>" class="text-danger">
            <i class="icon-logout"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Footer -->
    <footer>
        IEEP Coordinator Panel © <?= date('Y') ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
