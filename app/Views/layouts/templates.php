<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2><?= $title ?? 'Manage IEEP Templates' ?></h2>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>

    <!-- Upload Form -->
    <!-- FIXED: Form action points to coordinator/templates -->
    <form action="<?= base_url('coordinator/templates') ?>" method="post" enctype="multipart/form-data" class="mb-4">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="template_name">Template Name</label>
            <input type="text" name="template_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="template_file">Template PDF File</label>
            <input type="file" name="template_file" class="form-control-file" required>
        </div>
        <p>Set coordinates for text (in mm from top-left corner):</p>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="name_x">Participant Name (X)</label>
                    <input type="number" name="name_x" class="form-control" value="60" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="name_y">Participant Name (Y)</label>
                    <input type="number" name="name_y" class="form-control" value="120" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="event_x">Event Title (X)</label>
                    <input type="number" name="event_x" class="form-control" value="60" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="event_y">Event Title (Y)</label>
                    <input type="number" name="event_y" class="form-control" value="140" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Upload Template</button>
    </form>

    <hr>

    <!-- Existing Templates -->
    <h4>Your Uploaded Templates</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Preview (PDF)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($templates)): ?>
                <?php foreach ($templates as $template): ?>
                    <tr>
                        <td><?= esc($template['template_name']) ?></td>
                        <td>
                            <a href="<?= base_url($template['template_path']) ?>" target="_blank">View Template</a>
                        </td>
                        <td>
                            <!-- Add delete functionality if needed -->
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No templates uploaded yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?= $this->endSection() ?>
