<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2 class="mb-4">Event Registration Control</h2>
  <p>Enable/Disable event registration for participants.</p>

  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="regToggle" checked>
    <label class="form-check-label" for="regToggle">Registration Enabled</label>
  </div>
</div>

<?= $this->endSection() ?>
