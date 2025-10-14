<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'IEEP Management System' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fffdf9;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .navbar {
      background-color: #6d4c41;
    }
    .navbar a {
      color: #fff !important;
    }
    .btn-nav {
      background-color: #8d6e63; /* soft brown */
      color: #fff !important;
      border: none;
      margin-left: 10px;
      transition: 0.3s;
    }
    .btn-nav:hover {
      background-color: #5d4037; /* darker on hover */
    }
    main.content {
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
            <a class="btn btn-nav" href="<?= base_url('auth/login') ?>">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-nav" href="<?= base_url('profile') ?>">Profile</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="content">
    <?= $this->renderSection('content') ?>
  </main>

  <footer class="text-center py-3" style="background-color:#6d4c41; color:#fff;">
    <small>IEEP Management System © <?= date('Y') ?></small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
