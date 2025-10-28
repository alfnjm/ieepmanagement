<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Issued Certificates Report</h3>
<p>This page lists all participants who have been marked as "Attended" and are eligible for a certificate.</p>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Participant Name</th>
            <th>Matric No</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($certificates_issued)): ?>
            <tr>
                <td colspan="5" class="text-center">No certificates have been issued for your events yet.</td>
            </tr>
        <?php else: ?>
            <?php $i = 1; ?>
            <?php foreach ($certificates_issued as $cert): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($cert['event_name']) ?></td>
                    <td><?= esc($cert['user_name']) ?></td>
                    <td><?= esc($cert['student_id'] ?? 'N/A') ?></td>
                    <td>
                        <a href="<?= base_url('organizer/view-certificate/'.$cert['reg_id']) ?>" 
                           class="btn btn-sm btn-info" target="_blank">
                            View Certificate
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
    </table>
</div>

<?= $this->endSection() ?>