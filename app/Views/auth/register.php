<?= $this->extend('layouts/header') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <h3>Register</h3>

    <?php if(session()->getFlashdata('success')):?>
        <div class="alert alert-success"><?=session()->getFlashdata('success')?></div>
    <?php endif;?>

    <?php if(session()->getFlashdata('error')):?>
        <div class="alert alert-danger"><?=session()->getFlashdata('error')?></div>
    <?php endif;?>

    <?php if(isset($validation)):?>
        <div class="alert alert-danger"><?=$validation->listErrors()?></div>
    <?php endif;?>

<<<<<<< HEAD
    <form method="post" id="registrationForm">
        <?= csrf_field() ?>
        
=======
    <form method="post">
        <?= csrf_field() ?>
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Nama" value="<?= old('name') ?>" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
<<<<<<< HEAD

        <div class="mb-3">
            <label for="role" class="form-label">Select Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="" disabled selected>-- Select a Role --</option> 
                <option value="user" <?= old('role') == 'user' ? 'selected' : '' ?>>Student</option>
                <option value="organizer" <?= old('role') == 'organizer' ? 'selected' : '' ?>>Program Organizer</option>
            </select>
            <small class="form-text text-muted">Choose your role in the system</small>
        </div>
        <div class="student-fields" style="display:none;">
            <div class="mb-3">
                <input type="text" name="class" class="form-control" placeholder="Class" value="<?= old('class') ?>">
            </div>
            <div class="mb-3">
                <input type="text" name="student_id" class="form-control" placeholder="Matric Number" value="<?= old('student_id') ?>">
            </div>
            <div class="mb-3">
                <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="<?= old('phone') ?>">
            </div>
            <div class="mb-3">
                <input type="text" name="ic_number" class="form-control" placeholder="IC Number" value="<?= old('ic_number') ?>">
            </div>
        </div>

        <div class="organizer-fields" style="display:none;">
            <div class="mb-3">
                <input type="text" name="staff_id" class="form-control" placeholder="Staff ID" value="<?= old('staff_id') ?>">
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="terms" class="form-check-input" id="terms" value="1" <?= old('terms') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="terms">
                I agree to the <a href="#" target="_blank">Terms & Services</a>
            </label>
            <?php if(isset($validation) && $validation->hasError('terms')): ?>
                <div class="invalid-feedback d-block">
                    <?= $validation->getError('terms') ?>
                </div>
            <?php endif; ?>
        </div>
=======
        <div class="mb-3">
            <input type="text" name="class" class="form-control" placeholder="Class" value="<?= old('class') ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" name="student_id" class="form-control" placeholder="Matric Number" value="<?= old('student_id') ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="<?= old('phone') ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" name="ic_number" class="form-control" placeholder="IC Number" value="<?= old('ic_number') ?>" required>
        </div>
        
        <!-- Hidden role field set to 'user' by default -->
        <input type="hidden" name="role" value="user">
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140

        <button class="btn btn-primary w-100">Register</button>
    </form>

    <a href="<?= base_url('auth/login') ?>" class="small-link">Already have an account? Login</a>
</div>

<<<<<<< HEAD
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const studentFields = document.querySelector('.student-fields');
    const organizerFields = document.querySelector('.organizer-fields');

    // Function to show/hide fields
    function updateFormFields(selectedRole) {
        // Reset visibility and required attributes
        studentFields.style.display = 'none';
        organizerFields.style.display = 'none';
        
        // Remove 'required' from all hidden fields to allow form submission
        studentFields.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
        organizerFields.querySelectorAll('input').forEach(input => input.removeAttribute('required'));

        if (selectedRole === 'user') {
            studentFields.style.display = 'block';
            // Set 'required' for Student fields
            studentFields.querySelectorAll('input').forEach(input => input.setAttribute('required', 'required'));
        } else if (selectedRole === 'organizer') {
            organizerFields.style.display = 'block';
            // Set 'required' for Organizer fields (Staff ID)
            organizerFields.querySelectorAll('input').forEach(input => input.setAttribute('required', 'required'));
        }
    }

    // Call on page load to handle 'old' values if validation failed
    updateFormFields(roleSelect.value);

    // Call on role change
    roleSelect.addEventListener('change', function() {
        updateFormFields(this.value);
    });
});
</script>

=======
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
<?= $this->endSection() ?>
<?= $this->include('layouts/footer') ?>