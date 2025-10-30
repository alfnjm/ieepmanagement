<?= $this->extend('layouts/adminmain') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Ongoing & Upcoming Events</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <ul class="list-group">
            <?php if (empty($events)): ?>
                <li class="list-group-item">There are no ongoing or upcoming events.</li>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><?= esc($event['title']) ?></h5>
                            <small>Location: <?= esc($event['location'] ?? 'N/A') ?></small>
                        </div>
                        <span class="badge bg-primary rounded-pill">
                            <?= esc(date('M d, Y', strtotime($event['date']))) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>