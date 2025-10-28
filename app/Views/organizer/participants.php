<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Event Registrations</h3>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Event Name</th>
            <th>Participant Name</th>
            <th>Matric No</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($participants)): ?>
            <tr>
                <td colspan="5" class="text-center">No participants have registered for your events yet.</td>
            </tr>
        <?php else: ?>
            <?php $i = 1; ?>
            <?php foreach ($participants as $p): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($p['event_name']) ?></td>
                    <td><?= esc($p['user_name']) ?></td>
                    <td><?= esc($p['student_id'] ?? 'N/A') ?></td>
                    <td><?= esc($p['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
    </table>
</div>

<?= $this->endSection() ?>