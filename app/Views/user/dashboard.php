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
                <p class="card-text" ...><?= esc(mb_strimwidth($event['description'] ?? '', 0, 100, "...")) ?></p>
                <small class="text-muted"><?= esc($event['date']) ?> @ <?= esc($event['location']) ?></small><br>

                <?php 
                // --- START OF FIXED SECTION ---
                if(isset($registeredEvents[$event['id']])): 
                    // Get the specific registration details
                    $reg = $registeredEvents[$event['id']]; 
                ?>
                
                    <?php // Check if certificate is PUBLISHED
                    if($reg['certificate_published'] == 1 && !empty($reg['certificate_path'])): ?>
                        <a href="<?= base_url('user/downloadCertificate/'.$event['id']) ?>" 
                           class="btn btn-success btn-sm mt-2">
                            Download Certificate 
                        </a>
                    <?php // Check if user ATTENDED (ready=1) but cert is NOT published yet
                        elseif ($reg['is_attended'] == 1): ?>
                        <span class="badge bg-warning text-dark mt-2">Attended (Pending Publication)</span>
                    <?php // User is registered but attendance not marked
                    else: ?>
                        <span class="badge bg-primary mt-2">Registered</span>
                    <?php endif; ?>

                <?php else: ?>
                    <a href="<?= base_url('user/registerEvent/'.$event['id']) ?>" 
                        class="btn btn-primary btn-sm mt-2">Register</a>
                <?php endif; 
                // --- END OF FIXED SECTION ---
                ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

  </div>
</div>

<?= $this->include('layouts/footerT') ?>