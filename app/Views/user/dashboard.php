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
        $listEvents = $events ?? [];
        ?>

        <?php if (empty($listEvents)): ?>
            <div class="col-12">
                <p class="alert alert-info">There are no events currently available.</p>
            </div>
        <?php else: ?>
            <?php 
            // Get today's date once outside the loop for efficiency
            $today = date('Y-m-d'); 
            ?>
            <?php foreach($listEvents as $event): ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <?php if(!empty($event['thumbnail'])): ?>
                        <img src="<?= is_file(FCPATH.'uploads/posters/'.$event['thumbnail']) 
                                    ? base_url('uploads/posters/'.$event['thumbnail']) 
                                    : base_url('uploads/posters/default.jpg') // Fallback image
                                  ?>" 
                             class="card-img-top" 
                             alt="Event Thumbnail"
                             style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($event['title']) ?></h5>
                        <p class="card-text"><?= esc(mb_strimwidth($event['description'] ?? '', 0, 100, "...")) ?></p>
                        <small class="text-muted"><?= esc($event['date']) ?> @ <?= esc($event['location']) ?></small><br>
                    </div>

                    <div class="card-footer text-center">
                        <?php 
                        // 1. Check if user is already registered
                        if(isset($registeredEvents[$event['id']])): 
                            $reg = $registeredEvents[$event['id']]; // Get the specific registration details
                        ?>
                        
                            <?php // Check if certificate is PUBLISHED
                            if($reg['certificate_published'] == 1 && !empty($reg['certificate_path'])): ?>
                                
                                <a href="<?= site_url('user/downloadCertificate/'.$reg['id']) ?>" 
                                   class="btn btn-success">
                                    Download Certificate 
                                </a>

                            <?php // Check if user ATTENDED but cert is NOT published
                                elseif ($reg['is_attended'] == 1): ?>
                                <span class="badge bg-warning text-dark">Attended (Pending Publication)</span>
                            
                            <?php // User is registered but attendance not marked
                            else: ?>
                                <span class="badge bg-primary">Registered</span>
                            <?php endif; ?>

                        <?php 
                        // 2. Check if the event date is in the past
                        elseif ($event['date'] < $today): 
                        ?>
                            <span class="btn btn-secondary disabled">Event Passed</span>
                        
                        <?php 
                        // 3. Otherwise, OK to register
                        else: 
                        ?>
                            <a href="<?= site_url('user/registerEvent/'.$event['id']) ?>" 
                               class="btn btn-primary">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<?= $this->include('layouts/footerT') ?>