<?= $this->extend('layouts/header') ?>
<?= $this->section('content') ?>

<div class="auth-card">
  <h3>Forgot Password</h3>

  <?php if(session()->getFlashdata('success')):?>
    <div class="alert alert-success"><?=session()->getFlashdata('success')?></div>
  <?php endif;?>
  <?php if(session()->getFlashdata('error')):?>
    <div class="alert alert-danger"><?=session()->getFlashdata('error')?></div>
  <?php endif;?>

  <form method="post" action="<?=base_url('auth/forgot')?>">
    <?= csrf_field() ?>
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Enter your email" value="<?=set_value('email')?>">
    </div>
    <button class="btn btn-primary w-100">Send Reset Link</button>
  </form>

  <a href="<?=base_url('auth/login')?>" class="small-link">Back to Login</a>
</div>

<?= $this->endSection() ?>
<?= $this->include('layouts/footer') ?>
