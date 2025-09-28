<?= $this->include('layouts/headerT') ?>

<div class="container my-5">
  <h2 class="mb-4">Welcome, <?= session()->get('name'); ?>!</h2>

  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <h3>Available Events</h3>
  <div class="row">
    <?php foreach($events as $event): ?>
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title"><?= esc($event['title']) ?></h5>
            <p class="card-text"><?= esc($event['description']) ?></p>
            <small class="text-muted"><?= esc($event['date']) ?> @ <?= esc($event['location']) ?></small><br>

            <?php if(isset($registeredEvents[$event['id']])): ?>
              <span class="badge bg-success mt-2">Registered</span><br>
              <?php if($registeredEvents[$event['id']]['certificate_ready']): ?>
                <a href="<?= base_url('user/printCertificate/'.$event['id']) ?>" class="btn btn-warning btn-sm mt-2">Print Certificate</a>
              <?php else: ?>
                <span class="badge bg-secondary mt-2">Certificate Pending</span>
              <?php endif; ?>
            <?php else: ?>
              <a href="<?= base_url('user/registerEvent/'.$event['id']) ?>" class="btn btn-primary mt-2">Register</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?= $this->include('layouts/footerT') ?>
