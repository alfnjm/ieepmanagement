<?= $this->extend('layouts/usermain') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('user/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>
    
    <p>This page lists all your certificates from events you have attended and that have been published by the coordinator.</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($certificates)): ?>
                            <tr>
                                <td colspan="3" class="text-center">You do not have any certificates available to download yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; ?>
                            <?php foreach ($certificates as $cert): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= esc($cert['event_name']) ?></td>
                                    <td>
                                        <a href="<?= base_url($cert['certificate_path']) ?>" 
                                           class="btn btn-sm btn-success" 
                                           target="_blank">
                                            <i class="fas fa-download"></i> Download
                                        </a>
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