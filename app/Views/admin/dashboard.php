<?= $this->extend('layouts/main') ?>  
<?= $this->section('content') ?>

<div class="container py-4">
  <h2 class="mb-4">Admin Dashboard</h2>

  <!-- Display Messages -->
  <?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Statistik -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>Students</h5>
          <p class="fs-4 fw-bold"><?= $userCounts['user'] ?? 0 ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>Admins</h5>
          <p class="fs-4 fw-bold"><?= $userCounts['admin'] ?? 0 ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>Coordinators</h5>
          <p class="fs-4 fw-bold"><?= $userCounts['coordinator'] ?? 0 ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h5>Organizers</h5>
          <p class="fs-4 fw-bold"><?= $userCounts['organizer'] ?? 0 ?></p>
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
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= esc($user['name']) ?></td>
        <td><?= esc($user['email']) ?></td>
        <td>
          <span class="badge 
            <?= $user['role'] == 'admin' ? 'bg-danger' : '' ?>
            <?= $user['role'] == 'coordinator' ? 'bg-warning' : '' ?>
            <?= $user['role'] == 'organizer' ? 'bg-info' : '' ?>
            <?= $user['role'] == 'user' ? 'bg-success' : '' ?>
          ">
            <?= ucfirst($user['role']) ?>
          </span>
        </td>
        <td>
          <a href="<?= base_url('admin/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning me-1">Edit</a>
          <form action="<?= base_url('admin/delete/' . $user['id']) ?>" method="POST" class="d-inline">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-sm btn-danger" 
                    onclick="return confirm('Are you sure you want to delete this user?')">
              Delete
            </button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
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
  <div class="modal-dialog modal-lg"> <!-- Changed to modal-lg for more space -->
    <div class="modal-content">
      <form action="<?= base_url('admin/create') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Display Validation Errors -->
          <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                  <li><?= $error ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter name" value="<?= old('name') ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" value="<?= old('email') ?>" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" id="roleSelect" required onchange="toggleStudentFields()">
                  <option value="">-- Select Role --</option>
                  <option value="user" <?= old('role') == 'user' ? 'selected' : '' ?>>Student</option>
                  <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                  <option value="coordinator" <?= old('role') == 'coordinator' ? 'selected' : '' ?>>IEEP Coordinator</option>
                  <option value="organizer" <?= old('role') == 'organizer' ? 'selected' : '' ?>>Event Organizer</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Student-specific fields (initially hidden) -->
          <div id="studentFields" style="display: none;">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Class</label>
                  <input type="text" name="class" class="form-control" placeholder="Enter class" value="<?= old('class') ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Matric Number</label>
                  <input type="text" name="student_id" class="form-control" placeholder="Enter matric number" value="<?= old('student_id') ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Phone Number</label>
                  <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="<?= old('phone') ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">IC Number</label>
                  <input type="text" name="ic_number" class="form-control" placeholder="Enter IC number" value="<?= old('ic_number') ?>">
                </div>
              </div>
            </div>
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

<script>
function toggleStudentFields() {
    const roleSelect = document.getElementById('roleSelect');
    const studentFields = document.getElementById('studentFields');
    
    if (roleSelect.value === 'user') {
        studentFields.style.display = 'block';
        // Make fields required
        document.querySelectorAll('#studentFields input').forEach(input => {
            input.required = true;
        });
    } else {
        studentFields.style.display = 'none';
        // Remove required attribute
        document.querySelectorAll('#studentFields input').forEach(input => {
            input.required = false;
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleStudentFields();
});
</script>

<?= $this->endSection() ?>