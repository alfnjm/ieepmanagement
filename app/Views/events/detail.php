<?= $this->include('layouts/headerT') ?>

<div class="container my-5">
    <h2><?= esc($event['title']) ?></h2>
    <?php if($event['thumbnail']): ?>
        <img src="<?= base_url('uploads/'.$event['thumbnail']) ?>" class="img-fluid mb-3" alt="Thumbnail">
    <?php endif; ?>
    <p><?= esc($event['description']) ?></p>
    <p><strong>Date:</strong> <?= esc($event['date']) ?></p>
    <p><strong>Location:</strong> <?= esc($event['location']) ?></p>
    <a href="<?= base_url('events/register/'.$event['id']) ?>" class="btn btn-primary">Register</a>
</div>

<?= $this->include('layouts/footerT') ?>
