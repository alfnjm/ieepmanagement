<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<section class="content">
    <div class="container-fluid">
        
        <h1 class="mt-4"><?= $title ?></h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= site_url('organizer/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active"><?= $title ?></li>
        </ol>

        <div class="mb-3">
            <form method="GET" action="<?= site_url('organizer/participants') ?>" id="eventSelectForm">
                <label for="event-select" class="form-label">Select Event</label>
                <select name="event_id" id="event-select" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select an Event --</option>
                    <?php foreach ($events as $event): ?>
                        
                        <option value="<?= $event['id'] ?>" <?= ($event['id'] == $selected_event_id) ? 'selected' : '' ?>>
                            <?= esc($event['title']) ?> (<?= esc($event['date']) ?>)
                        </option>

                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if (isset($selectedEventInfo) && !empty($selectedEventInfo)): ?>
            
            <h3 class="mb-3">
                Viewing Participants for: <strong><?= esc($selectedEventInfo['title']) ?></strong>
            </h3>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>


            <form action="<?= site_url('organizer/participants/save') ?>" method="post">
                
                <?= csrf_field() ?>

                <input type="hidden" name="event_id" value="<?= $selectedEventInfo['id'] ?>">

                <div class="card">
                    <div class="card-body">
                        <table id="participants-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>Email</th>
                                    <th>Attended</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($participants)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No participants registered for this event.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($participants as $participant): ?>
                                        <tr>
                                            <td><?= esc($participant['name']) ?></td>
                                            <td><?= esc($participant['student_id']) ?></td>
                                            <td><?= esc($participant['email']) ?></td>
                                            <td>
                                                <input type="hidden" 
                                                       name="attendance[<?= $participant['user_id'] ?>]" 
                                                       value="0">
                                                
                                                <input type="checkbox" 
                                                       class="form-check-input"
                                                       name="attendance[<?= $participant['user_id'] ?>]" 
                                                       value="1" 
                                                       <?= $participant['is_attended'] ? 'checked' : '' ?>>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if (!empty($participants)): ?>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save"></i> Save Attendance
                    </button>
                <?php endif; ?>
            
            </form>
            <?php elseif ($selected_event_id): ?>
            <div class="alert alert-danger">
                Event not found or you do not have permission to view it.
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Please select an event from the dropdown above to view and manage participants.
            </div>
        <?php endif; ?>

    </div>
</section>

<?= $this->endSection() ?>