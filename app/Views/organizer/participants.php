<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Event Registrations</h3>

<table class="table table-bordered">
  <thead class="table-light">
    <tr>
      <th>#</th><th>Event Name</th><th>Participant</th><th>Matric No</th><th>Email</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>1</td><td>Tech Workshop</td><td>Aiman Hakim</td><td>DDT5A-001</td><td>aiman@example.com</td></tr>
    <tr><td>2</td><td>Tech Workshop</td><td>Siti Aminah</td><td>DDT5A-002</td><td>siti@example.com</td></tr>
  </tbody>
</table>

<?= $this->endSection() ?>
