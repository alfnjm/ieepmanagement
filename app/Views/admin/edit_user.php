<?= $this->extend('layouts/main') ?>  
<?= $this->section('content') ?>

<div class="container py-4">
  <h2 class="mb-4">Edit User</h2>

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

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="<?= base_url('admin/edit/' . $user['id']) ?>" method="POST">
        <?= csrf_field() ?>
        
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
              <input type="text" name="name" class="form-control" value="<?= old('name', $user['name']) ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= old('email', $user['email']) ?>" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
              <small class="form-text text-muted">Only enter if you want to change the password</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select name="role" class="form-select" id="roleSelect" required onchange="toggleStudentFields()">
                <option value="">-- Select Role --</option>
                <option value="user" <?= (old('role', $user['role']) == 'user') ? 'selected' : '' ?>>Student</option>
                <option value="admin" <?= (old('role', $user['role']) == 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="coordinator" <?= (old('role', $user['role']) == 'coordinator') ? 'selected' : '' ?>>IEEP Coordinator</option>
                <option value="organizer" <?= (old('role', $user['role']) == 'organizer') ? 'selected' : '' ?>>Event Organizer</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Student-specific fields -->
        <div id="studentFields" style="<?= ($user['role'] === 'user') ? 'display: block;' : 'display: none;' ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Class</label>
                <input type="text" name="class" class="form-control" value="<?= old('class', $user['class']) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Matric Number</label>
                <input type="text" name="student_id" class="form-control" value="<?= old('student_id', $user['student_id']) ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= old('phone', $user['phone']) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">IC Number</label>
                <input type="text" name="ic_number" class="form-control" value="<?= old('ic_number', $user['ic_number']) ?>">
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-success">Update User</button>
          <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Cancel</a>
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