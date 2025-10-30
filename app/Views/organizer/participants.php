<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Event Participants (Attended)</h3>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Participant Name</th>
                <th>Matric No</th>
                <th>Email</th>
                <th>Event Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($participants)): ?>
                <tr>
                    <td colspan="6" class="text-center">No participants have attended your events yet.</td>
                </tr>
            <?php else: ?>
                <?php $i = 1; ?>
                <?php foreach ($participants as $p): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= esc($p['event_title']) ?></td>
                        <td><?= esc($p['participant_name']) ?></td>
                        <td><?= esc($p['student_id'] ?? 'N/A') ?></td>
                        <td><?= esc($p['email']) ?></td>
                        <td><?= esc($p['event_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
