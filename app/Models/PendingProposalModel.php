<?php

namespace App\Models;

use CodeIgniter\Model;

class PendingProposalModel extends Model
{
    protected $table = 'pending_proposals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'event_name',
        'event_date',
        'event_time',
        'event_location',
        'eligible_age',
        'eligible_semesters',
        'event_description',
        'poster_image',
        'proposal_file',
        'status',
    ];

    protected $useTimestamps = true;
}
