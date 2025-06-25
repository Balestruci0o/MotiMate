<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $check = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    if ($check->num_rows > 0) {
        $error = 'Username or email already exists';
    } else {
        $insert = $conn->query("INSERT INTO users (username, email, password, created_at) 
                               VALUES ('$username', '$email', '$password', NOW())");
        if ($insert) {
            $success = 'Registration successful! Redirecting...';
            
            $user = $conn->query("SELECT * FROM users WHERE username = '$username'")->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            
        } else {
            $error = 'Registration failed';
        }
    }
}

$pageTitle = "Register";
require_once '../includes/header.php';
?>

<div class="auth-container animate__animated animate__fadeIn">
    <h1 class="text-center mb-4"><i class="fas fa-user-plus"></i> Create Account</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-error animate__animated animate__shakeX">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success animate__animated animate__fadeIn">
            <?= $success ?>
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
            <label for="email" class="form-label">
                <i class="fas fa-envelope"></i> Email
            </label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fas fa-lock"></i> Password
            </label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-user-plus"></i> Register
        </button>
        
        <div class="text-center mt-3">
            <span class="text-muted">Already have an account?</span>
            <a href="login.php" class="link-underline ml-2">Login here</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>