<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2 class="mb-4">Coordinator Dashboard Overview</h2>

  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5>Ongoing Events</h5>
          <p class="fs-3 text-success"><?= $stats['ongoing'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5>Upcoming</h5>
          <p class="fs-3 text-info"><?= $stats['upcoming'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5>Organizer Events</h5>
          <p class="fs-3 text-primary"><?= $stats['organizer'] ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5>Attendance</h5>
          <p class="fs-3 text-warning"><?= $stats['attendance'] ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
