<?= $this->extend('layouts/header') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <h3>Login</h3>

    <?php if(session()->getFlashdata('success')):?>
        <div class="alert alert-success"><?=session()->getFlashdata('success')?></div>
    <?php endif;?>

    <?php if(session()->getFlashdata('error')):?>
        <div class="alert alert-danger"><?=session()->getFlashdata('error')?></div>
    <?php endif;?>

    <form method="post" action="<?= base_url('auth/login') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <button class="btn btn-primary w-100">Login</button>
    </form>

    <a href="<?= base_url('auth/register') ?>" class="small-link">Don't have an account? Register</a>
</div>

<?= $this->endSection() ?>
<?= $this->include('layouts/footer') ?>
