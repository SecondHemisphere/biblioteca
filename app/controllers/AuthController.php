<?php
class AuthController
{
    private $db;
    private $userModel;

    // Constructor: inicializa la base de datos y el modelo de usuario
    public function __construct($db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    // Muestra la vista de login si el usuario no está autenticado
    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            header('Location: /dashboard'); // Redirige si ya está logueado
            exit;
        }

        $esLogin = true;
        $view = __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Muestra la vista de registro si el usuario no está autenticado
    public function showRegister()
    {
        if ($this->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }

        $esLogin = true;
        $view = __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }

    // Procesa el inicio de sesión
    public function login($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($data['email']);
            $password = trim($data['password']);

            // Verifica las credenciales en el modelo
            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Si es correcto, inicia sesión
                $this->createUserSession($user);
                header('Location: /dashboard');
                exit;
            } else {
                // Si no, muestra mensaje de error y redirige
                $_SESSION['error'] = 'Email o contraseña incorrectos';
                header('Location: /login');
                exit;
            }
        }
    }

    // Procesa el registro de un nuevo usuario
    public function register($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($data['name']);
            $email = trim($data['email']);
            $password = trim($data['password']);
            $confirm_password = trim($data['confirm_password']);

            // Validaciones básicas
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

            // Verifica si el correo ya existe
            if ($this->userModel->findUserByEmail($email)) {
                $_SESSION['error'] = 'El email ya está registrado';
                header('Location: /register');
                exit;
            }

            // Intenta registrar el nuevo usuario
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

    // Cierra la sesión del usuario
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }

    // Crea una sesión para el usuario autenticado
    private function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
    }

    // Verifica si el usuario ha iniciado sesión
    private function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
