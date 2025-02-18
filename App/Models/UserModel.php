<?php

namespace App\Models;

use PDO;
use Exception;


class UserModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ---------------------------
    // DUPLICATE CHECKS
    // ---------------------------

    // Check if email exists in the database
    public function existsByEmail(string $email, int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        if ($excludeId !== null) {
            $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return ((int) $stmt->fetchColumn()) > 0;
    }

    // Check if username exists in the database
    public function existsByUsername(string $username, int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        if ($excludeId !== null) {
            $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return ((int) $stmt->fetchColumn()) > 0;
    }

    // ---------------------------
    // PAGINATION & SEARCH
    // ---------------------------
    public function getUsers(int $currentUserId, string $searchTerm, int $offset, int $limit): array
    {
        $searchTerm = '%' . trim($searchTerm) . '%';

        $sql = "
        SELECT
            id,
            email,
            username,
            status,
            verified,
            roles_mask,
            registered,
            last_login,
            force_logout
        FROM users
        WHERE id != :currentUserId
          AND (username LIKE :search OR email LIKE :search)
        LIMIT :offset, :limit
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':currentUserId', $currentUserId, PDO::PARAM_INT);
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function countUsers(int $currentUserId, string $searchTerm): int
    {
        $searchTerm = '%' . trim($searchTerm) . '%';

        $sql = "
        SELECT COUNT(*)
        FROM users
        WHERE id != :currentUserId
          AND (username LIKE :search OR email LIKE :search)
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':currentUserId', $currentUserId, PDO::PARAM_INT);
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }


    // ---------------------------
    // CREATE / UPDATE / DELETE
    // ---------------------------

    // Create a new user
    public function createUser(
        string $username,
        string $email,
        string $password,
        int $rolesMask,
        int $status
    ): bool {
        // force_logout omitted from parameters => let's default to 0
        // or you can pass it in if needed
        $forceLogout = 0;
        $verified = 0;
        $resettable = 1;
        $registered = time();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO users (
                email,
                password,
                username,
                status,
                verified,
                resettable,
                roles_mask,
                registered,
                last_login,
                force_logout
            ) VALUES (
                :email,
                :password,
                :username,
                :status,
                :verified,
                :resettable,
                :roles_mask,
                :registered,
                NULL,
                :force_logout
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email',        $email,          PDO::PARAM_STR);
        $stmt->bindValue(':password',     $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(':username',     $username,       PDO::PARAM_STR);
        $stmt->bindValue(':status',       $status,         PDO::PARAM_INT);
        $stmt->bindValue(':verified',     $verified,       PDO::PARAM_INT);
        $stmt->bindValue(':resettable',   $resettable,     PDO::PARAM_INT);
        $stmt->bindValue(':roles_mask',   $rolesMask,      PDO::PARAM_INT);
        $stmt->bindValue(':registered',   $registered,     PDO::PARAM_INT);
        $stmt->bindValue(':force_logout', $forceLogout,    PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Update an existing user
    public function updateUser(
        int $id,
        string $username,
        string $email,
        int $rolesMask,
        int $status,
        int $forceLogout
    ): bool {
        $sql = "
            UPDATE users
            SET
                username     = :username,
                email        = :email,
                roles_mask   = :roles_mask,
                status       = :status,
                force_logout = :force_logout
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id',           $id,          PDO::PARAM_INT);
        $stmt->bindValue(':username',     $username,    PDO::PARAM_STR);
        $stmt->bindValue(':email',        $email,       PDO::PARAM_STR);
        $stmt->bindValue(':roles_mask',   $rolesMask,   PDO::PARAM_INT);
        $stmt->bindValue(':status',       $status,      PDO::PARAM_INT);
        $stmt->bindValue(':force_logout', $forceLogout, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Delete a user
    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
