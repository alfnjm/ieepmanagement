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

    <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Nama" value="<?= old('name') ?>">
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>">
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
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
        
        <!-- Role Selection Field -->
        <div class="mb-3">
            <label for="role" class="form-label">Select Role</label>
            <select name="role" id="role" class="form-control">
                <option value="user" <?= old('role') == 'user' ? 'selected' : '' ?>>User</option>
                <option value="organizer" <?= old('role') == 'organizer' ? 'selected' : '' ?>>Program Organizer</option>
                <option value="coordinator" <?= old('role') == 'coordinator' ? 'selected' : '' ?>>IEEP Coordinator</option>
            </select>
            <small class="form-text text-muted">Choose your role in the system</small>
        </div>

        <button class="btn btn-primary w-100">Register</button>
    </form>

    <a href="<?= base_url('auth/login') ?>" class="small-link">Already have an account? Login</a>
</div>

<?= $this->endSection() ?>
<?= $this->include('layouts/footer') ?>