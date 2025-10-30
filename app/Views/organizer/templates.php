<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">My Certificate Templates</h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Upload New Template</h6>
    </div>
    <div class="card-body">
        <p>Upload a PDF template. You will need to specify the X (horizontal) and Y (vertical) coordinates for the text fields. (0,0 is the top-left corner).</p>
        
        <form method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Template Name</label>
                <input type="text" name="template_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Template PDF File</label>
                <input type="file" name="template_file" class="form-control" accept=".pdf" required>
            </div>
            
            <h6 class="mt-4">Text Coordinates</h6>
            <div class="row">
                <div class="col-md-4">
                    <label>Participant Name (X, Y)</label>
                    <div class="input-group">
                        <input type="number" name="name_x" class="form-control" placeholder="X" value="60">
                        <input type="number" name="name_y" class="form-control" placeholder="Y" value="120">
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Event Title (X, Y)</label>
                    <div class="input-group">
                        <input type="number" name="event_x" class="form-control" placeholder="X" value="60">
                        <input type="number" name="event_y" class="form-control" placeholder="Y" value="150">
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Event Date (X, Y)</label>
                    <div class="input-group">
                        <input type="number" name="date_x" class="form-control" placeholder="X" value="60">
                        <input type="number" name="date_y" class="form-control" placeholder="Y" value="160">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary mt-3">Save Template</button>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">My Uploaded Templates</h6>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Name (X,Y)</th>
                    <th>Event (X,Y)</th>
                    <th>Date (X,Y)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($templates)): ?>
                    <tr><td colspan="4" class="text-center">No templates uploaded yet.</td></tr>
                <?php else: ?>
                    <?php foreach($templates as $t): ?>
                        <tr>
                            <td><?= esc($t['template_name']) ?></td>
                            <td><?= esc($t['name_x']) ?>, <?= esc($t['name_y']) ?></td>
                            <td><?= esc($t['event_x']) ?>, <?= esc($t['event_y']) ?></td>
                            <td><?= esc($t['date_x']) ?>, <?= esc($t['date_y']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>