<?php

namespace App\Controllers;

use Delight\Auth\Auth;
use App\Middleware\CsrfVerifier;
use App\Services\SessionTokenProvider;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Models\OnlineModifyModel;

class AuthController
{
    private Auth $auth;
    private CsrfVerifier $csrfVerifier;
    private Logger $registerLogger;
    private Logger $loginLogger;
    private Logger $logoutLogger;
    private OnlineModifyModel $onlineModifyModel;

    public function __construct(
        Auth $auth,
        CsrfVerifier $csrfVerifier,
        Logger $logger,
        OnlineModifyModel $onlineModifyModel
    ) {
        $this->auth = $auth;
        $this->csrfVerifier = $csrfVerifier;
        $this->onlineModifyModel = $onlineModifyModel;

        // Configure separate loggers for each action
        $this->registerLogger = new Logger('register');
        $this->registerLogger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/register.log', Logger::INFO));

        $this->loginLogger = new Logger('login');
        $this->loginLogger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/login.log', Logger::INFO));

        $this->logoutLogger = new Logger('logout');
        $this->logoutLogger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/logout.log', Logger::INFO));
    }

    public function loginPage()
    {
        require __DIR__ . '/../Views/Login.php';
    }

    public function registerPage()
    {
        require __DIR__ . '/../Views/Register.php';
    }

    public function processLogin()
    {
        $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        $rememberMeDuration = 30 * 24 * 60 * 60; // 30 dana
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';

        try {
            // Prilagođena validacija šifre
            $this->validatePassword($password);

            // Prijava korisnika
            if ($remember) {
                $this->auth->loginWithUsername($username, $password, $rememberMeDuration);
            } else {
                $this->auth->loginWithUsername($username, $password);
            }

            // Update online status to 1
            $userId = $this->auth->getUserId();
            if ($userId !== null) {
                $this->onlineModifyModel->login($userId);
            }

            // Log uspešne prijave
            $this->loginLogger->info('Successful login', [
                'ip'       => $ipAddress,
                'username' => $username,
                'userId'   => $userId,
                'time'     => date('Y-m-d H:i:s'),
            ]);

            // Generisanje novog CSRF tokena
            $tokenProvider = $this->csrfVerifier->getTokenProvider();
            if ($tokenProvider instanceof SessionTokenProvider) {
                $tokenProvider->regenerate();
            }

            header('Location: /dashboard');
            exit;
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $_SESSION['login_error'] = 'Invalid email address.';
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $_SESSION['login_error'] = 'Invalid password.';
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $_SESSION['login_error'] = 'Too many requests. Please try again later.';
        } catch (\Exception $e) {
            $_SESSION['login_error'] = $e->getMessage();
        }

        // Redirekcija nazad na stranicu za prijavu sa porukom o grešci
        header('Location: /login', true, 303);
        exit;
    }

    public function register()
    {
        $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';

        $userId = null; // Čuvamo Delight Auth userId ukoliko bude kreiran

        try {
            // Osnovne validacije
            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                throw new \Exception('All fields are required.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Invalid email address.');
            }
            if ($password !== $confirmPassword) {
                throw new \Exception('Passwords do not match.');
            }
            $this->validatePassword($password);

            // Kreiramo korisnika u Delight Auth
            $userId = $this->auth->registerWithUniqueUsername($email, $password, $username);

            // obrisati ovo i napraviti model za dodavanje role-a mjesto admin() metode
            // mozda cak ovo i ne koristiti
            $this->auth->admin()->addRoleForUserById($userId, \Delight\Auth\Role::CONSUMER);

            $this->registerLogger->info('Successful Delight Auth registration', [
                'ip'       => $ipAddress,
                'username' => $username,
                'email'    => $email,
                'userId'   => $userId,
                'time'     => date('Y-m-d H:i:s')
            ]);

            // Regenerišemo CSRF token
            $tokenProvider = $this->csrfVerifier->getTokenProvider();
            if ($tokenProvider instanceof SessionTokenProvider) {
                $tokenProvider->regenerate();
            }

            $_SESSION['register_success'] = 'Registration successful. You can now log in.';
            header('Location: /register');
            exit;
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $_SESSION['register_error'] = 'Email is already taken.';
        } catch (\Delight\Auth\DuplicateUsernameException $e) {
            $_SESSION['register_error'] = 'Username is already taken.';
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $_SESSION['register_error'] = 'Invalid email address.';
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $_SESSION['register_error'] = 'Invalid password.';
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $_SESSION['register_error'] = 'Too many requests. Please try again later.';
        } catch (\PDOException $e) {
            // Loguj detaljnu grešku iz PDOException
            $this->registerLogger->error('Database error during registration', [
                'ip'       => $ipAddress,
                'username' => $username,
                'email'    => $email,
                'error'    => $e->getMessage(),
                'SQLSTATE' => $e->getCode(),
                'time'     => date('Y-m-d H:i:s')
            ]);
            $_SESSION['register_error'] = 'There has been an issue with your request, try again later.';
        } catch (\Exception $e) {
            $this->registerLogger->warning('Failed registration attempt', [
                'ip'       => $ipAddress,
                'username' => $username,
                'email'    => $email,
                'error'    => $e->getMessage(),
                'time'     => date('Y-m-d H:i:s')
            ]);
            $_SESSION['register_error'] = 'There has been an issue with your request, try again later.';
        }

        header('Location: /register');
        exit;
    }



    // Prilagođena validacija šifre
    private function validatePassword(string $password): void
    {
        if (strlen($password) < 6 || strlen($password) > 24) {
            throw new \Exception('Password must be between 6 and 24 characters long.');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
            throw new \Exception('Password can only contain letters (a-z, A-Z) and numbers (0-9).');
        }
    }

    // Method for logging out the user, updating online status, destroying the session,
    // redirecting to the login page and logging the action
    public function logout()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';

        if ($this->auth->isLoggedIn()) {
            // Get user ID before logging out
            $userId = $this->auth->getUserId();
            if ($userId !== null) {
                $this->onlineModifyModel->logout($userId);
            }

            $this->auth->logOut();
            //Dodati da brise sve sesije iz baze!!!!!!!!

            $this->logoutLogger->info('User logged out', [
                'ip'       => $ipAddress,
                'userId'   => $userId,
                'time'     => date('Y-m-d H:i:s')
            ]);

            // Regenerate the CSRF token after logout
            $tokenProvider = $this->csrfVerifier->getTokenProvider();
            if ($tokenProvider instanceof SessionTokenProvider) {
                $tokenProvider->regenerate();
            }

            header('Location: /login');
            exit;
        } else {
            $this->logoutLogger->warning('Unauthorized logout attempt', [
                'ip'   => $ipAddress,
                'time' => date('Y-m-d H:i:s')
            ]);

            header('Location: /login');
            exit;
        }
    }
}
