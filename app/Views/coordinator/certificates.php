<?= $this->extend('layouts/coordinator_main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Publish Certificates</h2>
            <p>Select an event to generate and publish certificates for all participants marked as "attended" by the organizer.</p>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Event Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($events)) : ?>
                                <?php foreach ($events as $event) : ?>
                                    <tr>
                                        <td><?= esc($event['title']) ?></td>
                                        <td><?= !empty($event['date']) ? date('d M Y', strtotime($event['date'])) : 'No Date' ?></td>
                                        <td>
                                            <a href="<?= base_url('coordinator/publish_certificates/' . $event['id']) ?>" 
                                               class="btn btn-primary btn-sm"
                                               onclick="return confirm('Are you sure you want to generate and publish all pending certificates for this event?')">
                                               Publish Certificates
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center">No approved events found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>