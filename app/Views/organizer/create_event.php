  <?= $this->extend('layouts/organizermain') ?>
  <?= $this->section('content') ?>

  <div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-light">
        <h4 class="fw-bold text-secondary mb-0">Create New Event Proposal</h4>
      </div>
      <div class="card-body bg-white">

        <?php if (isset($validation)): ?>
          <div class="alert alert-danger" role="alert">
            <?= $validation->listErrors() ?>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('organizer/submitProposal') ?>" enctype="multipart/form-data">
          <?= csrf_field() ?>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Event Name</label>
              <input type="text" name="title" class="form-control" required value="<?= set_value('title') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Date</label>
              <input type="date" name="date" class="form-control" required value="<?= set_value('date') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Time</label>
              <input type="time" name="time" class="form-control" required value="<?= set_value('time') ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Location / Venue</label>
            <input type="text" name="event_location" class="form-control" required value="<?= set_value('event_location') ?>">
          </div>

          <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Program Start (optional)</label>
            <input type="datetime-local" name="program_start" class="form-control" value="<?= set_value('program_start') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Program End (optional)</label>
            <input type="datetime-local" name="program_end" class="form-control" value="<?= set_value('program_end') ?>">
          </div>
        </div>

          <!-- Multi-Day Section -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Program Days & Times</label>
            <p class="text-muted small mb-2">Add multiple days if your event runs on different days with different start times.</p>

            <div id="multi-day-container">
              <div class="day-entry mb-2 border p-3 rounded bg-light">
                <div class="row g-2 align-items-center">
                  <div class="col-md-5">
                    <input type="date" name="event_days[0][date]" class="form-control" required>
                  </div>
                  <div class="col-md-5">
                    <input type="time" name="event_days[0][time]" class="form-control" required>
                  </div>
                  <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-day">Remove</button>
                  </div>
                </div>
              </div>
            </div>

            <button type="button" id="add-day" class="btn btn-outline-secondary btn-sm mt-2">
              + Add Another Day
            </button>
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
            <input type="file" name="poster" class="form-control" accept="image/*" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Event Description</label>
            <textarea name="description" class="form-control" rows="4" required><?= set_value('description') ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Upload Proposal Document</label>
            <input type="file" name="proposal" class="form-control" accept=".pdf" required>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">Submit Proposal</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- JS for Adding & Removing Multi-Day Fields -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('add-day');
    const container = document.getElementById('multi-day-container');

    addBtn.addEventListener('click', function() {
      const index = container.querySelectorAll('.day-entry').length;
      const entry = document.createElement('div');
      entry.classList.add('day-entry', 'mb-2', 'border', 'p-3', 'rounded', 'bg-light');
      entry.innerHTML = `
        <div class="row g-2 align-items-center">
          <div class="col-md-5">
            <input type="date" name="event_days[${index}][date]" class="form-control" required>
          </div>
          <div class="col-md-5">
            <input type="time" name="event_days[${index}][time]" class="form-control" required>
          </div>
          <div class="col-md-2 text-end">
            <button type="button" class="btn btn-danger btn-sm remove-day">Remove</button>
          </div>
        </div>
      `;
      container.appendChild(entry);
    });

    container.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-day')) {
        e.target.closest('.day-entry').remove();
      }
    });
  });
  </script>

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
