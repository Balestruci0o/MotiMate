        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">
                        <i class="fas fa-brain"></i> MotiMate
                    </div>
                    <p class="footer-text">
                        Your personal motivation companion. Track your goals, reflect on your days, and stay inspired with motivational quotes.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="goals.php">Goals</a></li>
                        <li><a href="daily_log.php">Daily Log</a></li>
                        <li><a href="quotes.php">Quotes</a></li>
                        <li><a href="stats.php">Statistics</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> MotiMate. All rights reserved. | PIT II.A School Project</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script>
        const header = document.querySelector('.header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            gsap.utils.toArray('.card, .stats-card, .quote-box').forEach((el, i) => {
                gsap.from(el, {
                    opacity: 0,
                    y: 50,
                    duration: 0.8,
                    delay: i * 0.1,
                    ease: "power3.out"
                });
            });

            gsap.to(".floating", {
                y: -10,
                duration: 2,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });

            gsap.to(".pulse", {
                scale: 1.05,
                duration: 1,
                repeat: -1,
                yoyo: true,
                ease: "power1.inOut"
            });
        });

        const ratingItems = document.querySelectorAll('.rating-item');
        if (ratingItems) {
            ratingItems.forEach(item => {
                item.addEventListener('click', function() {
                    ratingItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    gsap.fromTo(this.querySelector('.rating-circle'), 
                        { scale: 0.8, opacity: 0.5 },
                        { scale: 1.1, opacity: 1, duration: 0.5, ease: "elastic.out(1, 0.5)" }
                    );
                });
            });
        }
    </script>
</body>
</html>