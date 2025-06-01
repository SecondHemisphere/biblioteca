<?php
class AuthController {
    private $db;
    private $userModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
    }
    
    public function showLogin() {
        if ($this->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }

        $esLogin = true;
        $view = __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }
    
    public function showRegister() {
        if ($this->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }

        $esLogin = true;
        $view = __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }
    
    public function login($data) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($data['email']);
            $password = trim($data['password']);
            
            $user = $this->userModel->login($email, $password);
            
            if ($user) {
                $this->createUserSession($user);
                header('Location: /dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Email o contraseña incorrectos';
                header('Location: /login');
                exit;
            }
        }
    }
    
    public function register($data) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($data['name']);
            $email = trim($data['email']);
            $password = trim($data['password']);
            $confirm_password = trim($data['confirm_password']);
            
            // Validaciones
            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'Por favor complete todos los campos';
                header('Location: /register');
                exit;
            }
            
            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Las contraseñas no coinciden';
                header('Location: /register');
                exit;
            }
            
            if ($this->userModel->findUserByEmail($email)) {
                $_SESSION['error'] = 'El email ya está registrado';
                header('Location: /register');
                exit;
            }
            
            if ($this->userModel->register([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ])) {
                $_SESSION['success'] = 'Registro exitoso. Por favor inicie sesión';
                header('Location: /login');
                exit;
            } else {
                $_SESSION['error'] = 'Algo salió mal. Intente nuevamente';
                header('Location: /register');
                exit;
            }
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }
    
    private function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
    }
    
    private function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>