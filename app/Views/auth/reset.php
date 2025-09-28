<?= $this->extend('layouts/header') ?>
<?= $this->section('content') ?>

<div class="auth-card">
  <h3>Reset Password</h3>

  <?php if(session()->getFlashdata('success')):?>
    <div class="alert alert-success"><?=session()->getFlashdata('success')?></div>
  <?php endif;?>
  <?php if(session()->getFlashdata('error')):?>
    <div class="alert alert-danger"><?=session()->getFlashdata('error')?></div>
  <?php endif;?>

  <form method="post" action="<?=base_url('auth/reset/'.$token)?>">
    <?= csrf_field() ?>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="New Password">
    </div>
    <div class="mb-3">
      <input type="password" name="password_confirm" class="form-control" placeholder="Confirm Password">
    </div>
    <button class="btn btn-primary w-100">Reset Password</button>
  </form>

  <a href="<?=base_url('auth/login')?>" class="small-link">Back to Login</a>
</div>

<?= $this->endSection() ?>
<?= $this->include('layouts/footer') ?>
