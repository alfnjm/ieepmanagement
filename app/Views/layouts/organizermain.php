<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Organizer Dashboard' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --color-sidebar-bg: #1e293b; /* Dark Slate Blue/Gray */
            --color-sidebar-text: #e2e8f0; /* Light text for dark background */
            --color-sidebar-hover: #334155; /* Lighter shade for hover */
            --color-sidebar-active: #3b82f6; /* Bright Blue for active */
            --color-bg-main: #f8fafc; /* Very Light Gray/White for content */
            --sidebar-width: 260px;
        }

        body {
            background-color: var(--color-bg-main);
        }

        /* ----------------------- */
        /* Sidebar Styling */
        /* ----------------------- */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: var(--color-sidebar-bg);
            color: var(--color-sidebar-text);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem;
            text-align: center;
        }

        .sidebar-header h4 {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-header small {
            color: #94a3b8; /* Lighter text */
        }

        .sidebar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .sidebar-nav .nav-link {
            color: var(--color-sidebar-text);
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.2s, color 0.2s;
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 1rem;
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-nav .nav-link:hover {
            background-color: var(--color-sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background-color: var(--color-sidebar-active);
            color: #fff;
            font-weight: 600;
        }
        
        .sidebar-nav .nav-divider {
            margin: 1rem 1.5rem;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav .nav-link.logout {
            color: #f87171; /* Red color for logout */
        }
        .sidebar-nav .nav-link.logout:hover {
            background-color: var(--color-sidebar-hover);
            color: #ef4444; /* Brighter red on hover */
        }


        /* ----------------------- */
        /* Main Content & Header */
        /* ----------------------- */
        .main-content {
            padding-left: var(--sidebar-width);
            transition: padding-left 0.3s ease;
        }
        
        .content-wrapper {
            padding: 2rem;
            min-height: calc(100vh - 60px); /* Full height minus footer */
            margin-bottom: 60px; /* Space for footer */
        }
        
        /* Mobile Toggle Button */
        .sidebar-toggle {
            display: none; /* Hidden on desktop */
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background: var(--color-sidebar-bg);
            color: white;
            border: none;
            font-size: 1.5rem;
        }

        /* Footer */
        .footer {
            background-color: #ffffff;
            color: #475569;
            padding: 1rem 2rem;
            text-align: left;
            position: fixed;
            bottom: 0;
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
            z-index: 999;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        /* ----------------------- */
        /* Responsive (Mobile) */
        /* ----------------------- */
        @media (max-width: 768px) {
            .sidebar {
                left: -100%; /* Hide sidebar off-screen */
            }
            
            .sidebar.active {
                left: 0; /* Show sidebar */
            }

            .main-content, .footer {
                padding-left: 0; /* Full width content */
                left: 0;
                width: 100%;
            }
            
            .sidebar-toggle {
                display: block; /* Show toggle button */
            }
            
            /* Overlay for when sidebar is open */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.4);
                z-index: 999;
                display: none;
            }
            
            .sidebar.active + .sidebar-overlay {
                display: block;
            }
        }

    </style>
</head>
<body>

    <button class="btn sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>IEEP Organizer</h4>
            <small>Management Panel</small>
        </div>
        
        <ul class="sidebar-nav">
            <li>
                <a href="<?= base_url('organizer/dashboard') ?>" class="nav-link <?= (uri_string() == 'organizer/dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('organizer/create-event') ?>" class="nav-link <?= (uri_string() == 'organizer/create-event') ? 'active' : '' ?>">
                    <i class="bi bi-plus-square-fill"></i> Create Event
                </a>
            </li>
            <li>
                <a href="<?= base_url('organizer/my-proposals') ?>" class="nav-link <?= (uri_string() == 'organizer/my-proposals') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-text-fill"></i> My Proposals
                </a>
            </li>
            <li>
                <a href="<?= base_url('organizer/participants') ?>" class="nav-link <?= (uri_string() == 'organizer/participants') ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Participants
                </a>
            </li>
            <li>
                <a href="<?= base_url('organizer/certificates') ?>" class="nav-link <?= (uri_string() == 'organizer/certificates') ? 'active' : '' ?>">
                    <i class="bi bi-patch-check-fill"></i> Certificates
                </a>
            </li>
            <li>
                <a href="<?= base_url('organizer/templates') ?>" class="nav-link <?= (uri_string() == 'organizer/templates') ? 'active' : '' ?>">
                    <i class="bi bi-clipboard-check-fill"></i> Create Certificates
                </a>
            </li>
            <li>
                <a href="<?= base_url('organizer/attendance') ?>" class="nav-link <?= (uri_string() == 'organizer/attendance') ? 'active' : '' ?>">
                    <i class="bi bi-clipboard-check-fill"></i> Attendance
                </a>
            </li>
            
            <hr class="nav-divider">
            
            <li>
                <a href="<?= base_url('auth/logout') ?>" class="nav-link logout">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content" id="mainContent">
        <div class="content-wrapper">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <footer class="footer">
        IEEP Organizer Panel © <?= date('Y') ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
        
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
        });
    </script>
</body>
</html>