<?= $this->extend('layouts/organizermain') ?>
<?= $this->section('content') ?>

<!-- 
    ADD THE TOAST HTML BACK
    This is the container that will hold our pop-up message.
--><div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="attendanceToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-check-circle-fill me-2 text-success"></i>
            <strong class="me-auto">Attendance Updated</strong>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-body-content">
            <!-- Message will be set by JavaScript --></div>
    </div>
</div>


<h3 class="mb-4">Event Participants & Attendance</h3>

<!-- This dropdown form is correct --><form method="GET" action="<?= base_url('organizer/participants') ?>" class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <label for="event_id" class="form-label">Select Event:</label>
            <select name="event_id" id="event_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Choose an Event --</option>
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <option value="<?= $event['id'] ?>" <?= ($selected_event == $event['id']) ? 'selected' : '' ?>>
                            <?= esc($event['title']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</form>

<!-- ADD THE STANDALONE CSRF FIELD BACK --><?= csrf_field() ?>

<?php if ($selected_event && !empty($participants)): ?>
    <hr>
    
    <!-- REMOVE THE <form method="POST"...> TAG HERE --><!-- and remove the closing </form> tag at the bottom --><div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <!-- REMOVE "Select All" checkbox, as it was for the button method --><th style="width: 5%;">Attended</th> 
                    <th>Name</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <!-- REMOVE "Status" column, as instant checkbox shows status --></tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $p): ?>
                    <tr id="user-row-<?= esc($p['user_id']) ?>">
                        <td class="text-center">
                            <!-- 
                                REVERT CHECKBOX to instant-save style.
                                - Add onchange="updateAttendance(this)"
                                - Add data-participant-name
                                - Remove name="participants[]"
                            --><input class="form-check-input" type="checkbox" 
                                   value="" 
                                   <?= ($p['is_attended'] == 1) ? 'checked' : '' ?>
                                   
                                   data-user-id="<?= esc($p['user_id']) ?>"
                                   data-event-id="<?= esc($selected_event) ?>"
                                   data-participant-name="<?= esc($p['name']) ?>"
                                   
                                   onchange="updateAttendance(this)">
                        </td>
                        <td><?= esc($p['name']) ?></td>
                        <td><?= esc($p['student_id'] ?? 'N/A') ?></td>
                        <td><?= esc($p['email']) ?></td>
                        <!-- The "Status" column is removed because the checkbox itself implies status --></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- REMOVE THE BUTTONS AND THEIR WRAPPING DIV --><?php elseif ($selected_event): ?>
    <div class="alert alert-info">No participants have registered for this event yet.</div>
<?php endif; ?>


<!-- 
    REVERT TO THE OLD SCRIPT FOR INSTANT-SAVE (AJAX) 
--><script>
    // --- Get the toast elements ---
    const toastElement = document.getElementById('attendanceToast');
    const bsToast = new bootstrap.Toast(toastElement);
    const toastBody = document.getElementById('toast-body-content');

    // Find the CSRF input field
    const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
    
    // This is the function called by the 'onchange' event
    function updateAttendance(checkbox) {
        // Get the participant's name for the toast message
        const participantName = checkbox.dataset.participantName;
        
        const userId = checkbox.dataset.userId;
        const eventId = checkbox.dataset.eventId;
        const isAttended = checkbox.checked ? 1 : 0;
        
        const row = document.getElementById('user-row-' + userId);

        const formData = new FormData();
        formData.append('user_id', userId);
        formData.append('event_id', eventId);
        formData.append('is_attended', isAttended);
        formData.append(csrfInput.name, csrfInput.value); // Add CSRF token

        // Show "working" feedback
        row.style.backgroundColor = '#fcf8e3'; // Yellow
        
        fetch('<?= base_url('organizer/participants/update') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Important for CI4 'isAJAX()'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Update the CSRF token value for the next request
            csrfInput.value = data.csrf_hash;
            
            if (data.status === 'success') {
                // Set the message text and show the toast
                if (isAttended) {
                    toastBody.innerText = `${participantName} marked as ATTENDED.`;
                } else {
                    toastBody.innerText = `${participantName} marked as ABSENT.`;
                }
                bsToast.show();

                // Keep the visual row feedback
                row.style.backgroundColor = '#dff0d8'; // Green
            
            } else {
                // Error handling
                row.style.backgroundColor = '#f2dede'; // Red
                console.error('Error:', data.message);
                alert('Error: Could not save attendance. ' + (data.message || ''));
            }

            // Reset the color after 2 seconds
            setTimeout(() => {
                row.style.backgroundColor = '';
            }, 2000);
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            row.style.backgroundColor = '#f2dede'; // Red
            alert('A network error occurred. Please try again.');
        });
    }
</script>

<?= $this->endSection() ?>