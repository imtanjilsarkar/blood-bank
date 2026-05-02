<?php
session_start();
include("database/connection.php");

// Handle contact form submission
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Create contact_messages table if not exists
    $createTable = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $createTable);
    
    // Insert message
    $query = "INSERT INTO contact_messages (name, email, subject, message) 
              VALUES ('$name', '$email', '$subject', '$message')";
    
    if (mysqli_query($conn, $query)) {
        $success = "Thank you for contacting us! We'll get back to you within 24 hours.";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | LifeFlow Blood Bank</title>
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
            min-height: 40vh;
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

        /* Contact Section */
        .contact-section {
            padding: 0 10% 80px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }

        /* Contact Info Cards */
        .info-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-card:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(199, 54, 43, 0.3);
        }

        .info-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, rgba(199, 54, 43, 0.2), rgba(199, 54, 43, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-icon i {
            font-size: 1.5rem;
            color: #c7362b;
        }

        .info-content h3 {
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .info-content p {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        /* Map */
        .map-container {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            overflow: hidden;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .map-container iframe {
            width: 100%;
            height: 250px;
            border: none;
        }

        /* Contact Form */
        .form-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-container h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #c7362b;
            background: rgba(255, 255, 255, 0.15);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-group select option {
            background: #1a1a1a;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #c7362b, #a1241a);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(199, 54, 43, 0.4);
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.2);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        /* Footer */
        .footer {
            background: #050505;
            padding: 60px 10% 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
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

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .social-links a:hover {
            background: #c7362b;
            transform: translateY(-3px);
        }

        .social-links a i {
            font-size: 1.2rem;
            color: white;
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
            .contact-section {
                padding: 0 20px 60px;
            }
            .contact-grid {
                grid-template-columns: 1fr;
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
        <a href="about.php">About</a>
        <a href="contact.php" class="active">Contact</a>
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
        <h1>Get in Touch</h1>
        <p>Have questions? We are here to help. Reach out to us anytime.</p>
    </div>
</section>

<!-- Contact Section -->
<div class="contact-section">
    <div class="contact-grid">
        <!-- Left Side - Contact Info -->
        <div data-aos="fade-right">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h3>Visit Us</h3>
                    <p>Basundhara, Dhaka - 1200, Bangladesh</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h3>Email Me</h3>
                    <p>im.tanjilsarkar@gmail.com</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fab fa-facebook-f"></i>
                </div>
                <div class="info-content">
                    <h3>Facebook</h3>
                    <p><a href="https://www.facebook.com/tanjilsarkar123" target="_blank" style="color: #b0b0b0;">facebook.com/tanjilsarkar123</a></p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fab fa-instagram"></i>
                </div>
                <div class="info-content">
                    <h3>Instagram</h3>
                    <p><a href="https://www.instagram.com/tanjilsarkar_" target="_blank" style="color: #b0b0b0;">instagram.com/tanjilsarkar_</a></p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fab fa-linkedin-in"></i>
                </div>
                <div class="info-content">
                    <h3>LinkedIn</h3>
                    <p><a href="https://www.linkedin.com/in/tanji-sarkar/" target="_blank" style="color: #b0b0b0;">linkedin.com/in/tanjilsarkar</a></p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fab fa-github"></i>
                </div>
                <div class="info-content">
                    <h3>GitHub</h3>
                    <p><a href="https://github.com/imtanjilsarkar" target="_blank" style="color: #b0b0b0;">github.com/imtanjilsarkar</a></p>
                </div>
            </div>

            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14603.467184580228!2d90.38495935!3d23.82172245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c732e6e9dda7%3A0xfe5e08f5a7e71c3!2sBasundhara%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1699999999999!5m2!1sen!2sbd" 
                        allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>

        <!-- Right Side - Contact Form -->
        <div data-aos="fade-left">
            <div class="form-container">
                <h2><i class="fas fa-paper-plane"></i> Send me a Message</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= $success ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" id="contactForm">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <select name="subject" required>
                            <option value="">Select Subject</option>
                            <option value="General Inquiry">General Inquiry</option>
                            <option value="Donation Related">Donation Related</option>
                            <option value="Hospital Partnership">Hospital Partnership</option>
                            <option value="Technical Support">Technical Support</option>
                            <option value="Feedback">Feedback & Suggestion</option>
                            <option value="Emergency">Emergency Request</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Your Message..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3><i class="fas fa-droplet"></i> LifeFlow</h3>
            <p>Bridging donors and emergencies with cutting-edge technology. Saving lives, one donation at a time.</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <p><a href="index.php">Home</a></p>
            <p><a href="about.php">About Us</a></p>
            <p><a href="contact.php">Contact</a></p>
        </div>
        <div class="footer-section">
            <h3>Contact Info</h3>
            <p><i class="fas fa-map-marker-alt"></i> Basundhara, Dhaka - 1200</p>
            <p><i class="fas fa-envelope"></i> im.tanjilsarkar@gmail.com</p>
        </div>
        <div class="footer-section">
            <h3>Connect With Me</h3>
            <div class="social-links">
                <a href="https://www.facebook.com/tanjilsarkar123" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/tanjilsarkar_" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/in/tanji-sarkar/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://github.com/imtanjilsarkar" target="_blank"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 LifeFlow Blood Bank System | Developed by Tanjil Sarkar | Saving Lives Every Day</p>
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

    // Form validation
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        const name = this.querySelector('[name="name"]').value.trim();
        const email = this.querySelector('[name="email"]').value.trim();
        const subject = this.querySelector('[name="subject"]').value;
        const message = this.querySelector('[name="message"]').value.trim();
        
        if (!name || !email || !subject || !message) {
            e.preventDefault();
            alert('Please fill in all fields');
        } else if (!email.includes('@') || !email.includes('.')) {
            e.preventDefault();
            alert('Please enter a valid email address');
        }
    });
</script>
</body>
</html>