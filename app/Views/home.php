<?= $this->extend('layouts/headerT') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    </div>

<div class="container px-4 py-5" id="events-section">

    <ul class="nav nav-tabs nav-fill mb-3" id="eventsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                Upcoming Events
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">
                Past Events
            </button>
        </li>
    </ul>

    <div class="tab-content" id="eventsTabContent">

        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
                
                <?php if (empty($upcoming_events)): ?>
                    <div class="col-12">
                        <p class="text-muted">There are no upcoming events at this time.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($upcoming_events as $event): ?>
                        <div class="col">
                        
                            <a href="<?= base_url('event/' . $event['id']) ?>" 
                               class="text-decoration-none">
                                
                                <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" 
                                     style="background-image: url('<?= is_file(FCPATH.'uploads/posters/'.$event['thumbnail']) ? base_url('uploads/posters/'.$event['thumbnail']) : $event['thumbnail'] ?>'); background-size: cover; background-position: center;">
                                    
                                    <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1" style="background-color: rgba(0,0,0,0.5);">
                                        <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold"><?= esc($event['title']) ?></h3>
                                        <ul class="d-flex list-unstyled mt-auto">
                                            <li class="me-auto">
                                                <small>📅 <?= esc($event['date']) ?></small>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <small>📍 <?= esc($event['location']) ?></small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </a> 
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
            <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
                
                <?php if (empty($past_events)): ?>
                    <div class="col-12">
                        <p class="text-muted">There are no past events to show.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($past_events as $event): ?>
                        <div class="col">
                        
                            <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" 
                                 style="background-image: url('<?= is_file(FCPATH.'uploads/posters/'.$event['thumbnail']) ? base_url('uploads/posters/'.$event['thumbnail']) : $event['thumbnail'] ?>'); background-size: cover; background-position: center; filter: grayscale(80%);">

                                <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1" style="background-color: rgba(0,0,0,0.6);">
                                
                                    <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold"><?= esc($event['title']) ?></h3>
                                    <ul class="d-flex list-unstyled mt-auto">
                                    
                                        <li class="me-auto">
                                            <small>📅 <?= esc($event['date']) ?></small>
                                        </li>
                                    
                                        <li class="d-flex align-items-center">
                                            <small>📍 <?= esc($event['location']) ?></small>
                                        </li>
                                    
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>