<?= $this->extend('layouts/coordinator_main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3 class="mb-4">Pending Organizer Account Approvals</h3>

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

  <!-- Pending Organizers Table -->
  <?php if (empty($pendingOrganizers)): ?>
    <div class="alert alert-info">
      No pending organizer registrations at the moment.
    </div>
  <?php else: ?>
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Staff ID</th>
              <th>Registered On</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pendingOrganizers as $i => $organizer): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($organizer['name']) ?></td>
                <td><?= esc($organizer['email']) ?></td>
                <td><?= esc($organizer['staff_id']) ?></td>
                <td><?= esc($organizer['created_at']) ?></td>
                <td>
                  <a href="<?= base_url('coordinator/approve/' . $organizer['id']) ?>" 
                     class="btn btn-sm btn-success"
                     onclick="return confirm('Approve this organizer account?')">
                    Approve
                  </a>
                  <a href="<?= base_url('coordinator/reject/' . $organizer['id']) ?>" 
                     class="btn btn-sm btn-danger"
                     onclick="return confirm('Reject and remove this registration?')">
                    Reject
                  </a>
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
