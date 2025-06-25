<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
     header("Location: dashboard.php");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
          
            header("Location: dashboard.php");
                
            exit();
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = 'User not found';
    }
}

$pageTitle = "Login";
require_once '../includes/header.php';
?>

<div class="auth-container animate__animated animate__fadeIn">
    <h1 class="text-center mb-4"><i class="fas fa-sign-in-alt"></i> Welcome Back</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-error animate__animated animate__shakeX">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <form method="post" class="auth-form">
        <div class="form-group">
            <label for="username" class="form-label">
                <i class="fas fa-user"></i> Username
            </label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fas fa-lock"></i> Password
            </label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
        
        <div class="text-center mt-3">
            <span class="text-muted">Don't have an account?</span>
            <a href="register.php" class="link-underline ml-2">Register here</a>
        </div>
    </form>
</div>

<style>
.auth-form {
    max-width: 400px;
    margin: 0 auto;
}

.btn-block {
    display: block;
    width: 100%;
    padding: 1rem;
    font-size: 1.1rem;
}

.text-center {
    text-align: center;
}

.mt-3 {
    margin-top: 1.5rem;
}

.ml-2 {
    margin-left: 0.5rem;
}

.text-muted {
    color: var(--gray);
}

.fade-out {
    opacity: 0;
    transition: opacity 0.5s ease;
}
</style>

<?php require_once '../includes/footer.php'; ?>