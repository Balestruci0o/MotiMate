<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
}

$userId = $_SESSION['user_id'];
$pageTitle = "Your Statistics";

$stats = [
    'total_goals' => $conn->query("SELECT COUNT(*) as count FROM goals WHERE user_id = $userId")->fetch_assoc()['count'],
    'completed_goals' => $conn->query("SELECT COUNT(*) as count FROM goals WHERE user_id = $userId AND is_done = 1")->fetch_assoc()['count'],
    'avg_rating' => $conn->query("SELECT AVG(rating) as avg FROM daily_logs WHERE user_id = $userId")->fetch_assoc()['avg'],
    'best_day' => $conn->query("SELECT log_date, rating FROM daily_logs WHERE user_id = $userId ORDER BY rating DESC LIMIT 1")->fetch_assoc()
];

$weeklyData = $conn->query("SELECT DAYNAME(log_date) as day, AVG(rating) as avg_rating 
                           FROM daily_logs 
                           WHERE user_id = $userId AND log_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                           GROUP BY DAYOFWEEK(log_date)")->fetch_all(MYSQLI_ASSOC);

$monthlyData = $conn->query("SELECT DATE_FORMAT(log_date, '%Y-%m-%d') as date, rating 
                            FROM daily_logs 
                            WHERE user_id = $userId AND log_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                            ORDER BY log_date")->fetch_all(MYSQLI_ASSOC);

$goalProgress = $conn->query("SELECT category, COUNT(*) as total, 
                             SUM(is_done) as completed 
                             FROM goals 
                             WHERE user_id = $userId 
                             GROUP BY category")->fetch_all(MYSQLI_ASSOC);

require_once '../includes/header.php';
?>

<div class="stats-header">
    <h1 class="animate__animated animate__fadeInDown"><i class="fas fa-chart-line"></i> Your Statistics</h1>
    <p class="animate__animated animate__fadeInDown animate__delay-1s">Track your progress and motivation trends</p>
</div>

<div class="stats-highlights">
    <div class="stats-card pulse">
        <div class="stats-icon">
            <i class="fas fa-bullseye"></i>
        </div>
        <div class="stats-value"><?= $stats['total_goals'] ?></div>
        <div class="stats-label">Total Goals</div>
    </div>
    
    <div class="stats-card pulse" style="animation-delay: 0.2s;">
        <div class="stats-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stats-value"><?= $stats['completed_goals'] ?></div>
        <div class="stats-label">Completed</div>
    </div>
    
    <div class="stats-card pulse" style="animation-delay: 0.4s;">
        <div class="stats-icon">
            <i class="fas fa-star"></i>
        </div>
        <div class="stats-value"><?= round($stats['avg_rating'], 1) ?: 'N/A' ?></div>
        <div class="stats-label">Avg. Rating</div>
    </div>
    
    <div class="stats-card pulse" style="animation-delay: 0.6s;">
        <div class="stats-icon">
            <i class="fas fa-trophy"></i>
        </div>
        <div class="stats-value"><?= $stats['best_day']['rating'] ?? 'N/A' ?></div>
        <div class="stats-label">Best Day</div>
        <?php if ($stats['best_day']): ?>
            <div class="stats-subtext"><?= date('M j, Y', strtotime($stats['best_day']['log_date'])) ?></div>
        <?php endif; ?>
    </div>
</div>

<div class="chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <h2><i class="fas fa-calendar-week"></i> Weekly Mood Trend</h2>
            <p>Your average daily ratings for the past week</p>
        </div>
        <div class="chart-container">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h2><i class="fas fa-calendar-alt"></i> Monthly Progress</h2>
            <p>Daily ratings for the past 30 days</p>
        </div>
        <div class="chart-container">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h2><i class="fas fa-tasks"></i> Goals Progress</h2>
            <p>Completion rate by category</p>
        </div>
        <div class="chart-container">
            <canvas id="goalsChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h2><i class="fas fa-heartbeat"></i> Motivation Insights</h2>
            <p>Your personal patterns</p>
        </div>
        <div class="insights-container">
            <?php if ($stats['avg_rating'] > 7): ?>
                <div class="insight positive">
                    <i class="fas fa-smile-beam"></i>
                    <h3>You're doing great!</h3>
                    <p>Your average rating is high. Keep up the positive mindset!</p>
                </div>
            <?php elseif ($stats['avg_rating'] < 5): ?>
                <div class="insight negative">
                    <i class="fas fa-cloud-rain"></i>
                    <h3>Room for improvement</h3>
                    <p>Your average rating is low. Try adding more positive activities to your routine.</p>
                </div>
            <?php else: ?>
                <div class="insight neutral">
                    <i class="fas fa-meh"></i>
                    <h3>Steady progress</h3>
                    <p>Your motivation is average. Small changes can lead to big improvements!</p>
                </div>
            <?php endif; ?>
            
            <?php if ($stats['completed_goals'] / max(1, $stats['total_goals']) > 0.7): ?>
                <div class="insight positive">
                    <i class="fas fa-check-double"></i>
                    <h3>Goal crusher!</h3>
                    <p>You complete most of your goals. That's amazing!</p>
                </div>
            <?php endif; ?>
            
            <?php if (count($weeklyData) > 0): 
                $bestDay = array_reduce($weeklyData, function($a, $b) {
                    return $a['avg_rating'] > $b['avg_rating'] ? $a : $b;
                }, $weeklyData[0]); ?>
                <div class="insight highlight">
                    <i class="fas fa-calendar-day"></i>
                    <h3>Best weekday: <?= $bestDay['day'] ?></h3>
                    <p>Your average rating is <?= round($bestDay['avg_rating'], 1) ?> on this day.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    const weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Average Rating',
                data: [0, 0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(108, 92, 231, 0.7)',
                borderColor: 'rgba(108, 92, 231, 1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    cornerRadius: 8,
                    padding: 12
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    const weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    <?php if (!empty($weeklyData)): ?>
        const weeklyData = <?= json_encode($weeklyData) ?>;
        weeklyData.forEach(item => {
            const index = weekDays.indexOf(item.day);
            if (index !== -1) {
                weeklyChart.data.datasets[0].data[index] = parseFloat(item.avg_rating);
            }
        });
        weeklyChart.update();
    <?php endif; ?>

    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: Array(30).fill('').map((_, i) => {
                const d = new Date();
                d.setDate(d.getDate() - (29 - i));
                return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Daily Rating',
                data: Array(30).fill(null),
                backgroundColor: 'rgba(0, 206, 201, 0.1)',
                borderColor: 'rgba(0, 206, 201, 1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'rgba(108, 92, 231, 1)',
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    <?php if (!empty($monthlyData)): ?>
        const monthlyData = <?= json_encode($monthlyData) ?>;
        const labels = monthlyChart.data.labels;
        const last30Days = labels.map(label => {
            const parts = label.split(' ');
            return `${parts[1]} ${parts[0]}`; 
        });
        
        monthlyData.forEach(item => {
            const date = new Date(item.date);
            const formattedDate = `${date.getDate()} ${date.toLocaleDateString('en-US', { month: 'short' })}`;
            const index = last30Days.indexOf(formattedDate);
            if (index !== -1) {
                monthlyChart.data.datasets[0].data[index] = parseInt(item.rating);
            }
        });
        monthlyChart.update();
    <?php endif; ?>

    const goalsCtx = document.getElementById('goalsChart').getContext('2d');
    const goalsChart = new Chart(goalsCtx, {
        type: 'radar',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Completed Goals',
                    data: [],
                    backgroundColor: 'rgba(108, 92, 231, 0.2)',
                    borderColor: 'rgba(108, 92, 231, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(108, 92, 231, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 8
                },
                {
                    label: 'Total Goals',
                    data: [],
                    backgroundColor: 'rgba(0, 206, 201, 0.2)',
                    borderColor: 'rgba(0, 206, 201, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(0, 206, 201, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    angleLines: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    suggestedMin: 0,
                    ticks: {
                        display: false,
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            },
            elements: {
                line: {
                    tension: 0.1
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    <?php if (!empty($goalProgress)): ?>
        const goalData = <?= json_encode($goalProgress) ?>;
        const categories = [];
        const completed = [];
        const total = [];
        
        goalData.forEach(item => {
            categories.push(item.category.charAt(0).toUpperCase() + item.category.slice(1));
            completed.push(parseInt(item.completed));
            total.push(parseInt(item.total));
        });
        
        const maxValue = Math.max(...total) + 1;
        
        goalsChart.data.labels = categories;
        goalsChart.data.datasets[0].data = completed;
        goalsChart.data.datasets[1].data = total;
        goalsChart.options.scales.r.suggestedMax = maxValue;
        goalsChart.update();
    <?php endif; ?>

    gsap.utils.toArray(".chart-card").forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top 80%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 0.8,
            delay: i * 0.1,
            ease: "power3.out"
        });
    });
});
</script>

<style>
.chart-container {
    position: relative;
    height: 350px;
    width: 100%;
}

canvas {
    animation: fadeIn 1s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Additional styles for the radar chart */
.radar-chart-container {
    max-width: 100%;
    margin: 0 auto;
}
</style>

<?php require_once '../includes/footer.php'; ?>