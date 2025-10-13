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
            <input type="text" name="name" class="form-control" placeholder="Nama" value="<?= old('name') ?>" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
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

        <button class="btn btn-primary w-100">Register</button>
    </form>

    <a href="<?= base_url('auth/login') ?>" class="small-link">Already have an account? Login</a>
</div>

<?= $this->endSection() ?>
<?= $this->include('layouts/footer') ?>