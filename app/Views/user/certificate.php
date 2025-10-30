<?= $this->extend('layouts/usermain') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <h2><?= $title ?? 'My Certificates' ?></h2>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (!empty($registrations)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $reg): ?>
                    <tr>
                        <td><?= esc($reg['event_title']) ?></td>
                        <td><?= date('M d, Y', strtotime($reg['event_date'])) ?></td>
                        <td>
                            <!-- This route MUST exist -->
                            <a href="<?= base_url('user/downloadCertificate/' . $reg['event_id']) ?>" class="btn btn-primary btn-sm" target="_blank">
                                Download Certificate
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no certificates available for download at this time.</p>
        <p>Certificates are made available after you have attended an event and the event coordinator has published them.</p>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>
