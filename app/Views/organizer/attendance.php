<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Attendance List</h3>

<table class="table table-bordered">
  <thead class="table-light">
    <tr><th>#</th><th>Participant</th><th>Matric No</th><th>Event</th><th>Attendance</th></tr>
  </thead>
  <tbody>
    <tr><td>1</td><td>Aiman Hakim</td><td>DDT5A-001</td><td>Tech Workshop</td><td><input type="checkbox" checked></td></tr>
    <tr><td>2</td><td>Siti Aminah</td><td>DDT5A-002</td><td>Tech Workshop</td><td><input type="checkbox"></td></tr>
  </tbody>
</table>

<?= $this->endSection() ?>
