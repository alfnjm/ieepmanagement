<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $title ?? 'Auth' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      height: 100vh;
      background: #f5f2ef; /* soft beige */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .auth-wrapper {
      display: flex;
      height: 100vh;
      width: 100%;
    }
    .auth-left {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f5f2ef; /* sama dengan body */
      padding: 2rem;
    }
    .auth-left img {
      max-width: 75%;
      max-height: 450px;
      object-fit: contain;
    }
    .auth-divider {
      width: 1px;
      background: #d8cfc7; /* soft brown line */
    }
    .auth-right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #faf9f7;
      padding: 2rem;
    }
    .auth-card {
      width: 100%;
      max-width: 400px;
    }
    .auth-card h3 {
      font-weight: 600;
      margin-bottom: 1.5rem;
      text-align: center;
      color: #5d4037;
    }
    .form-control {
      border-radius: 6px;
      border: 1px solid #cfc2b9;
      margin-bottom: 1rem;
    }
    .btn-primary {
      background-color: #8d6e63;
      border: none;
      font-weight: 600;
    }
    .btn-primary:hover {
      background-color: #6d4c41;
    }
    .btn-outline-secondary {
      border-color: #a1887f;
      color: #5d4037;
    }
    .btn-outline-secondary:hover {
      background-color: #d7ccc8;
      color: #3e2723;
    }
    .small-link {
      display: block;
      margin-top: 1rem;
      font-size: 0.9rem;
      color: #6d4c41;
      text-decoration: none;
      text-align: center;
    }
    .small-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <!-- Left side (gambar clean) -->
<div class="auth-left d-none d-md-flex position-relative">

  <!-- Back Arrow Button -->
  <a href="<?= base_url('/') ?>" 
     class="btn btn-link position-absolute top-0 start-0 m-3 text-decoration-none fw-bold" 
     style="color:#5d4037; font-size: 1.2rem;">
    ← Back
  </a>

  <div class="auth-wrapper">
    <!-- Left side (gambar clean) -->
    <div class="auth-left d-none d-md-flex">
      <img src="<?= base_url('images/cert.png') ?>" alt="Preview">
    </div>

    <!-- Divider -->
    <div class="auth-divider d-none d-md-block"></div>

    <!-- Right side (form) -->
    <div class="auth-right">
      <div class="auth-card">
        <?= $this->renderSection('content') ?>
      </div>
    </div>
  </div>
</body>
</html>
