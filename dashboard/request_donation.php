<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location: ../login.php");
    exit();
}

$donor_id = $_SESSION['user_id'];
$donor_name = $_SESSION['name'];

// Check if donor has any pending requests
$checkPending = mysqli_query($conn, "SELECT COUNT(*) as pending FROM donation_requests WHERE donor_id='$donor_id' AND status='pending'");
$pendingCount = mysqli_fetch_assoc($checkPending)['pending'];

// Get donor's last donation date
$lastDonation = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(donation_date) as last_date FROM donations WHERE donor_id='$donor_id'"))['last_date'];

// Check eligibility
$eligible = true;
$daysLeft = 0;
if ($lastDonation) {
    $lastDate = new DateTime($lastDonation);
    $today = new DateTime();
    $diff = $today->diff($lastDate)->days;
    if ($diff < 90) {
        $eligible = false;
        $daysLeft = 90 - $diff;
    }
}

if (isset($_POST['request'])) {
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $units = intval($_POST['units']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $scheduled_date = !empty($_POST['scheduled_date']) ? $_POST['scheduled_date'] : null;
    
    // Validate units (1-3 units per donation)
    if ($units < 1 || $units > 3) {
        $error = "Units must be between 1 and 3 per donation request.";
    } elseif (!$eligible) {
        $error = "You are not eligible to donate yet. Please wait $daysLeft more days.";
    } elseif ($pendingCount > 0) {
        $error = "You already have a pending donation request. Please wait for it to be processed.";
    } else {
        $query = "INSERT INTO donation_requests (donor_id, donor_name, blood_group, units, notes, scheduled_date, status) 
                  VALUES ('$donor_id', '$donor_name', '$blood_group', '$units', '$notes', " . ($scheduled_date ? "'$scheduled_date'" : "NULL") . ", 'pending')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Your donation request has been submitted successfully! Our team will contact you within 24 hours.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Donation | LifeFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a0a, #0a1520);
            color: #ffffff;
            min-height: 100vh;
        }

        .dashboard-container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px 0;
        }

        .sidebar-header {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 25px;
            color: #b0b0b0;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-menu a:hover {
            background: linear-gradient(90deg, rgba(199, 54, 43, 0.2), transparent);
            color: #c7362b;
        }

        .sidebar-menu .active a {
            background: linear-gradient(90deg, rgba(199, 54, 43, 0.2), transparent);
            color: #c7362b;
            border-left: 3px solid #c7362b;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
        }

        /* Top Bar */
        .top-bar {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .home-btn {
            background: rgba(199, 54, 43, 0.2);
            border: 1px solid #c7362b;
            padding: 8px 20px;
            border-radius: 40px;
            color: #c7362b;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .home-btn:hover {
            background: #c7362b;
            color: white;
            transform: translateY(-2px);
        }

        /* Form Container */
        .form-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 40px;
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .form-header p {
            color: #b0b0b0;
            margin-top: 10px;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.15);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        .alert-warning {
            background: rgba(243, 156, 18, 0.15);
            border: 1px solid #f39c12;
            color: #f39c12;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #c7362b;
        }

        label i {
            margin-right: 8px;
        }

        input, select, textarea {
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

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #c7362b;
            background: rgba(255, 255, 255, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        select option {
            background: #1a1a1a;
        }

        /* Units Selector */
        .units-selector {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .unit-option {
            flex: 1;
            text-align: center;
            cursor: pointer;
        }

        .unit-option input {
            display: none;
        }

        .unit-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .unit-option input:checked + .unit-card {
            border-color: #c7362b;
            background: rgba(199, 54, 43, 0.1);
        }

        .unit-card:hover {
            border-color: #c7362b;
        }

        .unit-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: #c7362b;
        }

        .unit-label {
            font-size: 0.8rem;
            color: #b0b0b0;
        }

        /* Submit Button */
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(199, 54, 43, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }

        .info-card i {
            font-size: 2rem;
            color: #c7362b;
            margin-bottom: 10px;
        }

        .info-card .value {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .info-card .label {
            color: #b0b0b0;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .sidebar-header h2, .sidebar-menu a span {
                display: none;
            }
            .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
                padding: 20px;
            }
            .form-container {
                padding: 25px;
            }
            .units-selector {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-droplet"></i> LifeFlow</h2>
            <p style="color: #c7362b; font-size: 0.8rem;">Donor Portal</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="donor.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="donation_history.php"><i class="fas fa-history"></i> <span>Donation History</span></a></li>
            <li class="active"><a href="request_donation.php"><i class="fas fa-hand-holding-heart"></i> <span>Request Donation</span></a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div>
                <h2>Request Donation</h2>
                <p style="color: #b0b0b0;">Schedule your blood donation appointment</p>
            </div>
            <a href="../index.php" class="home-btn">
                <i class="fas fa-home"></i> Home
            </a>
        </div>

        <!-- Info Cards -->
        <div class="info-cards">
            <div class="info-card">
                <i class="fas fa-calendar-alt"></i>
                <div class="value"><?= $pendingCount ?></div>
                <div class="label">Pending Requests</div>
            </div>
            <div class="info-card">
                <i class="fas fa-hourglass-half"></i>
                <div class="value"><?= $eligible ? 'Ready' : ($daysLeft . ' days') ?></div>
                <div class="label">Eligibility Status</div>
            </div>
            <div class="info-card">
                <i class="fas fa-tint"></i>
                <div class="value">1-3 Units</div>
                <div class="label">Per Donation</div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-hand-holding-heart"></i> Schedule a Donation</h2>
                <p>Your donation can save up to 3 lives. Thank you for your generosity!</p>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div><?= $success ?></div>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <?php if (!$eligible): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-clock"></i>
                    <div>You need to wait <strong><?= $daysLeft ?> more days</strong> before your next donation. The minimum gap between donations is 90 days for your health and safety.</div>
                </div>
            <?php endif; ?>

            <?php if ($pendingCount > 0): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-hourglass-half"></i>
                    <div>You already have a pending donation request. Please wait for it to be processed before submitting another request.</div>
                </div>
            <?php endif; ?>

            <form method="POST" id="donationForm">
                <!-- Blood Group Selection -->
                <div class="form-group">
                    <label><i class="fas fa-tint"></i> Blood Group</label>
                    <select name="blood_group" required <?= (!$eligible || $pendingCount > 0) ? 'disabled' : '' ?>>
                        <option value="">Select Your Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>

                <!-- Units Selection -->
                <div class="form-group">
                    <label><i class="fas fa-cubes"></i> Number of Units</label>
                    <div class="units-selector">
                        <label class="unit-option">
                            <input type="radio" name="units" value="1" checked required <?= (!$eligible || $pendingCount > 0) ? 'disabled' : '' ?>>
                            <div class="unit-card">
                                <div class="unit-number">1 Unit</div>
                                <div class="unit-label">Standard Donation</div>
                            </div>
                        </label>
                        <label class="unit-option">
                            <input type="radio" name="units" value="2" <?= (!$eligible || $pendingCount > 0) ? 'disabled' : '' ?>>
                            <div class="unit-card">
                                <div class="unit-number">2 Units</div>
                                <div class="unit-label">Double Red Cell</div>
                            </div>
                        </label>
                        <label class="unit-option">
                            <input type="radio" name="units" value="3" <?= (!$eligible || $pendingCount > 0) ? 'disabled' : '' ?>>
                            <div class="unit-card">
                                <div class="unit-number">3 Units</div>
                                <div class="unit-label">Triple Donation</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Preferred Date -->
                <div class="form-group">
                    <label><i class="fas fa-calendar"></i> Preferred Donation Date (Optional)</label>
                    <input type="date" name="scheduled_date" min="<?= date('Y-m-d', strtotime('+7 days')) ?>" max="<?= date('Y-m-d', strtotime('+60 days')) ?>" <?= (!$eligible || $pendingCount > 0) ? 'disabled' : '' ?>>
                    <small style="color: #b0b0b0; display: block; margin-top: 5px;">Choose a date at least 7 days from today</small>
                </div>

                <!-- Additional Notes -->
                <div class="form-group">
                    <label><i class="fas fa-pen"></i> Additional Notes (Optional)</label>
                    <textarea name="notes" placeholder="Any special requests or information..." <?= (!$eligible || $pendingCount > 0) ? 'disabled' : '' ?>></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="request" class="btn-submit" <?= (!$eligible || $pendingCount > 0) ? 'disabled' : '' ?>>
                    <i class="fas fa-paper-plane"></i> Submit Donation Request
                </button>
            </form>

            <!-- Donation Tips -->
            <div style="margin-top: 30px; padding: 20px; background: rgba(255,255,255,0.03); border-radius: 12px;">
                <h4 style="color: #c7362b; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Before You Donate:</h4>
                <ul style="color: #b0b0b0; line-height: 1.8; margin-left: 20px;">
                    <li>✓ Eat a healthy meal 2-3 hours before donation</li>
                    <li>✓ Drink plenty of water before and after</li>
                    <li>✓ Get adequate sleep (at least 6 hours)</li>
                    <li>✓ Avoid fatty foods 24 hours before donation</li>
                    <li>✓ Bring a valid ID with you</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Form validation
    document.getElementById('donationForm')?.addEventListener('submit', function(e) {
        const bloodGroup = this.querySelector('[name="blood_group"]').value;
        const units = this.querySelector('[name="units"]:checked');
        
        if (!bloodGroup) {
            e.preventDefault();
            alert('Please select your blood group');
        } else if (!units) {
            e.preventDefault();
            alert('Please select number of units');
        }
    });

    // Date picker min validation
    const dateInput = document.querySelector('[name="scheduled_date"]');
    if (dateInput) {
        const minDate = new Date();
        minDate.setDate(minDate.getDate() + 7);
        dateInput.min = minDate.toISOString().split('T')[0];
        
        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + 60);
        dateInput.max = maxDate.toISOString().split('T')[0];
    }
</script>
</body>
</html>