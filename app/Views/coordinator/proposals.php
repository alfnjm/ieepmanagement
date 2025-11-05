<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3 class="fw-bold mb-4">Pending Event Proposals</h3>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
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
          <?php if (!empty($pendingProposals)): ?>
            <?php foreach ($pendingProposals as $index => $proposal): ?>
              <?php $status = strtolower($proposal['status']); ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= esc($proposal['event_name']) ?></td>
                <td><?= esc($proposal['event_date']) ?></td>
                <td><?= esc($proposal['event_location']) ?></td>
                <td><?= esc($proposal['eligible_semesters']) ?></td>
                <td>
                  <?php if (!empty($proposal['proposal_file'])): ?>
                    <a href="<?= base_url('uploads/proposals/'.$proposal['proposal_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                      View
                    </a>
                  <?php else: ?>
                    <span class="text-muted">N/A</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($proposal['poster_image'])): ?>
                    <a href="<?= base_url('uploads/posters/'.$proposal['poster_image']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                      Poster
                    </a>
                  <?php else: ?>
                    <span class="text-muted">N/A</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($status === 'pending'): ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                  <?php elseif ($status === 'approved'): ?>
                    <span class="badge bg-success">Approved</span>
                  <?php else: ?>
                    <span class="badge bg-danger">Rejected</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($status === 'pending'): ?>
                    <a href="<?= base_url('coordinator/approveProposal/'.$proposal['id']) ?>" class="btn btn-sm btn-success">Approve</a>
                    <a href="<?= base_url('coordinator/rejectProposal/'.$proposal['id']) ?>" class="btn btn-sm btn-danger">Reject</a>
                  <?php else: ?>
                    <em>N/A</em>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="9" class="text-center text-muted">No pending proposals at the moment.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
