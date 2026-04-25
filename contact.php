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

        /* FAQ Section */
        .faq-section {
            padding: 80px 10%;
            background: rgba(199, 54, 43, 0.03);
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-top: 50px;
        }

        .faq-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .faq-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            color: #b0b0b0;
            margin-top: 0;
            line-height: 1.6;
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
            margin-top: 15px;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
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
            .faq-grid {
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
        <p>Have questions? We're here to help. Reach out to us anytime.</p>
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
                    <p>123 Health Street, Medical City, MC 12345</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h3>Call Us</h3>
                    <p>Emergency: <strong>+1 (555) 911-1234</strong></p>
                    <p>Support: +1 (555) 123-4567</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h3>Email Us</h3>
                    <p>General: info@lifeflow.com</p>
                    <p>Support: support@lifeflow.com</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h3>Working Hours</h3>
                    <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                    <p>Saturday: 10:00 AM - 4:00 PM</p>
                    <p>Sunday: Closed</p>
                    <p style="color: #c7362b; margin-top: 5px;">24/7 Emergency Support Available</p>
                </div>
            </div>

            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.11976397304681!3d40.69766374874431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1699999999999!5m2!1sen!2s" 
                        allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>

        <!-- Right Side - Contact Form -->
        <div data-aos="fade-left">
            <div class="form-container">
                <h2><i class="fas fa-paper-plane"></i> Send us a Message</h2>
                
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
                            <option value="Feedback">Feedback / Suggestion</option>
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

<!-- FAQ Section -->
<div class="faq-section">
    <div class="section-title" data-aos="fade-up" style="text-align: center; margin-bottom: 30px;">
        <h2>Frequently Asked Questions</h2>
        <p>Quick answers to common questions</p>
    </div>
    <div class="faq-grid">
        <div class="faq-item" data-aos="fade-up" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>📝 How often can I donate blood?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                For whole blood donation, you need to wait at least 56 days (8 weeks) between donations. For safety reasons, we recommend 90 days gap between donations to ensure your body fully recovers.
            </div>
        </div>
        <div class="faq-item" data-aos="fade-up" data-aos-delay="100" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>👤 Who can donate blood?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Generally, healthy individuals aged 18-65 years, weighing at least 50kg (110 lbs), with normal hemoglobin levels can donate. A medical screening is done before each donation to ensure safety.
            </div>
        </div>
        <div class="faq-item" data-aos="fade-up" data-aos-delay="200" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>⏱️ How long does the donation process take?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                The entire process takes about 30-45 minutes, including registration (5-10 min), medical screening (10-15 min), actual donation (8-10 minutes), and refreshments (10-15 min).
            </div>
        </div>
        <div class="faq-item" data-aos="fade-up" data-aos-delay="300" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>🩸 Is blood donation safe?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Yes! We use sterile, single-use equipment for each donor. There's absolutely no risk of contracting any disease during the donation process. All needles and bags are disposed of properly after single use.
            </div>
        </div>
        <div class="faq-item" data-aos="fade-up" data-aos-delay="400" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>🍽️ What should I do before donating?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Eat a healthy meal 2-3 hours before donation, drink plenty of water, get adequate sleep, and avoid fatty foods. Don't donate on an empty stomach!
            </div>
        </div>
        <div class="faq-item" data-aos="fade-up" data-aos-delay="500" onclick="toggleFaq(this)">
            <div class="faq-question">
                <span>🏥 Can my hospital partner with LifeFlow?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Absolutely! We're always looking to partner with more hospitals. Please contact us via the form above with "Hospital Partnership" as the subject, and our team will reach out within 48 hours.
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

    function toggleFaq(element) {
        element.classList.toggle('active');
    }

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