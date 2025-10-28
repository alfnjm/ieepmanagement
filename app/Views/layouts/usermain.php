
  <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'IEEP Management System' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
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
    .content {
      flex: 1;
      padding: 30px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand text-white fw-bold" href="<?= base_url('/') ?>">IEEP Management System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="btn btn-nav" href="<?= base_url('auth/logout') ?>">Logout</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-nav" href="<?= base_url('user/profile') ?>">Profile</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</body>
</html>

</body>
</html>
