<?php

namespace App\Models;

use PDO;

class AdminsModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function fetchUsersByRoles(): array
    {
        $sql = "
            SELECT roles_mask, COUNT(*) as count 
            FROM users 
            GROUP BY roles_mask
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map roles_mask to human-readable roles
        $rolesMapping = [
            1 => 'ADMIN',
            2 => 'AUTHOR',
            4 => 'COLLABORATOR',
            8 => 'CONSULTANT',
            16 => 'CONSUMER',
            32 => 'CONTRIBUTOR',
            64 => 'COORDINATOR',
            128 => 'CREATOR',
            256 => 'DEVELOPER',
            512 => 'DIRECTOR',
            1024 => 'EDITOR',
            2048 => 'EMPLOYEE',
            4096 => 'MAINTAINER',
            8192 => 'MANAGER',
            16384 => 'MODERATOR',
            32768 => 'PUBLISHER',
            65536 => 'REVIEWER',
            131072 => 'SUBSCRIBER',
            262144 => 'SUPER_ADMIN',
            524288 => 'SUPER_EDITOR',
            1048576 => 'SUPER_MODERATOR',
            2097152 => 'TRANSLATOR'
        ];

        foreach ($data as &$item) {
            $item['role_name'] = $rolesMapping[$item['roles_mask']] ?? 'UNKNOWN';
        }

        return $data;
    }

    public function fetchActiveUsersByDays(): array
    {
        $sql = "
            SELECT DATE(FROM_UNIXTIME(last_login)) as day, COUNT(*) as count
            FROM users
            WHERE last_login IS NOT NULL
            GROUP BY day
            ORDER BY day DESC
            LIMIT 7
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchRegisteredUsersByMonths(): array
    {
        $sql = "
            SELECT MONTH(FROM_UNIXTIME(registered)) as month, COUNT(*) as count
            FROM users
            WHERE registered IS NOT NULL
            GROUP BY month
            ORDER BY month
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchServerUsage(): array
    {
        // PowerShell za CPU Usage
        $cpuUsage = shell_exec('powershell "Get-Counter -Counter \'\\Processor(_Total)\\% Processor Time\' -SampleInterval 1 -MaxSamples 1 | Select -ExpandProperty CounterSamples | Select -ExpandProperty CookedValue"');
        $cpu = $cpuUsage ? (int) round($cpuUsage) : 0;

        // PowerShell za RAM Usage
        $ramOutput = shell_exec('powershell "Get-CimInstance -ClassName Win32_OperatingSystem | Select-Object FreePhysicalMemory,TotalVisibleMemorySize"');
        preg_match('/FreePhysicalMemory\s+:\s+(\d+)/', $ramOutput, $freeMatches);
        preg_match('/TotalVisibleMemorySize\s+:\s+(\d+)/', $ramOutput, $totalMatches);

        $totalRam = isset($totalMatches[1]) ? (int) $totalMatches[1] : 1;
        $freeRam  = isset($freeMatches[1])  ? (int) $freeMatches[1]  : 0;
        $usedRam  = $totalRam - $freeRam;
        $ram      = ($usedRam / $totalRam) * 100;

        // Disk Usage
        $diskTotal = disk_total_space("C:");
        $diskFree  = disk_free_space("C:");
        $diskUsed  = $diskTotal - $diskFree;
        $ssd       = ($diskUsed / $diskTotal) * 100;

        return [
            'cpu' => $cpu,
            'ram' => round($ram, 2),
            'ssd' => round($ssd, 2)
        ];
    }


    public function getOnlineUsers(): array
    {
        $sql = "SELECT id, username, email, online FROM users WHERE online = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
