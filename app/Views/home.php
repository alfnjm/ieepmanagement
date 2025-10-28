<?= $this->extend('layouts/headerT') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    </div>


<div class="container px-4 py-5" id="custom-cards">
    <h2 class="pb-2 border-bottom">Upcoming Events</h2>

    <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
        
        <?php if (empty($events)): ?>
            <?php else: ?>
            <?php foreach ($events as $event): ?>
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
<?= $this->endSection() ?>