<?php
include("database/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | LifeFlow Blood Bank</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            background: radial-gradient(circle at 20% 50%, rgba(199, 54, 43, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(199, 54, 43, 0.05) 0%, transparent 50%);
            z-index: -2;
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

        .nav-links a:hover, .nav-links a.active {
            color: #c7362b;
        }

        .btn-login {
            background: linear-gradient(135deg, #c7362b, #a1241a);
            padding: 10px 28px;
            border-radius: 40px;
            color: white !important;
        }

        /* Hero Section */
        .page-hero {
            min-height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 140px 20px 60px;
        }

        .page-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: fadeInUp 0.8s ease;
        }

        .page-hero p {
            color: #b0b0b0;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease 0.2s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sections */
        .section {
            padding: 80px 10%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .section-title p {
            color: #b0b0b0;
        }

        /* Mission Vision Grid */
        .mission-vision {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .mv-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mv-card:hover {
            transform: translateY(-10px);
            border-color: rgba(199, 54, 43, 0.5);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .mv-card i {
            font-size: 3rem;
            color: #c7362b;
            margin-bottom: 20px;
        }

        .mv-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .mv-card p {
            color: #b0b0b0;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-wrapper {
            background: linear-gradient(135deg, rgba(199, 54, 43, 0.1), rgba(199, 54, 43, 0.05));
            border-radius: 40px;
            padding: 60px;
            margin: 40px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 3rem;
            color: #c7362b;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .stat-item p {
            color: #b0b0b0;
        }

        /* Timeline */
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline-item {
            padding: 25px 30px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            margin-bottom: 20px;
            position: relative;
            border-left: 4px solid #c7362b;
            transition: 0.3s;
        }

        .timeline-item:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.08);
        }

        .timeline-year {
            font-size: 1.5rem;
            font-weight: 800;
            color: #c7362b;
            margin-bottom: 10px;
        }

        /* Values Grid */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }

        .value-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: 0.3s;
        }

        .value-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
        }

        .value-card i {
            font-size: 2.5rem;
            color: #c7362b;
            margin-bottom: 15px;
        }

        .value-card h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .value-card p {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        /* Team Grid */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .team-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: 0.3s;
        }

        .team-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #c7362b, #a1241a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .team-avatar i {
            font-size: 3rem;
            color: white;
        }

        .team-card h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .team-card .role {
            color: #c7362b;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .team-social {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .team-social a {
            color: #b0b0b0;
            transition: 0.3s;
        }

        .team-social a:hover {
            color: #c7362b;
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

        /* Floating Home Button */
        .floating-home {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #c7362b, #a1241a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            z-index: 9999;
            animation: pulse 2s infinite;
        }

        .floating-home:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(199, 54, 43, 0.5);
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(199, 54, 43, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(199, 54, 43, 0); }
            100% { box-shadow: 0 0 0 0 rgba(199, 54, 43, 0); }
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
            .page-hero h1 {
                font-size: 2rem;
            }
            .section {
                padding: 50px 20px;
            }
            .stats-wrapper {
                padding: 30px;
            }
            .stat-item h3 {
                font-size: 2rem;
            }
            .floating-home {
                width: 45px;
                height: 45px;
                bottom: 20px;
                right: 20px;
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
        <a href="about.php" class="active">About</a>
        <a href="contact.php">Contact</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard/<?php echo $_SESSION['role']; ?>.php" class="btn-login">Dashboard</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Login</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Hero Section -->
<section class="page-hero">
    <div>
        <h1>About LifeFlow</h1>
        <p>Revolutionizing blood donation management with technology and compassion</p>
    </div>
</section>

<!-- Mission & Vision -->
<div class="section">
    <div class="mission-vision">
        <div class="mv-card" data-aos="fade-right">
            <i class="fas fa-bullseye"></i>
            <h3>Our Mission</h3>
            <p>To provide a seamless, efficient, and life-saving blood donation platform that connects donors with those in need, ensuring no life is lost due to blood shortage.</p>
        </div>
        <div class="mv-card" data-aos="fade-left">
            <i class="fas fa-eye"></i>
            <h3>Our Vision</h3>
            <p>To create a world where blood is available instantly to every patient in need, powered by technology and driven by humanity.</p>
        </div>
    </div>
</div>

<!-- Our Journey -->
<div class="section">
    <div class="section-title" data-aos="fade-up">
        <h2>Our Journey</h2>
        <p>From a small initiative to a nationwide network</p>
    </div>
    <div class="timeline">
        <div class="timeline-item" data-aos="fade-up">
            <div class="timeline-year">2020</div>
            <p>Founded with a mission to digitize blood donation management in local communities. Started with just 50 donors and 2 partner hospitals.</p>
        </div>
        <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
            <div class="timeline-year">2022</div>
            <p>Expanded to serve 20+ hospitals and registered 1000+ active donors. Launched our first mobile app for easy donor registration.</p>
        </div>
        <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
            <div class="timeline-year">2024</div>
            <p>Launched advanced analytics and real-time stock management system. Reached milestone of 2000+ lives saved.</p>
        </div>
        <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
            <div class="timeline-year">2026</div>
            <p>Revolutionizing blood banking with AI-powered predictions, real-time tracking, and seamless connectivity across the nation.</p>
        </div>
    </div>
</div>

<!-- Impact Stats -->
<div class="section">
    <div class="stats-wrapper" data-aos="fade-up">
        <div class="stats-grid">
            <div class="stat-item">
                <h3 class="counter" data-target="2530">0</h3>
                <p>Active Donors</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="48">0</h3>
                <p>Partner Hospitals</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="1248">0</h3>
                <p>Lives Saved</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="315">0</h3>
                <p>Volunteers</p>
            </div>
        </div>
    </div>
</div>

<!-- Core Values -->
<div class="section">
    <div class="section-title" data-aos="fade-up">
        <h2>Our Core Values</h2>
        <p>The principles that guide everything we do</p>
    </div>
    <div class="values-grid">
        <div class="value-card" data-aos="fade-up">
            <i class="fas fa-heart"></i>
            <h4>Compassion</h4>
            <p>We put humanity first in every decision we make</p>
        </div>
        <div class="value-card" data-aos="fade-up" data-aos-delay="100">
            <i class="fas fa-shield-alt"></i>
            <h4>Integrity</h4>
            <p>Transparent and ethical in all our operations</p>
        </div>
        <div class="value-card" data-aos="fade-up" data-aos-delay="200">
            <i class="fas fa-rocket"></i>
            <h4>Innovation</h4>
            <p>Leveraging technology to save more lives</p>
        </div>
        <div class="value-card" data-aos="fade-up" data-aos-delay="300">
            <i class="fas fa-users"></i>
            <h4>Community</h4>
            <p>Building a network of caring individuals</p>
        </div>
        <div class="value-card" data-aos="fade-up" data-aos-delay="400">
            <i class="fas fa-trophy"></i>
            <h4>Excellence</h4>
            <p>Striving for the highest quality in service</p>
        </div>
        <div class="value-card" data-aos="fade-up" data-aos-delay="500">
            <i class="fas fa-handshake"></i>
            <h4>Partnership</h4>
            <p>Collaborating for greater impact</p>
        </div>
    </div>
</div>

<!-- Leadership Team -->
<div class="section">
    <div class="section-title" data-aos="fade-up">
        <h2>Leadership Team</h2>
        <p>Meet the dedicated individuals behind LifeFlow</p>
    </div>
    <div class="team-grid">
        <div class="team-card" data-aos="fade-up">
            <div class="team-avatar">
                <i class="fas fa-user-tie"></i>
            </div>
            <h4>Dr. Sarah Johnson</h4>
            <div class="role">Founder & CEO</div>
            <div class="team-social">
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
        <div class="team-card" data-aos="fade-up" data-aos-delay="100">
            <div class="team-avatar">
                <i class="fas fa-user-md"></i>
            </div>
            <h4>Dr. Michael Chen</h4>
            <div class="role">Medical Director</div>
            <div class="team-social">
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
        <div class="team-card" data-aos="fade-up" data-aos-delay="200">
            <div class="team-avatar">
                <i class="fas fa-laptop-code"></i>
            </div>
            <h4>Emily Rodriguez</h4>
            <div class="role">Chief Technology Officer</div>
            <div class="team-social">
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
            </div>
        </div>
        <div class="team-card" data-aos="fade-up" data-aos-delay="300">
            <div class="team-avatar">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4>David Kim</h4>
            <div class="role">Operations Director</div>
            <div class="team-social">
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </div>
</div>

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
        <p>&copy; 2026 LifeFlow Blood Bank System | Saving Lives Every Day</p>
    </div>
</footer>

<!-- Floating Home Button -->
<a href="index.php" class="floating-home">
    <i class="fas fa-home"></i>
</a>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });

    // Counter animation
    const counters = document.querySelectorAll('.counter');
    const statsSection = document.querySelector('.stats-wrapper');
    
    let started = false;
    
    function startCounters() {
        if (started) return;
        started = true;
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            let current = 0;
            const increment = target / 50;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.innerText = Math.ceil(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target;
                }
            };
            updateCounter();
        });
    }
    
    // Intersection Observer for counters
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                startCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    
    if (statsSection) observer.observe(statsSection);
</script>
</body>
</html>