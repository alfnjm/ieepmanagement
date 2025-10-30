<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Event Participants (Attended)</h3>

<form method="GET" action="<?= base_url('organizer/participants') ?>" class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <label for="event_id" class="form-label">Select Event:</label>
            <select name="event_id" id="event_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Choose an Event --</option>
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <option value="<?= $event['id'] ?>" <?= ($selected_event == $event['id']) ? 'selected' : '' ?>>
                            <?= esc($event['title']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</form>

<?php if ($selected_event): ?>
    <hr>
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
                        <td colspan="6" class="text-center">No participants have attended this event yet.</td>
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
                            <td><?= esc(date('M d, Y', strtotime($p['event_date']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>