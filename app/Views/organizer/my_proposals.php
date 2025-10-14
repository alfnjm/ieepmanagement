<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">My Submitted Proposals</h2>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <table class="table table-hover table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Event Name</th>
          <th>Date Submitted</th>
          <th>Status</th>
          <th>Proposal File</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($myProposals)): ?>
          <?php foreach ($myProposals as $index => $proposal): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= esc($proposal['event_name']) ?></td>
              <td><?= date('d M Y', strtotime($proposal['created_at'])) ?></td>
              <td>
                <?php if ($proposal['status'] === 'Approved'): ?>
                  <span class="badge bg-success">Approved</span>
                <?php elseif ($proposal['status'] === 'Rejected'): ?>
                  <span class="badge bg-danger">Rejected</span>
                <?php else: ?>
                  <span class="badge bg-warning text-dark">Pending</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($proposal['proposal_file'])): ?>
                  <a href="<?= base_url('uploads/proposals/' . $proposal['proposal_file']) ?>" 
                     class="btn btn-outline-primary btn-sm" target="_blank">
                    View File
                  </a>
                <?php else: ?>
                  <span class="text-muted">No file</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No proposals submitted yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
