<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 fw-bold text-primary">My Submitted Proposals</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th>Proposal File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($proposals)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No proposals submitted yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; ?>
                        <?php foreach ($proposals as $proposal): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($proposal['event_name']) ?></td>
                                <td><?= esc(date('M d, Y', strtotime($proposal['created_at']))) ?></td>
                                <td>
                                    <?php if (strtolower($proposal['status']) == 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif (strtolower($proposal['status']) == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif (strtolower($proposal['status']) == 'rejected'): ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= esc($proposal['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($proposal['proposal_file'] && $proposal['proposal_file'] !== 'N/A'): ?>
                                        <a href="<?= base_url('uploads/proposals/' . $proposal['proposal_file']) ?>" target="_blank" class="btn btn-sm btn-info">View File</a>
                                    <?php else: ?>
                                        No file
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>