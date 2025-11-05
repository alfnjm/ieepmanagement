<?= $this->extend('layouts/headerT') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="card shadow border-0">

        <!-- Event Poster -->
        <?php if (!empty($event['thumbnail'])): ?>
            <img src="<?= base_url('uploads/posters/' . $event['thumbnail']) ?>" 
                 class="card-img-top" 
                 alt="Event Poster" 
                 style="max-height: 400px; object-fit: cover;">
        <?php endif; ?>

        <div class="card-body">
            <!-- Event Title -->
            <h3 class="card-title mb-3 text-primary fw-bold">
                <?= esc($event['title']) ?>
            </h3>

            <!-- Event Date & Time (Main) -->
            <?php
                $start = !empty($event['program_start']) ? new DateTime($event['program_start']) : null;
                $end = !empty($event['program_end']) ? new DateTime($event['program_end']) : null;

                $eventDateTime = 'N/A';
                if ($start && $end) {
                    if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
                        // Same day event
                        $eventDateTime = $start->format('l, d M Y') . ', ' . $start->format('h:i A') . ' – ' . $end->format('h:i A');
                    } else {
                        // Multi-day event
                        $eventDateTime = $start->format('l, d M Y, h:i A') . ' – ' . $end->format('l, d M Y, h:i A');
                    }
                } elseif ($start) {
                    $eventDateTime = $start->format('l, d M Y, h:i A');
                }
            ?>

            <!-- Event Details -->
            <ul class="list-unstyled mb-4">
                <li><strong>📅 Date & Time:</strong> <?= $eventDateTime ?></li>
                <li><strong>📍 Location:</strong> <?= esc($event['location'] ?? 'N/A') ?></li>
                <li><strong>🎓 Eligible Semesters:</strong> <?= esc($event['eligible_semesters'] ?? 'N/A') ?></li>
            </ul>

            <!-- Daily Schedule -->
            <h5 class="fw-semibold mt-3">📅 Daily Schedule</h5>
            <?php
                $days = [];

                // If event_days exists, decode it
                if (!empty($event['event_days'])) {
                    $days = json_decode($event['event_days'], true);
                }
                // If event_days is empty, generate from program_start/program_end
                elseif (!empty($event['program_start']) && !empty($event['program_end'])) {
                    $startDay = new DateTime($event['program_start']);
                    $endDay = new DateTime($event['program_end']);
                    while ($startDay <= $endDay) {
                        $days[] = [
                            'date' => $startDay->format('Y-m-d'),
                            'time' => $event['time'] ?? '00:00:00'
                        ];
                        $startDay->modify('+1 day');
                    }
                }
            ?>

            <?php if (!empty($days)): ?>
                <ul>
                    <?php foreach ($days as $day): ?>
                        <li>
                            <?= date('l, d M Y', strtotime($day['date'])) ?> – 
                            Start at <?= date('h:i A', strtotime($day['time'])) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No day-by-day schedule available.</p>
            <?php endif; ?>

            <!-- Event Description -->
            <h5 class="fw-semibold mt-3">Event Description</h5>
            <p><?= esc($event['description'] ?? 'No description available.') ?></p>

            <!-- Proposal PDF (if exists) -->
            <?php if (!empty($event['proposal_file'])): ?>
                <a href="<?= base_url('uploads/proposals/' . $event['proposal_file']) ?>" 
                   target="_blank" 
                   class="btn btn-outline-primary mt-2">
                    📄 View Proposal Document
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
