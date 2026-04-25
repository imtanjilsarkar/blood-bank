<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hospital_id = $_SESSION['user_id'];
    $hospital_name = $_SESSION['name'];
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $units = intval($_POST['units']);
    $patient_name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    // Check stock availability
    $stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units_available FROM blood_stock WHERE blood_group='$blood_group'"));
    
    if ($stock && $stock['units_available'] >= $units) {
        $sql = "INSERT INTO blood_requests (hospital_id, hospital_name, blood_group, units, patient_name, reason, status) 
                VALUES ('$hospital_id', '$hospital_name', '$blood_group', '$units', '$patient_name', '$reason', 'pending')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Request submitted successfully! Admin will review shortly.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $available = $stock ? $stock['units_available'] : 0;
        $error = "Insufficient stock! Only $available units available.";
    }
}
?>

<!-- Rest of the HTML remains the SAME as before -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood | LifeFlow</title>
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
            background: #0a0a0a;
            color: #ffffff;
            padding: 30px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 35px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #c7362b;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 14px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: 0.3s;
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

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #c7362b, #a1241a);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(199, 54, 43, 0.4);
        }

        .alert {
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
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

        .input-error {
            border-color: #e74c3c !important;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-hand-holding-medical"></i> Request Blood</h1>
        <p style="color: #b0b0b0;">Fill out the form to request blood for your patient</p>
    </div>

    <div class="form-card">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $success ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form id="requestForm" method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Patient Name</label>
                <input type="text" name="patient_name" id="patientName" required placeholder="Enter patient's full name">
                <div class="error-message" id="patientNameError"></div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-tint"></i> Blood Group</label>
                <select name="blood_group" id="bloodGroup" required>
                    <option value="">Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
                <div class="error-message" id="bloodGroupError"></div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-cubes"></i> Units Required</label>
                <input type="number" name="units" id="units" min="1" max="20" required placeholder="Enter number of units (1-20)">
                <div class="error-message" id="unitsError"></div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-stethoscope"></i> Reason for Request</label>
                <textarea name="reason" id="reason" required placeholder="Describe the medical need for this blood request"></textarea>
                <div class="error-message" id="reasonError"></div>
            </div>

            <button type="submit"><i class="fas fa-paper-plane"></i> Submit Request</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('requestForm').addEventListener('submit', function(e) {
        let isValid = true;
        
        const patientName = document.getElementById('patientName');
        if (patientName.value.trim().length < 3) {
            document.getElementById('patientNameError').textContent = 'Please enter a valid patient name (min 3 characters)';
            patientName.classList.add('input-error');
            isValid = false;
        } else {
            document.getElementById('patientNameError').textContent = '';
            patientName.classList.remove('input-error');
        }
        
        const bloodGroup = document.getElementById('bloodGroup');
        if (!bloodGroup.value) {
            document.getElementById('bloodGroupError').textContent = 'Please select a blood group';
            bloodGroup.classList.add('input-error');
            isValid = false;
        } else {
            document.getElementById('bloodGroupError').textContent = '';
            bloodGroup.classList.remove('input-error');
        }
        
        const units = document.getElementById('units');
        if (units.value < 1 || units.value > 20) {
            document.getElementById('unitsError').textContent = 'Units must be between 1 and 20';
            units.classList.add('input-error');
            isValid = false;
        } else {
            document.getElementById('unitsError').textContent = '';
            units.classList.remove('input-error');
        }
        
        const reason = document.getElementById('reason');
        if (reason.value.trim().length < 10) {
            document.getElementById('reasonError').textContent = 'Please provide a valid reason (min 10 characters)';
            reason.classList.add('input-error');
            isValid = false;
        } else {
            document.getElementById('reasonError').textContent = '';
            reason.classList.remove('input-error');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    document.getElementById('patientName').addEventListener('input', function() {
        if (this.value.trim().length >= 3) {
            this.classList.remove('input-error');
            document.getElementById('patientNameError').textContent = '';
        }
    });
    
    document.getElementById('units').addEventListener('input', function() {
        if (this.value >= 1 && this.value <= 20) {
            this.classList.remove('input-error');
            document.getElementById('unitsError').textContent = '';
        }
    });
</script>
</body>
</html>