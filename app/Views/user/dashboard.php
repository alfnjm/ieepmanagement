<?= $this->include('layouts/usermain') ?>

<div class="container my-5">
  <h2 class="mb-4">Hye, <?= session()->get('name'); ?> 👋</h2>

  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>
   <?php if(session()->getFlashdata('info')): ?>
    <div class="alert alert-info"><?= session()->getFlashdata('info') ?></div>
  <?php endif; ?>

  <h3 class="mb-4">Ongoing Events</h3>
  <div class="row">

    <?php 
    // Kalau event kosong, guna dummy data
    $dummyEvents = [
      [
        'id' => 1,
        'title' => 'Tech Talk: AI in 2025',
        'description' => 'Join us for an insightful talk on Artificial Intelligence trends.',
        'date' => '2025-10-15',
        'location' => 'Auditorium A',
        'thumbnail' => 'https://source.unsplash.com/400x200/?technology,ai'
      ],
      [
        'id' => 2,
        'title' => 'Entrepreneurship Workshop',
        'description' => 'Learn how to start and grow your own business.',
        'date' => '2025-11-01',
        'location' => 'Hall B',
        'thumbnail' => 'https://source.unsplash.com/400x200/?business,workshop'
      ],
    ];

    // Use $events from the controller if available, otherwise use dummy data
    // Note: The $events variable is passed from your User::dashboard() controller method
    $listEvents = !empty($events) ? $events : $dummyEvents;
    ?>

    <?php if (empty($listEvents)): ?>
        <div class="col-12">
            <p class="alert alert-info">There are no events currently available.</p>
        </div>
    <?php else: ?>
        <?php foreach($listEvents as $event): ?>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
            <?php if(!empty($event['thumbnail'])): ?>
                <img src="<?= is_file(FCPATH.'uploads/posters/'.$event['thumbnail']) 
                            ? base_url('uploads/posters/'.$event['thumbnail']) 
                            : $event['thumbnail'] ?>" 
                    class="card-img-top" 
                    alt="Event Thumbnail"
                    style="height: 200px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= esc($event['title']) ?></h5>
                <p class="card-text" style="min-height: 4.5em;"><?= esc(mb_strimwidth($event['description'], 0, 100, "...")) ?></p>
                <small class="text-muted"><?= esc($event['date']) ?> @ <?= esc($event['location']) ?></small><br>

                <?php if(isset($registeredEvents[$event['id']])): ?>
                
                    <?php if($registeredEvents[$event['id']]['certificate_ready'] == 1): ?>
                        <a href="<?= base_url('user/printCertificate/'.$event['id']) ?>" 
                        class="btn btn-success btn-sm mt-2" target="_blank">
                            Download Certificate 
                        </a>
                    <?php else: ?>
                        <span class="badge bg-primary mt-2">Registered (Attendance Pending)</span>
                    <?php endif; ?>

                <?php else: ?>
                    <a href="<?= base_url('user/registerEvent/'.$event['id']) ?>" 
                        class="btn btn-primary btn-sm mt-2">Register</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

  </div>
</div>

<?= $this->include('layouts/footerT') ?>