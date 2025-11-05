<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h4 class="m-0 fw-bold text-primary">My Submitted Proposals</h4>
        <a href="<?= site_url('organizer/create-event') ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> New Proposal
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Program Days</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th>Proposal File</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($proposals)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No proposals submitted yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; ?>
                        <?php foreach ($proposals as $proposal): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <strong><?= esc($proposal['event_name']) ?></strong><br>
                                    <small class="text-muted"><?= esc($proposal['event_location'] ?? '—') ?></small>
                                </td>

                                <!-- 🟢 Show multi-day schedule -->
                                <td>
                                    <?php if (!empty($proposal['event_days'])): ?>
                                        <?php $days = json_decode($proposal['event_days'], true); ?>
                                        <?php if (is_array($days)): ?>
                                            <ul class="mb-0 ps-3">
                                                <?php foreach ($days as $day): ?>
                                                    <li>
                                                        <?= date('M d, Y', strtotime($day['date'])) ?>
                                                        <?= !empty($day['time']) ? ' at ' . date('g:i A', strtotime($day['time'])) : '' ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= esc(date('M d, Y', strtotime($proposal['created_at']))) ?></td>

                                <td>
                                    <?php 
                                        $status = strtolower($proposal['status']); 
                                        $badgeClass = match ($status) {
                                            'approved' => 'success',
                                            'pending'  => 'warning text-dark',
                                            'rejected' => 'danger',
                                            default    => 'secondary'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (!empty($proposal['proposal_file']) && $proposal['proposal_file'] !== 'N/A'): ?>
                                        <a href="<?= base_url('uploads/proposals/' . $proposal['proposal_file']) ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info">
                                           <i class="fas fa-file-pdf"></i> View
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No file</span>
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
