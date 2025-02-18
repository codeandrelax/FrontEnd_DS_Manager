<?php

namespace App\Controllers;

use App\Models\AdminsModel;

class AdminsController
{
    private $adminsModel;
    private $auth;

    public function __construct($auth, $db)
    {
        $this->auth = $auth;
        $this->adminsModel = new AdminsModel($db);
    }

    public function getUsersByRoles()
    {
        try {
            if (!$this->auth->isLoggedIn() || !$this->auth->hasRole(\Delight\Auth\Role::DEVELOPER)) {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $data = $this->adminsModel->fetchUsersByRoles();
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error fetching users by roles.']);
        }
    }

    public function getActiveUsersByDays()
    {
        try {
            if (!$this->auth->isLoggedIn() || !$this->auth->hasRole(\Delight\Auth\Role::DEVELOPER)) {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $data = $this->adminsModel->fetchActiveUsersByDays();
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error fetching active users by days.']);
        }
    }

    public function getRegisteredUsersByMonths()
    {
        try {
            if (!$this->auth->isLoggedIn() || !$this->auth->hasRole(\Delight\Auth\Role::DEVELOPER)) {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $data = $this->adminsModel->fetchRegisteredUsersByMonths();
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error fetching registered users by months.']);
        }
    }

    public function getServerUsage()
    {
        try {
            if (!$this->auth->isLoggedIn() || !$this->auth->hasRole(\Delight\Auth\Role::DEVELOPER)) {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $data = $this->adminsModel->fetchServerUsage();
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error fetching server usage.']);
        }
    }


    // Fetch online users using Delight Auth and session tracking
    public function getOnlineUsers()
    {
        try {
            $onlineUsers = $this->adminsModel->getOnlineUsers();
            echo json_encode(['success' => true, 'data' => $onlineUsers]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Error fetching online users.']);
        }
    }
}
