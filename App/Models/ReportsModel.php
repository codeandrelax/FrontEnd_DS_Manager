<?php

namespace App\Models;

use Exception;

class ReportsModel
{
    private $logsDirectory;
    private $allowedFiles;

    public function __construct()
    {
        $this->logsDirectory = __DIR__ . '/../../logs/';
        $this->allowedFiles = [
            'login.log',
            'register.log',
            'database.log',
            'logout.log',
            'unknown-route.log',
            'app.log'
        ];
    }

    /**
     * Vrati listu dostupnih log fajlova.
     */
    public function getLogFiles(): array
    {
        $logs = [];

        foreach ($this->allowedFiles as $file) {
            $filePath = $this->logsDirectory . $file;

            if (file_exists($filePath)) {
                $logs[] = [
                    'name' => ucfirst(str_replace(['-', '.log'], [' ', ''], $file)),
                    'file' => $file
                ];
            }
        }

        if (empty($logs)) {
            throw new Exception('No log files found.');
        }

        return $logs;
    }

    /**
     * Vrati sadržaj određenog log fajla.
     */
    public function getLogContent(string $file): string
    {
        $sanitizedFile = basename($file);
        $filePath = $this->logsDirectory . $sanitizedFile;

        if (!in_array($sanitizedFile, $this->allowedFiles)) {
            throw new Exception('Access to this file is not allowed.');
        }

        if (!file_exists($filePath)) {
            throw new Exception('File does not exist.');
        }

        return file_get_contents($filePath);
    }
}
