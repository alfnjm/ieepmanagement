<!-- app/Views/admin/_modal_add_user.php -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addUserLabel">Add New User</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?= form_open('admin/createUser') ?>

        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" id="roleSelect" class="form-select" required>
            <option value="">-- Select Role --</option>
            <option value="user">Student</option>
            <option value="coordinator">IEEP Coordinator</option>
            <option value="organizer">Program Organizer</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div id="studentFields" style="display:none;">
          <div class="mb-3">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Class</label>
            <input type="text" name="class" class="form-control">
          </div>
        </div>

        <div id="staffFields" style="display:none;">
          <div class="mb-3">
            <label class="form-label">Staff ID</label>
            <input type="text" name="staff_id" class="form-control">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">IC Number</label>
          <input type="text" name="ic_number" class="form-control">
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Create</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>

        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>

<script>
  // Toggle field visibility based on role
  document.getElementById('roleSelect').addEventListener('change', function() {
    let role = this.value;
    document.getElementById('studentFields').style.display = role === 'user' ? 'block' : 'none';
    document.getElementById('staffFields').style.display = (role === 'coordinator' || role === 'organizer') ? 'block' : 'none';
  });
</script>
