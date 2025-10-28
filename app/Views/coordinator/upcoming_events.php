<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Upcoming Approved Events</h2>
    
    <ul class="list-group">
        <?php if (empty($events)): ?>
            <li class="list-group-item text-muted">There are no upcoming events at this time.</li>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><?= esc($event['title']) ?></h5>
                        <small class="text-muted">
                            📅 
                            <?php if (!empty($event['date'])): ?>
                                <?= esc(date('F d, Y', strtotime($event['date']))) ?>
                            <?php else: ?>
                                Date Not Set
                            <?php endif; ?>
                             at 📍 <?= esc($event['location']) ?>
                        </small>
                    </div>
                    <a href="<?= base_url('event/' . $event['id']) ?>" class="btn btn-sm btn-info" target="_blank">
                        View Details
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<?= $this->endSection() ?>