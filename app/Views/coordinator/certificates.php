<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3 class="mb-4"><?= $title ?></h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('info')): ?>
        <div class="alert alert-info"><?= session()->getFlashdata('info') ?></div>
    <?php endif; ?>

    <?php if (empty($templates)): ?>
        <div class="alert alert-warning">
            You must <a href="<?= site_url('coordinator/templates') ?>">upload a certificate template</a> before you can publish any certificates.
        </div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="eventsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Event Name</th>
                                <th>Event Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($events)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">No approved events found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td><?= esc($event['title']) ?></td>
                                        <td><?= esc($event['date']) ?></td>
                                        
                                        <td>
                                            <form action="<?= site_url('coordinator/publish_certificates') ?>" method="post" class="d-flex">
                                                <?= csrf_field() ?>
                                                
                                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

                                                <select name="template_id" class="form-select form-select-sm me-2" required>
                                                    <option value="">Select Template...</option>
                                                    <?php foreach ($templates as $template): ?>
                                                        <option value="<?= $template['id'] ?>">
                                                            <?= esc($template['template_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                
                                                <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                                                    Publish
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
    <?php endif; // End of check for templates ?>
</div>

<?= $this->endSection() ?>