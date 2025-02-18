<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\PaginationService;
use Exception;

class UserController
{
    private $userModel;
    private $auth;
    private $logger;
    private $paginationService;

    public function __construct($auth, $db, $logger)
    {
        $this->auth = $auth;
        $this->userModel = new UserModel($db);
        $this->logger = $logger;
        $this->paginationService = new PaginationService();
    }

    public static function create()
    {
        return new self(
            \App\Config\Container::get('auth'),
            \App\Config\Container::get('glavnilager_db'),
            \App\Config\Container::get('logger')
        );
    }

    /**
     * Proverava autentifikaciju i pristup.
     */
    private function checkAccess(string $requiredRole): bool
    {
        if (!$this->auth->isLoggedIn() || !$this->auth->hasRole($requiredRole)) {
            $this->respondWithError(403, 'Unauthorized');
            return false;
        }
        return true;
    }

    /**
     * Standardizovani odgovor.
     */
    private function respondWithJson(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    private function respondWithError(int $statusCode, string $message, array $details = [])
    {
        $this->respondWithJson(['error' => $message, 'details' => $details], $statusCode);
    }

    /**
     * GET /administrators/manage-users/data
     */
    public function fetchUsers()
    {
        try {
            if (!$this->checkAccess(\Delight\Auth\Role::DEVELOPER)) {
                return;
            }

            $page = (int) ($_GET['page'] ?? 1);
            $limit = (int) ($_GET['limit'] ?? $this->paginationService->getDefaultItemsPerPage());
            $search = trim($_GET['search'] ?? '');

            $pagination = $this->paginationService->paginate($page, $limit);
            $currentUserId = $this->auth->getUserId();

            $users = $this->userModel->getUsers($currentUserId, $search, $pagination['offset'], $pagination['limit']);
            $totalUsers = $this->userModel->countUsers($currentUserId, $search);

            $meta = $this->paginationService->generateMeta($totalUsers, $pagination['page'], $pagination['limit']);

            $this->respondWithJson(['success' => true, 'data' => $users, 'meta' => $meta]);
        } catch (Exception $e) {
            $this->logger->error('Error fetching users', ['error' => $e->getMessage()]);
            $this->respondWithError(500, 'An error occurred');
        }
    }

    /**
     * POST /administrators/manage-users/add
     */
    public function addUser()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->respondWithError(405, 'Method not allowed');
            }

            if (!$this->checkAccess(\Delight\Auth\Role::DEVELOPER)) {
                return;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $requiredFields = ['username', 'email', 'password', 'roles_mask'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $this->respondWithError(400, 'Invalid input', ['missing_field' => $field]);
                }
            }

            if ($this->userModel->existsByEmail($data['email'])) {
                $this->respondWithError(400, 'Validation error', ['details' => 'Email already in use']);
            }

            if ($this->userModel->existsByUsername($data['username'])) {
                $this->respondWithError(400, 'Validation error', ['details' => 'Username already in use']);
            }

            $success = $this->userModel->createUser(
                $data['username'],
                $data['email'],
                $data['password'],
                (int) $data['roles_mask'],
                (int) ($data['status'] ?? 0)
            );

            if ($success) {
                $this->respondWithJson(['success' => true]);
            } else {
                $this->respondWithError(500, 'Failed to add user', ['details' => 'Database error']);
            }
        } catch (Exception $e) {
            $this->logger->error('Error adding user', ['error' => $e->getMessage()]);
            $this->respondWithError(500, 'An error occurred while adding user', ['details' => $e->getMessage()]);
        }
    }

    /**
     * POST /administrators/manage-users/edit
     */
    public function editUser()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->respondWithError(405, 'Method not allowed');
            }

            if (!$this->checkAccess(\Delight\Auth\Role::DEVELOPER)) {
                return;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $requiredFields = ['id', 'username', 'email', 'roles_mask', 'status', 'force_logout'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $this->respondWithError(400, 'Invalid input', ['missing_field' => $field]);
                }
            }

            if ($this->userModel->existsByEmail($data['email'], $data['id'])) {
                $this->respondWithError(400, 'Validation error', ['details' => 'Email already in use']);
            }

            if ($this->userModel->existsByUsername($data['username'], $data['id'])) {
                $this->respondWithError(400, 'Validation error', ['details' => 'Username already in use']);
            }

            $success = $this->userModel->updateUser(
                (int) $data['id'],
                $data['username'],
                $data['email'],
                (int) $data['roles_mask'],
                (int) $data['status'],
                (int) $data['force_logout']
            );

            if ($success) {
                $this->respondWithJson(['success' => true]);
            } else {
                $this->respondWithError(500, 'Failed to update user', ['details' => 'Database error']);
            }
        } catch (Exception $e) {
            $this->logger->error('Error updating user', ['error' => $e->getMessage()]);
            $this->respondWithError(500, 'An error occurred while updating user', ['details' => $e->getMessage()]);
        }
    }

    /**
     * POST /administrators/manage-users/delete
     */
    public function deleteUser()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->respondWithError(405, 'Method not allowed');
            }

            if (!$this->checkAccess(\Delight\Auth\Role::DEVELOPER)) {
                return;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;

            if (!$id) {
                $this->respondWithError(400, 'Invalid user ID');
            }

            $success = $this->userModel->deleteUser((int) $id);

            if ($success) {
                $this->respondWithJson(['success' => true]);
            } else {
                $this->respondWithError(500, 'Failed to delete user');
            }
        } catch (Exception $e) {
            $this->logger->error('Error deleting user', ['error' => $e->getMessage()]);
            $this->respondWithError(500, 'An error occurred while deleting user');
        }
    }
}
