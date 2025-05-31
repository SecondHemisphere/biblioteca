<?php
class DashboardController {
    private $db;
    private $userModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        $data = [
            'title' => 'Dashboard',
            'user' => $user
        ];

        $current_page = 'dashboard';

        $view = __DIR__ . '/../views/dashboard/index.php';
        require_once __DIR__ . '/../views/layouts/layout.php';
    }
    
    private function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>