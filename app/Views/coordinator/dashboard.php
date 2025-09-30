<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2 class="mb-4">IEEP Coordinator Dashboard</h2>

  <!-- Card Statistik -->
  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title">Ongoing Events</h5>
          <p class="fs-3 text-success">3</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title">Upcoming Events</h5>
          <p class="fs-3 text-info">2</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title">Organizer Events</h5>
          <p class="fs-3 text-primary">5</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title">Attendance</h5>
          <p class="fs-3 text-warning">120</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Proposal Table -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Program Proposals (Organizer)
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Program Name</th>
            <th>Organizer</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Community Clean Up</td>
            <td>Organizer A</td>
            <td>10 Oct 2025</td>
            <td><span class="badge bg-warning">Pending</span></td>
            <td>
              <button class="btn btn-sm btn-success">Approve</button>
              <button class="btn btn-sm btn-danger">Reject</button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Tech Workshop</td>
            <td>Organizer B</td>
            <td>15 Oct 2025</td>
            <td><span class="badge bg-success">Approved</span></td>
            <td>
              <button class="btn btn-sm btn-secondary">Approved</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Event Control -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Event Registration Control
    </div>
    <div class="card-body">
      <p>Enable/Disable event registration for participants.</p>
      <!-- Toggle Slider -->
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="regToggle" checked>
        <label class="form-check-label" for="regToggle">Registration Enabled</label>
      </div>
    </div>
  </div>

  <!-- Upcoming Events -->
  <div class="card shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Upcoming Events (IEEP Coordinator View Only)
    </div>
    <div class="card-body">
      <ul class="list-group">
        <li class="list-group-item">Leadership Seminar - 20 Oct 2025</li>
        <li class="list-group-item">Innovation Hackathon - 5 Nov 2025</li>
      </ul>
    </div>
  </div>

</div>

<?= $this->endSection() ?>
