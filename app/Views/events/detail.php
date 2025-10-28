<?= $this->extend('layouts/headerT') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-5">
            <img src="<?= is_file(FCPATH.'uploads/posters/'.$event['thumbnail']) ? base_url('uploads/posters/'.$event['thumbnail']) : $event['thumbnail'] ?>" 
                 class="img-fluid rounded shadow-lg" 
                 alt="<?= esc($event['title']) ?>">
        </div>

        <div class="col-lg-7">
            <h1 class="display-5 fw-bold"><?= esc($event['title']) ?></h1>
            <p class="lead text-muted"><?= esc($event['location']) ?> on <?= esc(date('F d, Y', strtotime($event['date']))) ?></p>
            
            <hr class="my-4">

            <h3>Event Description</h3>
            <p><?= nl2br(esc($event['description'])) ?></p>

            <ul class="list-group list-group-flush my-4">
                <li class="list-group-item">
                    <strong>Time:</strong> <?= esc(date('h:i A', strtotime($event['time']))) ?>
                </li>
                <li class="list-group-item">
                    <strong>Semesters:</strong> <?= esc($event['eligible_semesters'] ?? 'All') ?>
                </li>
            </ul>

            <div class="d-grid gap-2">
                <?php if (session()->get('isLoggedIn')): ?>
                    <?php if ($isRegistered): ?>
                        <span class="btn btn-success btn-lg disabled">✅ Already Registered</span>
                    <?php else: ?>
                        <a href="<?= base_url('user/registerEvent/'.$event['id']) ?>" class="btn btn-primary btn-lg">
                            Register for this Event
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-primary btn-lg">
                        Login to Register
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>