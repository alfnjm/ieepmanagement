<<<<<<< HEAD
<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Program Organizer Dashboard</h2>

<div class="row text-center mb-4">
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>My Events</h6><p class="fs-3 text-primary">3</p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Proposals Submitted</h6><p class="fs-3 text-warning">2</p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Participants</h6><p class="fs-3 text-success">120</p></div></div></div>
  <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><h6>Certificates Issued</h6><p class="fs-3 text-info">15</p></div></div></div>
=======
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2 class="mb-4">Program Organizer Dashboard</h2>

  <!-- Statistik Ringkas -->
  <div class="row text-center mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h6 class="card-title">My Events</h6>
          <p class="fs-3 text-primary">3</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h6 class="card-title">Proposals Submitted</h6>
          <p class="fs-3 text-warning">2</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h6 class="card-title">Participants</h6>
          <p class="fs-3 text-success">120</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h6 class="card-title">Certificates Issued</h6>
          <p class="fs-3 text-info">15</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Event -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Create New Event
    </div>
    <div class="card-body">
      <form>
        <div class="mb-3">
          <label class="form-label">Event Name</label>
          <input type="text" class="form-control" placeholder="Enter event name">
        </div>
        <div class="mb-3">
          <label class="form-label">Date</label>
          <input type="date" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" rows="3" placeholder="Describe your event"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Thumbnail Image</label>
          <input type="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create Event</button>
      </form>
    </div>
  </div>

  <!-- Submit Proposal -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Submit Proposal for Approval
    </div>
    <div class="card-body">
      <form>
        <div class="mb-3">
          <label class="form-label">Proposal Title</label>
          <input type="text" class="form-control" placeholder="Enter proposal title">
        </div>
        <div class="mb-3">
          <label class="form-label">Details</label>
          <textarea class="form-control" rows="3" placeholder="Proposal details"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Upload Proposal Document</label>
          <input type="file" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Submit Proposal</button>
      </form>
    </div>
  </div>

  <!-- Proposal Status -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      My Proposals
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Proposal Title</th>
            <th>Date Submitted</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Community Clean Up</td>
            <td>28 Sept 2025</td>
            <td><span class="badge bg-warning">Pending</span></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Tech Workshop</td>
            <td>15 Sept 2025</td>
            <td><span class="badge bg-success">Approved</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Registered Participants -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Event Registrations (Approved Events Only)
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Participant</th>
            <th>Matric No</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Tech Workshop</td>
            <td>Aiman Hakim</td>
            <td>DDT5A-001</td>
            <td>aiman@example.com</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Tech Workshop</td>
            <td>Siti Aminah</td>
            <td>DDT5A-002</td>
            <td>siti@example.com</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Certificates -->
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Certificates
    </div>
    <div class="card-body">
      <ul class="list-group">
        <li class="list-group-item">
          Community Clean Up - <a href="#">Download Certificate</a>
        </li>
        <li class="list-group-item">
          Tech Workshop - <a href="#">Download Certificate</a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Attendance -->
  <div class="card shadow-sm border-0">
    <div class="card-header bg-secondary text-white">
      Attendance List
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Participant</th>
            <th>Matric No</th>
            <th>Event</th>
            <th>Attendance</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Aiman Hakim</td>
            <td>DDT5A-001</td>
            <td>Tech Workshop</td>
            <td><input type="checkbox" class="form-check-input" checked></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Siti Aminah</td>
            <td>DDT5A-002</td>
            <td>Tech Workshop</td>
            <td><input type="checkbox" class="form-check-input"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
</div>

<?= $this->endSection() ?>
