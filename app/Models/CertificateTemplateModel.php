<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateTemplateModel extends Model
{
    protected $table = 'certificate_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'organizer_id',
        'template_name',
        'template_path',
        'name_x', 'name_y',
        'event_x', 'event_y',
        'date_x', 'date_y'
    ];
}