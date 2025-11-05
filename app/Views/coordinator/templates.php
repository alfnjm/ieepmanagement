<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3 class="mb-4">Manage IEEP Certificate Templates</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Upload New Template</h6>
        </div>
        <div class="card-body">
            <p>Upload a PDF template. You will need to specify the X (horizontal) and Y (vertical) coordinates for the text fields in millimeters (mm). (0,0 is the top-left corner).</p>
            
            <form action="<?= base_url('coordinator/templates') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Template Name</label>
                    <input type="text" name="template_name" class="form-control" value="<?= set_value('template_name') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Template PDF File</label>
                    <input type="file" name="template_file" class="form-control" accept=".pdf" required>
                </div>
                
                <h6 class="mt-4">Text Coordinates (in mm)</h6>
                <div class="row">
                    <div class="col-md-4">
                        <label>Participant Name (X, Y)</label>
                        <div class="input-group">
                            <input type="number" name="name_x" class="form-control" placeholder="X" value="<?= set_value('name_x', 60) ?>">
                            <input type="number" name="name_y" class="form-control" placeholder="Y" value="<?= set_value('name_y', 120) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Student ID (X, Y)</label>
                        <div class="input-group">
                            <input type="number" name="student_id_x" class="form-control" placeholder="X" value="<?= set_value('student_id_x', 60) ?>">
                            <input type="number" name="student_id_y" class="form-control" placeholder="Y" value="<?= set_value('student_id_y', 130) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Event Title (X, Y)</label>
                        <div class="input-group">
                            <input type="number" name="event_x" class="form-control" placeholder="X" value="<?= set_value('event_x', 60) ?>">
                            <input type="number" name="event_y" class="form-control" placeholder="Y" value="<?= set_value('event_y', 150) ?>">
                        </div>
                    </div>
                    </div>
                
                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-upload"></i> Save Template</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-light">
            <h6 class="m-0 font-weight-bold text-primary">Uploaded IEEP Templates</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Name (X,Y)</th>
                            <th>Student ID (X,Y)</th>
                            <th>Event (X,Y)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($templates)): ?>
                            <tr><td colspan="5" class="text-center">No templates uploaded yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($templates as $t): ?>
                                <tr>
                                    <td><?= esc($t['template_name']) ?></td>
                                    <td><?= esc($t['name_x']) ?>, <?= esc($t['name_y']) ?></td>
                                    <td><?= esc($t['student_id_x'] ?? 'N/A') ?>, <?= esc($t['student_id_y'] ?? 'N/A') ?></td>
                                    <td><?= esc($t['event_x']) ?>, <?= esc($t['event_y']) ?></td>
                                    <td>
                                        <a href="<?= base_url('coordinator/preview_template/' . $t['id']) ?>" class="btn btn-sm btn-info" target="_blank" title="Preview">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <form action="<?= base_url('coordinator/delete_template/' . $t['id']) ?>" method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this template? This cannot be undone.');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>