<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $category = sanitizeInput($_POST['category']);
    $deadline = sanitizeInput($_POST['deadline']);
    
    $insert = $conn->query("INSERT INTO goals (user_id, title, description, category, deadline, is_done, created_at) 
                           VALUES ($userId, '$title', '$description', '$category', '$deadline', 0, NOW())");
    
    if ($insert) {
        $success = 'Goal added successfully!';
        header("Location: goals.php");
    } else {
        $error = 'Failed to add goal';
    }
}

$pageTitle = "Add New Goal";
require_once '../includes/header.php';
?>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4"><i class="fas fa-bullseye"></i> Add New Goal</h1>
            
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
            
            <form method="post" class="goal-form">
                <div class="form-group">
                    <label for="title" class="form-label">Goal Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description (optional)</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">Select category</option>
                            <option value="health">Health</option>
                            <option value="school">School</option>
                            <option value="personal">Personal</option>
                            <option value="work">Work</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="date" id="deadline" name="deadline" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Goal
                    </button>
                    <a href="goals.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.goal-form {
    max-width: 800px;
    margin: 0 auto;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.form-group.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 15px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .form-group.col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
</style>

<script>

gsap.from(".goal-form", {
    opacity: 0,
    y: 20,
    duration: 0.8,
    delay: 0.3
});
</script>

<?php require_once '../includes/footer.php'; ?>