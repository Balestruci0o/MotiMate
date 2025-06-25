<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$goalId = $_GET['id'] ?? 0;

$goal = $conn->query("SELECT * FROM goals WHERE id = $goalId AND user_id = $userId")->fetch_assoc();

if (!$goal) {
    $_SESSION['error'] = 'Goal not found';
    header("Location: goals.php");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $category = sanitizeInput($_POST['category']);
    $deadline = sanitizeInput($_POST['deadline']);
    
    $update = $conn->query("UPDATE goals SET 
                          title = '$title',
                          description = '$description',
                          category = '$category',
                          deadline = '$deadline'
                          WHERE id = $goalId AND user_id = $userId");
    
    if ($update) {
        $success = 'Goal updated successfully!';
        header("Location: goals.php");
        $goal = $conn->query("SELECT * FROM goals WHERE id = $goalId")->fetch_assoc();
    } else {
        $error = 'Failed to update goal';
    }
}

require_once '../includes/header.php';
?>

<div class="container">
    <h2>Edit Goal</h2>
    
    <?php if ($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($goal['title']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?= htmlspecialchars($goal['description']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="health" <?= $goal['category'] === 'health' ? 'selected' : '' ?>>Health</option>
                <option value="school" <?= $goal['category'] === 'school' ? 'selected' : '' ?>>School</option>
                <option value="personal" <?= $goal['category'] === 'personal' ? 'selected' : '' ?>>Personal</option>
                <option value="work" <?= $goal['category'] === 'work' ? 'selected' : '' ?>>Work</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($goal['deadline']) ?>" required>
        </div>
        
        <button type="submit" class="button">Update Goal</button>
        <a href="view_goal.php?id=<?= $goal['id'] ?>" class="button secondary">Cancel</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>