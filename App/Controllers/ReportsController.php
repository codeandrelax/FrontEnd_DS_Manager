<?php

namespace App\Controllers;

use App\Models\ReportsModel;

class ReportsController
{
    private $reportsModel;

    public function __construct()
    {
        $this->reportsModel = new ReportsModel(); // Kreiranje modela
    }

    /**
     * Vrati listu dostupnih log fajlova.
     */
    public function getLogs()
    {
        $logs = $this->reportsModel->getLogFiles();

        echo json_encode(['success' => true, 'logs' => $logs]);
    }

    /**
     * Vrati sadržaj određenog log fajla.
     */
    public function viewLog()
    {
        $fileName = htmlspecialchars($_GET['file'] ?? '');

        if (empty($fileName)) {
            echo json_encode(['success' => false, 'message' => 'File not specified.']);
            return;
        }

        $content = $this->reportsModel->getLogContent($fileName);

        echo json_encode(['success' => true, 'content' => $content, 'file' => $fileName]);
    }
}
