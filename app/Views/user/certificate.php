<?= $this->extend('layouts/usermain') ?>
<?= $this->section('content') ?>

<div class="container my-5">
  <h2 class="mb-4">My Certificates 🎓</h2>

  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>
  
  <div class="card shadow-sm">
    <div class="card-body">
      <?php if (empty($certificates)): ?>
        <p class="alert alert-info">No certificates are ready for download yet. Attendance must be marked by the organizer.</p>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($certificates as $cert): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1"><?= esc($cert['title']) ?></h6>
                <small class="text-muted">Event Date: <?= esc($cert['date']) ?></small>
              </div>
              <a href="<?= base_url('user/printCertificate/'.$cert['event_id']) ?>" 
                 class="btn btn-success btn-sm" target="_blank">
                Download Certificate
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->include('layouts/footerT') ?>
<?= $this->endSection() ?>