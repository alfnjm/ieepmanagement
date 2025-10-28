<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Attendance List & Certificate Ready Mark</h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('organizer/attendance') ?>">
    <?= csrf_field() ?>
    
    <?php if (empty($participants)): ?>
        <div class="alert alert-info">No registrations found for your approved events.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Participant</th>
                        <th>Matric No</th>
                        <th>Certificate Ready (Attendance)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($participants as $p): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= esc($p['event_name']) ?></td>
                            <td><?= esc($p['user_name']) ?></td>
                            <td><?= esc($p['student_id'] ?? 'N/A') ?></td>
                            <td>
                                <input type="hidden" name="user_id[]" value="<?= esc($p['user_id']) ?>">
                                <input type="hidden" name="event_id[]" value="<?= esc($p['event_id']) ?>">

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           name="attendance[]" 
                                           id="att-<?= esc($p['id']) ?>" 
                                           value="<?= esc($p['user_id'] . '_' . $p['event_id']) ?>"
                                           <?= $p['certificate_ready'] == 1 ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="att-<?= esc($p['id']) ?>">Mark Attended</label>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary px-4">Save Attendance</button>
        </div>
    <?php endif; ?>
</form>

<?= $this->endSection() ?>