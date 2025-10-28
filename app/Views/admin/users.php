<?= $this->extend('layouts/adminmain') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">User Management</h2>

<!-- Success & Error Messages -->
<?php if (session()->has('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= session('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h5>All Users</h5>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add User</button>
</div>

<table class="table table-bordered bg-white shadow-sm">
  <thead class="table-light">
    <tr>
      <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= esc($user['name']) ?></td>
        <td><?= esc($user['email']) ?></td>
        <td>
          <span class="badge 
            <?= $user['role'] == 'admin' ? 'bg-danger' : '' ?>
            <?= $user['role'] == 'coordinator' ? 'bg-warning' : '' ?>
            <?= $user['role'] == 'organizer' ? 'bg-info' : '' ?>
            <?= $user['role'] == 'user' ? 'bg-success' : '' ?>">
            <?= ucfirst($user['role']) ?>
          </span>
        </td>
        <td>
          <a href="<?= base_url('admin/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning me-1">Edit</a>
          <form action="<?= base_url('admin/delete/' . $user['id']) ?>" method="POST" class="d-inline">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-sm btn-danger"
              onclick="return confirm('Are you sure you want to delete this user?')">
              Delete
            </button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Add User Modal (reuse your existing one) -->
<?= view('admin/_modal_add_user') ?>

<?= $this->endSection() ?>
