<?= $this->include('layouts/usermain') ?>

<div class="container my-5">
  <h2 class="mb-4">Hye, <?= session()->get('name'); ?> 👋</h2>

  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
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
      [
        'id' => 3,
        'title' => 'Charity Run 2025',
        'description' => 'Run for a cause! Join our 5KM charity marathon.',
        'date' => '2025-12-10',
        'location' => 'Main Stadium',
        'thumbnail' => 'https://source.unsplash.com/400x200/?marathon,run'
      ],
    ];

    $listEvents = !empty($events) ? $events : $dummyEvents;
    ?>

    <?php foreach($listEvents as $event): ?>
      <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
          <?php if(!empty($event['thumbnail'])): ?>
            <img src="<?= is_file(FCPATH.'uploads/'.$event['thumbnail']) 
                        ? base_url('uploads/'.$event['thumbnail']) 
                        : $event['thumbnail'] ?>" 
                class="card-img-top" 
                alt="Event Thumbnail">
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?= esc($event['title']) ?></h5>
            <p class="card-text"><?= esc($event['description']) ?></p>
            <small class="text-muted"><?= esc($event['date']) ?> @ <?= esc($event['location']) ?></small><br>

            <?php if(isset($registeredEvents[$event['id']])): ?>
              <span class="badge bg-success mt-2">Already Registered</span>
            <?php else: ?>
              <a href="<?= base_url('user/registerEvent/'.$event['id']) ?>" 
                class="btn btn-primary btn-sm mt-2">Register</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>

<?= $this->include('layouts/footerT') ?>
