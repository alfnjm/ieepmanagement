<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Organizer Dashboard' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fffdf9;
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      height: 100vh;
      width: 240px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #6d4c41; /* brown theme */
      color: #fff;
      padding-top: 1rem;
    }
    .sidebar h4 {
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
      color: #fff;
    }
    .sidebar a {
      color: #f0eae3;
      display: block;
      padding: 10px 20px;
      text-decoration: none;
      transition: 0.2s;
    }
    .sidebar a.active,
    .sidebar a:hover {
      background-color: #8d6e63;
      color: #fff;
    }
    .content {
      margin-left: 240px;
      padding: 2rem;
    }
    footer {
      background-color: #6d4c41;
      color: white;
      padding: 0.8rem;
      text-align: center;
      position: fixed;
      bottom: 0;
      left: 240px;
      width: calc(100% - 240px);
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Organizer Panel</h4>
    <a href="<?= base_url('organizer/dashboard') ?>" class="<?= (uri_string() == 'organizer/dashboard') ? 'active' : '' ?>">📊 Dashboard</a>
    <a href="<?= base_url('organizer/create-event') ?>" class="<?= (uri_string() == 'organizer/create_event') ? 'active' : '' ?>">🎯 Create Event</a>
    <a href="<?= base_url('organizer/my-proposals') ?>" class="<?= (uri_string() == 'organizer/my_proposals') ? 'active' : '' ?>">📑 My Proposals</a>
    <a href="<?= base_url('organizer/participants') ?>" class="<?= (uri_string() == 'organizer/participants') ? 'active' : '' ?>">👥 Participants</a>
    <a href="<?= base_url('organizer/certificates') ?>" class="<?= (uri_string() == 'organizer/certificates') ? 'active' : '' ?>">🎓 Certificates</a>
    <a href="<?= base_url('organizer/attendance') ?>" class="<?= (uri_string() == 'organizer/attendance') ? 'active' : '' ?>">📋 Attendance</a>
    <hr class="text-light">
    <a href="<?= base_url('/') ?>">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <?= $this->renderSection('content') ?>
  </div>

  <!-- Footer -->
  <footer>
    IEEP Organizer Panel © <?= date('Y') ?>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
