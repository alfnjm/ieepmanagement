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

    
    <ul class="nav nav-tabs nav-fill mb-3" id="eventsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                Upcoming Events
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">
                My Event History
            </button>
        </li>
    </ul>

    <?php 
    // Get today's date once for the logic inside the loops
    $today = date('Y-m-d'); 
    ?>

    <div class="tab-content" id="eventsTabContent">

        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <div class="row mt-4">

            <?php if (empty($upcoming_events)): ?>
                <div class="col-12">
                    <p class="alert alert-info">There are no upcoming events at this time.</p>
                </div>
            <?php else: ?>
                <?php foreach($upcoming_events as $event): ?>
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
                            <?php if(isset($registeredEvents[$event['id']])): 
                                $reg = $registeredEvents[$event['id']]; ?>
                            
                                <?php if($reg['certificate_published'] == 1 && !empty($reg['certificate_path'])): ?>
                                    <a href="<?= site_url('user/downloadCertificate/'.$reg['id']) ?>" 
                                       class="btn btn-success">
                                        Download Certificate 
                                    </a>
                                <?php elseif ($reg['is_attended'] == 1): ?>
                                    <span class="badge bg-warning text-dark">Attended (Pending Publication)</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Registered</span>
                                <?php endif; ?>

                            <?php elseif ($event['date'] < $today): ?>
                                <span class="btn btn-secondary disabled">Event Passed</span>
                            <?php else: ?>
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

        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
            <div class="row mt-4">

            <?php if (empty($past_events)): ?>
                <div class="col-12">
                    <p class="alert alert-info">You have no past events in your history.</p>
                </div>
            <?php else: ?>
                <?php foreach($past_events as $event): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <?php if(!empty($event['thumbnail'])): ?>
                            <img src="<?= is_file(FCPATH.'uploads/posters/'.$event['thumbnail']) 
                                ? base_url('uploads/posters/'.$event['thumbnail']) 
                                : base_url('uploads/posters/default.jpg') // Fallback image
                            ?>" 
                            class="card-img-top" 
                            alt="Event Thumbnail"
                            style="height: 200px; object-fit: cover; filter: grayscale(50%);">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($event['title']) ?></h5>
                            <p class="card-text"><?= esc(mb_strimwidth($event['description'] ?? '', 0, 100, "...")) ?></p>
                            <small class="text-muted"><?= esc($event['date']) ?> @ <?= esc($event['location']) ?></small><br>
                        </div>

                        <div class="card-footer text-center">
                            <?php if(isset($registeredEvents[$event['id']])): 
                                $reg = $registeredEvents[$event['id']]; ?>
                            
                                <?php if($reg['certificate_published'] == 1 && !empty($reg['certificate_path'])): ?>
                                    <a href="<?= site_url('user/downloadCertificate/'.$reg['id']) ?>" 
                                       class="btn btn-success">
                                        Download Certificate 
                                    </a>
                                <?php elseif ($reg['is_attended'] == 1): ?>
                                    <span class="badge bg-warning text-dark">Attended (Pending Publication)</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Registered (Not Attended)</span>
                                <?php endif; ?>

                            <?php elseif ($event['date'] < $today): ?>
                                <span class="btn btn-secondary disabled">Event Passed</span>
                            <?php else: ?>
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

    </div> </div> <?= $this->include('layouts/footerT') ?>