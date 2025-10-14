<?= $this->include('layouts/headerT') ?>

<div class="container my-5">
  <h1 class="mb-4">Upcoming Events</h1>
  <div class="row">
    <?php foreach($events as $event): ?>
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title"><?= esc($event['title']) ?></h5>
            <p class="card-text"><?= esc($event['description']) ?></p>
            <small class="text-muted"><?= esc($event['date']) ?></small><br>

            <?php if(!session()->get('student_id')): ?>
              <!-- kalau tak login, redirect ke login -->
<<<<<<< HEAD
              <a href="<?= base_url('auth/register') ?>" class="btn btn-primary mt-2">Register</a>
=======
              <a href="<?= base_url('auth/login') ?>" class="btn btn-primary mt-2">Register</a>
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
            <?php else: ?>
              <!-- kalau dah login, redirect ke event register -->
              <a href="<?= base_url('events/register/'.$event['id']) ?>" class="btn btn-success mt-2">Register</a>
            <?php endif; ?>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?= $this->include('layouts/footerT') ?>
