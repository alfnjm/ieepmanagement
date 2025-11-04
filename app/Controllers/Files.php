<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Files extends BaseController
{
    public function certificateTemplate($filename)
    {
        $path = WRITEPATH . 'uploads/cert_templates/' . basename($filename);

        if (!is_file($path)) {
            return $this->response->setStatusCode(404)->setBody('Not found');
        }

        // detect mime
        $mime = mime_content_type($path) ?: 'application/octet-stream';

        // stream inline (bukan download)
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="'.$filename.'"')
            ->setBody(file_get_contents($path));
    }
}
