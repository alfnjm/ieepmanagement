<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3 class="mb-4">Pending Event Proposals</h3>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($proposals)): ?>
        <div class="alert alert-info">
            No pending proposals available at the moment.
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Eligible Semesters</th>
                            <th>Proposal</th>
                            <th>Poster</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proposals as $i => $proposal): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($proposal['event_name']) ?></td>
                                <td><?= esc($proposal['event_date']) ?></td>
                                <td><?= esc($proposal['event_location']) ?></td>
                                <td><?= esc($proposal['eligible_semesters']) ?></td>

                                <td>
                                    <?php if (!empty($proposal['proposal_file'])): ?>
                                        <a href="<?= base_url('uploads/proposals/' . $proposal['proposal_file']) ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank">View</a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (!empty($proposal['poster_image'])): ?>
                                        <a href="<?= base_url('uploads/posters/' . $proposal['poster_image']) ?>" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           target="_blank">View</a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>

                                <!-- ✅ Fixed status display -->
                                <td>
                                    <?php if ($proposal['status'] === 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif ($proposal['status'] === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($proposal['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Unknown</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($proposal['status'] === 'pending'): ?>
                                        <a href="<?= base_url('coordinator/approveProposal/' . $proposal['id']) ?>" 
                                           class="btn btn-sm btn-success"
                                           onclick="return confirm('Approve this event proposal?')">
                                            Approve
                                        </a>
                                        <a href="<?= base_url('coordinator/rejectProposal/' . $proposal['id']) ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Reject this event proposal?')">
                                            Reject
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
