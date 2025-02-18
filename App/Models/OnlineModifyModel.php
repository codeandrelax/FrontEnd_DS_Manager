<?php

namespace App\Models;

use PDO;

class OnlineModifyModel
{
    private PDO $db;

    /**
     * Constructor.
     *
     * @param PDO $db The PDO database connection.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Updates the online status for a given user.
     *
     * @param int $userId The user's ID.
     * @param int $status The online status (1 for online, 0 for offline).
     * @return bool Returns true if the update was successful, false otherwise.
     */
    private function setOnlineStatus(int $userId, int $status): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET online = :status WHERE id = :userId");
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Marks the user as online.
     *
     * @param int $userId The user's ID.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function login(int $userId): bool
    {
        return $this->setOnlineStatus($userId, 1);
    }

    /**
     * Marks the user as offline.
     *
     * @param int $userId The user's ID.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function logout(int $userId): bool
    {
        return $this->setOnlineStatus($userId, 0);
    }
}
