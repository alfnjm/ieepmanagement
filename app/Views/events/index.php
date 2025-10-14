<?= $this->include('layouts/headerT') ?>

<div class="container my-5">
    <h1 class="mb-4">Upcoming Events</h1>
    <div class="row">
        <?php foreach($events as $event): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <?php if($event['thumbnail']): ?>
                        <img src="<?= base_url('uploads/'.$event['thumbnail']) ?>" class="card-img-top" alt="Thumbnail">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($event['title']) ?></h5>
                        <p class="card-text"><?= esc($event['description']) ?></p>
                        <small class="text-muted"><?= esc($event['date']) ?> @ <?= esc($event['location']) ?></small><br>

                        <?php if(isset($registrations[$event['id']])): ?>
                            <a href="<?= base_url('events/cancel/'.$event['id']) ?>" class="btn btn-danger mt-2">Cancel</a>
                        <?php else: ?>
                            <a href="<?= base_url('events/register/'.$event['id']) ?>" class="btn btn-primary mt-2">Register</a>
                        <?php endif; ?>

                        <a href="<?= base_url('events/detail/'.$event['id']) ?>" class="btn btn-outline-secondary mt-2">Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->include('layouts/footerT') ?>
