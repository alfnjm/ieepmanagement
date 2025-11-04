<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateTemplateModel extends Model
{
    protected $table = 'certificate_templates';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'coordinator_id', 
        'template_name',
        'template_path',
        'name_x', 'name_y',
        'event_x', 'event_y',
        'student_id_x', 'student_id_y'
    ];
}