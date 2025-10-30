<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Mark Attendance</h3>

<form method="GET" action="<?= base_url('organizer/attendance') ?>" class="mb-4">
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

<?php if ($selected_event && !empty($participants)): ?>
    <hr>
    
    <form method="POST" action="<?= base_url('organizer/attendance') ?>">
        <?= csrf_field() ?>
        
        <input type="hidden" name="event_id" value="<?= $selected_event ?>">
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Attended</th>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $p): ?>
                        <tr>
                            <td class="text-center">
                                <input class="form-check-input" type="checkbox" 
                                       name="participants[]" 
                                       value="<?= esc($p['user_id']) ?>" 
                                       <?= ($p['is_attended'] == 1) ? 'checked' : '' ?>>
                            </td>
                            <td><?= esc($p['name']) ?></td>
                            <td><?= esc($p['student_id'] ?? 'N/A') ?></td>
                            <td><?= esc($p['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Save Attendance</button>
        </div>
    </form>
    
<?php elseif ($selected_event): ?>
    <div class="alert alert-info">No participants have registered for this event yet.</div>
<?php endif; ?>

<?= $this->endSection() ?>