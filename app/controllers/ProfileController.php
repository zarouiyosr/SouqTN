<?php
class ProfileController {

    private function requireClient(): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /SouqTN/public/login");
            exit;
        }
        // L'admin n'a pas d'espace profil client : on le renvoie vers son dashboard.
        if (($_SESSION['role'] ?? '') === 'admin') {
            header("Location: /SouqTN/public/dashboard/admin");
            exit;
        }
    }

    // GET /profile
    public function show(): void {
        $this->requireClient();
        $user    = (new User)->findById($_SESSION['user_id']);
        $success = $_GET['ok']  ?? null;
        $error   = null;
        require __DIR__ . '/../views/dashboard/profile.php';
    }

    // POST /profile/update
    public function update(): void {
        $this->requireClient();
        $userModel = new User;
        $id        = (int)$_SESSION['user_id'];

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password =      $_POST['password'] ?? '';

        $user = $userModel->findById($id);

        if (strlen($username) < 2) {
            $error = "Le nom doit faire au moins 2 caractères";
            $success = null;
            require __DIR__ . '/../views/dashboard/profile.php';
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide";
            $success = null;
            require __DIR__ . '/../views/dashboard/profile.php';
            return;
        }
        if ($userModel->emailExistsExcept($email, $id)) {
            $error = "Cet email est déjà utilisé";
            $success = null;
            require __DIR__ . '/../views/dashboard/profile.php';
            return;
        }
        if (!empty($password) && strlen($password) < 8) {
            $error = "Le mot de passe doit faire au moins 8 caractères";
            $success = null;
            require __DIR__ . '/../views/dashboard/profile.php';
            return;
        }

        $userModel->update($id, $username, $email, $user['role']);
        if (!empty($password)) {
            $userModel->updatePassword($id, $password);
        }

        // Met à jour le nom affiché dans la session
        $_SESSION['username'] = $username;

        header("Location: /SouqTN/public/profile?ok=1");
        exit;
    }
}

