<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Program Organizer Dashboard</h2>

<div class="row text-center mb-4">
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>My Events</h6><p class="fs-3 text-primary">3</p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Proposals Submitted</h6><p class="fs-3 text-warning">2</p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Participants</h6><p class="fs-3 text-success">120</p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Certificates Issued</h6><p class="fs-3 text-info">15</p></div></div></div>
</div>

<?= $this->endSection() ?>
