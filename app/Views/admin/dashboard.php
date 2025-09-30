<?= $this->extend('layouts/main') ?>  
<?= $this->section('content') ?>

<div class="container py-4">
  <h2 class="mb-4">Admin Dashboard</h2>

  <!-- Statistik -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>Students</h5>
          <p class="fs-4 fw-bold">120</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>Admins</h5>
          <p class="fs-4 fw-bold">5</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>IEEP Coordinators</h5>
          <p class="fs-4 fw-bold">3</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>Organizers</h5>
          <p class="fs-4 fw-bold">8</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Add User Button -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>All Users</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add User</button>
  </div>

  <!-- User Table -->
  <table class="table table-bordered bg-white shadow-sm">
    <thead class="table-light">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Ali Ahmad</td>
        <td>ali@example.com</td>
        <td>Student</td>
        <td><button class="btn btn-sm btn-danger">Delete</button></td>
      </tr>
      <tr>
        <td>Siti Aminah</td>
        <td>siti@example.com</td>
        <td>Event Organizer</td>
        <td><button class="btn btn-sm btn-danger">Delete</button></td>
      </tr>
    </tbody>
  </table>

  <!-- Ongoing Events -->
  <h4 class="mt-4">Ongoing Events</h4>
  <ul class="list-group shadow-sm">
    <li class="list-group-item">Innovation Expo 2025 - 12/10/2025</li>
    <li class="list-group-item">Career Fair - 20/10/2025</li>
  </ul>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" placeholder="Enter name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="Enter email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" placeholder="Enter password" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" required>
              <option value="">-- Select Role --</option>
              <option value="student">Student</option>
              <option value="admin">Admin</option>
              <option value="ieep coordinator">IEEP Coordinator</option>
              <option value="event organizer">Event Organizer</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
