<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Program Organizer Dashboard</h2>

<div class="row text-center mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>My Approved Events</h6>
                <p class="fs-3 text-primary"><?= esc($stats['my_events'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Proposals Submitted</h6>
                <p class="fs-3 text-warning"><?= esc($stats['proposals_submitted'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Total Participants (Attended)</h6>
                <p class="fs-3 text-success"><?= esc($stats['total_participants'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Certificates Issued</h6>
                <p class="fs-3 text-info"><?= esc($stats['certificates_issued'] ?? 0) ?></p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>