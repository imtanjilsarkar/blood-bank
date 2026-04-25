<?php
session_start();
include("database/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeFlow | Next-Gen Blood Bank System 2026</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(199, 54, 43, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(199, 54, 43, 0.08) 0%, transparent 50%);
            z-index: -2;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(45deg, 
                rgba(199, 54, 43, 0.02) 0px, 
                rgba(199, 54, 43, 0.02) 2px,
                transparent 2px,
                transparent 8px);
            animation: moveBg 20s linear infinite;
        }

        @keyframes moveBg {
            0% { transform: translate(-10%, -10%) rotate(0deg); }
            100% { transform: translate(10%, 10%) rotate(360deg); }
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 20px;
            left: 5%;
            right: 5%;
            width: 90%;
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 80px;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .logo i {
            background: none;
            color: #c7362b;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #e0e0e0;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #c7362b;
        }

        .btn-login {
            background: linear-gradient(135deg, #c7362b, #a1241a);
            padding: 10px 28px;
            border-radius: 40px;
            color: white !important;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10%;
            position: relative;
        }

        .hero-content {
            flex: 1;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(199, 54, 43, 0.2);
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: 1px solid rgba(199, 54, 43, 0.3);
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 1.2rem;
            color: #b0b0b0;
            margin-bottom: 30px;
        }

        .btn-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #c7362b, #a1241a);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(199, 54, 43, 0.4);
        }

        .btn-outline {
            border: 2px solid #c7362b;
            color: white;
            background: transparent;
        }

        .btn-outline:hover {
            background: rgba(199, 54, 43, 0.1);
            transform: translateY(-3px);
        }

        .hero-stats {
            flex: 1;
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .stat-circle {
            text-align: center;
        }

        .circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(199, 54, 43, 0.2), rgba(199, 54, 43, 0.05));
            border: 2px solid rgba(199, 54, 43, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        /* Sections */
        .section {
            padding: 80px 10%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .section-title p {
            color: #b0b0b0;
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
            text-align: center;
        }

        .glass-card:hover {
            transform: translateY(-10px);
            border-color: rgba(199, 54, 43, 0.5);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .glass-card i {
            font-size: 3rem;
            color: #c7362b;
            margin-bottom: 20px;
        }

        .glass-card h3 {
            margin-bottom: 15px;
        }

        .glass-card p {
            color: #b0b0b0;
            line-height: 1.6;
        }

        /* Blood Stock Table */
        .stock-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: rgba(199, 54, 43, 0.3);
            padding: 15px;
            text-align: center;
            font-weight: 600;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, rgba(199, 54, 43, 0.1), rgba(199, 54, 43, 0.05));
            border-radius: 40px;
            margin: 40px 10%;
            padding: 60px;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .cta-section p {
            color: #b0b0b0;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        .footer {
            background: #050505;
            padding: 60px 10% 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 60px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-section p {
            color: #b0b0b0;
            line-height: 1.8;
        }

        .footer-section a {
            color: #b0b0b0;
            text-decoration: none;
            transition: 0.3s;
        }

        .footer-section a:hover {
            color: #c7362b;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #b0b0b0;
        }

        /* Floating Home Button (hidden on homepage) */
        .floating-home {
            display: none;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 20px;
            }
            .nav-links {
                gap: 15px;
            }
            .nav-links a {
                font-size: 0.9rem;
            }
            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 120px;
            }
            .hero-content h1 {
                font-size: 2.5rem;
            }
            .btn-group {
                justify-content: center;
            }
            .hero-stats {
                margin-top: 50px;
            }
            .section {
                padding: 50px 20px;
            }
            .cta-section {
                margin: 40px 20px;
                padding: 40px 20px;
            }
            .section-title h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<div class="bg-animation"></div>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <i class="fas fa-droplet"></i> LifeFlow
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard/<?php echo $_SESSION['role']; ?>.php" class="btn-login">Dashboard</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Login</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content" data-aos="fade-right">
        <div class="hero-badge">
            <i class="fas fa-heartbeat"></i> 24/7 Emergency Support
        </div>
        <h1>Donate Blood,<br>Save Lives Instantly</h1>
        <p>Join the future of blood donation management. Every drop counts, every donor is a hero.</p>
        <div class="btn-group">
            <a href="register.php" class="btn btn-primary"><i class="fas fa-hand-holding-heart"></i> Become a Donor</a>
            <a href="login.php" class="btn btn-outline"><i class="fas fa-hospital-user"></i> Hospital Login</a>
            <a href="about.php" class="btn btn-outline"><i class="fas fa-info-circle"></i> Learn More</a>
        </div>
    </div>
    <div class="hero-stats" data-aos="fade-left">
        <div class="stat-circle">
            <div class="circle">1248+</div>
            <p>Lives Saved</p>
        </div>
        <div class="stat-circle">
            <div class="circle">2530+</div>
            <p>Active Donors</p>
        </div>
        <div class="stat-circle">
            <div class="circle">48+</div>
            <p>Hospitals</p>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section" id="features">
    <div class="section-title" data-aos="fade-up">
        <h2>Why Choose LifeFlow?</h2>
        <p>Next-generation blood bank management system for 2026</p>
    </div>
    <div class="cards-grid">
        <div class="glass-card" data-aos="fade-up">
            <i class="fas fa-bolt"></i>
            <h3>Real-time Updates</h3>
            <p>Instant blood stock updates and request tracking with live notifications</p>
        </div>
        <div class="glass-card" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-shield-alt"></i>
            <h3>Secure System</h3>
            <p>Role-based access control for donors, hospitals & admin with encrypted data</p>
        </div>
        <div class="glass-card" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-chart-line"></i>
            <h3>Analytics Dashboard</h3>
            <p>Comprehensive insights, reports, and predictive analytics for better management</p>
        </div>
        <div class="glass-card" data-aos="fade-up" data-aos-delay="300">
            <i class="fas fa-mobile-alt"></i>
            <h3>Mobile Ready</h3>
            <p>Fully responsive design that works perfectly on all devices</p>
        </div>
    </div>
</section>

<!-- Blood Stock Section -->
<section class="section">
    <div class="section-title" data-aos="fade-up">
        <h2>Current Blood Stock</h2>
        <p>Real-time availability across all blood groups</p>
    </div>
    <div class="stock-container" data-aos="fade-up">
        <table>
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Units Available</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM blood_stock ORDER BY FIELD(blood_group, 'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-')");
                while($row = mysqli_fetch_assoc($result)) {
                    $statusClass = '';
                    $statusText = '';
                    if($row['units_available'] > 10) {
                        $statusText = '✅ Available';
                        $statusClass = 'style="color: #2ecc71;"';
                    } elseif($row['units_available'] > 0) {
                        $statusText = '⚠️ Low Stock';
                        $statusClass = 'style="color: #f39c12;"';
                    } else {
                        $statusText = '❌ Critical';
                        $statusClass = 'style="color: #e74c3c;"';
                    }
                    echo "<tr>
                            <td><strong>{$row['blood_group']}</strong></td>
                            <td>{$row['units_available']}</td>
                            <td $statusClass>$statusText</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" data-aos="fade-up">
    <h2>Ready to Make a Difference?</h2>
    <p>Join thousands of donors who are saving lives every day. Your donation can save up to 3 lives!</p>
    <div class="btn-group" style="justify-content: center;">
        <a href="register.php" class="btn btn-primary"><i class="fas fa-hand-holding-heart"></i> Become a Donor</a>
        <a href="contact.php" class="btn btn-outline"><i class="fas fa-envelope"></i> Contact Us</a>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3><i class="fas fa-droplet"></i> LifeFlow</h3>
            <p>Bridging donors & emergencies with cutting-edge technology. Saving lives, one donation at a time.</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <p><a href="index.php">🏠 Home</a></p>
            <p><a href="about.php">📖 About Us</a></p>
            <p><a href="contact.php">📞 Contact</a></p>
            <p><a href="login.php">🔐 Login</a></p>
            <p><a href="register.php">📝 Register</a></p>
        </div>
        <div class="footer-section">
            <h3>Contact Info</h3>
            <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
            <p><i class="fas fa-envelope"></i> info@lifeflow.com</p>
            <p><i class="fas fa-map-marker-alt"></i> 123 Health Street, Medical City</p>
        </div>
        <div class="footer-section">
            <h3>Follow Us</h3>
            <p><i class="fab fa-facebook"></i> <a href="#"> Facebook</a></p>
            <p><i class="fab fa-twitter"></i> <a href="#"> Twitter</a></p>
            <p><i class="fab fa-instagram"></i> <a href="#"> Instagram</a></p>
            <p><i class="fab fa-linkedin"></i> <a href="#"> LinkedIn</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 LifeFlow Blood Bank System | Saving Lives Every Day | Version 2.0</p>
    </div>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Smooth scroll for anchor links (if any)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add active class to current nav link
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage || (currentPage === '' && linkPage === 'index.php')) {
            link.style.color = '#c7362b';
        }
    });
</script>
</body>
</html>