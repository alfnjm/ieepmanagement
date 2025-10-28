<?= $this->extend('layouts/adminmain') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Admin Dashboard</h2>

<div class="row text-center mb-4">
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Students</h6><p class="fs-3 text-success"><?= $userCounts['user'] ?? 0 ?></p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Admins</h6><p class="fs-3 text-danger"><?= $userCounts['admin'] ?? 0 ?></p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Coordinators</h6><p class="fs-3 text-warning"><?= $userCounts['coordinator'] ?? 0 ?></p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Organizers</h6><p class="fs-3 text-info"><?= $userCounts['organizer'] ?? 0 ?></p></div></div></div>
</div>

<?= $this->endSection() ?>
