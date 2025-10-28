<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4">
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
      <h4 class="fw-bold text-secondary mb-0">Create New Event</h4>
    </div>
    <div class="card-body bg-white">

        <form method="post" action="<?= base_url('organizer/submitProposal') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Event Name</label>
        <input type="text" name="event_name" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Date</label>
        <input type="date" name="event_date" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Time</label>
        <input type="time" name="event_time" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Location / Venue</label>
      <input type="text" name="event_location" class="form-control" required>
    </div>

    <!-- Replaced Eligible Age with Program Start/End -->
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Program Start</label>
        <input type="datetime-local" name="program_start" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Program End</label>
        <input type="datetime-local" name="program_end" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Eligible Semesters</label>
      <div class="d-flex flex-wrap gap-3">
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="eligible_semesters[]" value="<?= $i ?>" id="sem<?= $i ?>">
            <label class="form-check-label" for="sem<?= $i ?>">Semester <?= $i ?></label>
          </div>
        <?php endfor; ?>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Event Poster</label>
      <input type="file" name="poster_image" class="form-control" accept="image/*" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Event Description</label>
      <textarea name="event_description" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Upload Proposal Document</label>
      <input type="file" name="proposal_file" class="form-control" accept=".pdf,.doc,.docx" required>
    </div>

    <div class="text-end">
      <button type="submit" class="btn btn-primary px-4">Submit Event</button>
    </div>
  </form>
</div>


<style>
  body {
    background-color: #f8f9fa !important;
  }
  .card {
    border-radius: 10px;
  }
  .form-label {
    color: #444;
  }
  .btn-primary {
    background-color: #0d6efd;
    border: none;
  }
  .btn-primary:hover {
    background-color: #084298;
  }
</style>

<?= $this->endSection() ?>
