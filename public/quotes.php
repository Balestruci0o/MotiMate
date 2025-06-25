<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_quote'])) {
    $quote = sanitizeInput($_POST['quote']);
    $author = sanitizeInput($_POST['author']);
    
    $insert = $conn->query("INSERT INTO quotes (user_id, quote, author) 
                           VALUES ($userId, '$quote', '$author')");
    
    if ($insert) {
        $message = 'Quote added successfully!';
        echo '<script>setTimeout(() => { document.querySelector(".alert-success").classList.add("animate__fadeOut"); }, 3000);</script>';
    } else {
        $message = 'Failed to add quote';
    }
}

$quotes = $conn->query("SELECT * FROM quotes WHERE user_id = $userId OR user_id IS NULL ORDER BY id DESC");

$pageTitle = "Motivational Quotes";
require_once '../includes/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-quote-right"></i> Motivational Quotes</h1>
        <button class="btn btn-accent" id="toggleQuoteForm">
            <i class="fas fa-plus"></i> Add Quote
        </button>
    </div>

    <div class="card mb-4" id="quoteForm" style="display: none;">
        <div class="card-body">
            <?php if ($message): ?>
                <div class="alert alert-<?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?> animate__animated animate__fadeIn">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="quote" class="form-label">Quote Text</label>
                    <textarea id="quote" name="quote" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="author" class="form-label">Author (optional)</label>
                    <input type="text" id="author" name="author" class="form-control">
                </div>
                <button type="submit" name="add_quote" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Quote
                </button>
            </form>
        </div>
    </div>

    <?php if ($quotes->num_rows > 0): ?>
        <div class="quotes-grid">
            <?php while ($quote = $quotes->fetch_assoc()): ?>
                <div class="quote-card card">
                    <div class="quote-text">
                        <i class="fas fa-quote-left quote-icon"></i>
                        <?= htmlspecialchars($quote['quote']) ?>
                    </div>
                    <?php if ($quote['author']): ?>
                        <div class="quote-author">
                            — <?= htmlspecialchars($quote['author']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($quote['user_id'] == $userId): ?>
                        <div class="quote-actions">
                            <form method="post" action="delete_quote.php" onsubmit="return confirm('Are you sure?')">
                                <input type="hidden" name="id" value="<?= $quote['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-text">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card empty-state">
            <div class="text-center py-5">
                <i class="fas fa-quote-right fa-3x mb-3" style="color: var(--primary-light);"></i>
                <h3>No quotes yet</h3>
                <p class="text-muted">Add your first motivational quote to get started!</p>
                <button class="btn btn-primary mt-3" id="toggleEmptyQuoteForm">
                    <i class="fas fa-plus"></i> Add Quote
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.quotes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
}

.quote-card {
    position: relative;
    transition: var(--hover-transition);
    padding: 2rem;
}

.quote-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.quote-icon {
    color: var(--primary-light);
    font-size: 1.5rem;
    margin-right: 0.5rem;
    opacity: 0.7;
}

.quote-text {
    font-size: 1.1rem;
    line-height: 1.7;
    font-style: italic;
    margin-bottom: 1rem;
}

.quote-author {
    text-align: right;
    font-weight: 600;
    color: var(--primary);
}

.quote-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.btn-text {
    background: none;
    border: none;
    color: var(--gray);
    padding: 0.25rem;
}

.btn-text:hover {
    color: var(--danger);
}

.empty-state {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
}
</style>

<script>
// Toggle quote form visibility
document.getElementById('toggleQuoteForm').addEventListener('click', function() {
    const form = document.getElementById('quoteForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        gsap.from(form, {
            opacity: 0,
            y: -20,
            duration: 0.5
        });
    } else {
        gsap.to(form, {
            opacity: 0,
            y: -20,
            duration: 0.3,
            onComplete: () => form.style.display = 'none'
        });
    }
});

document.getElementById('toggleEmptyQuoteForm')?.addEventListener('click', function() {
    document.getElementById('quoteForm').style.display = 'block';
    gsap.from("#quoteForm", {
        opacity: 0,
        y: -20,
        duration: 0.5
    });
    
    document.getElementById('quoteForm').scrollIntoView({
        behavior: 'smooth'
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>