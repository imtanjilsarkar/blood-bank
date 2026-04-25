<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle AJAX update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
    $blood_group = $_POST['blood_group'];
    $units = intval($_POST['units']);
    $action = $_POST['action'];
    
    $current = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units_available FROM blood_stock WHERE blood_group='$blood_group'"))['units_available'];
    
    if ($action == 'add') {
        $new = $current + $units;
    } else {
        $new = max(0, $current - $units);
    }
    
    mysqli_query($conn, "UPDATE blood_stock SET units_available=$new WHERE blood_group='$blood_group'");
    echo json_encode(['success' => true, 'new_units' => $new]);
    exit();
}

$stock = mysqli_query($conn, "SELECT * FROM blood_stock");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock | LifeFlow</title>
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
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .update-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #c7362b;
            font-weight: 600;
        }

        select, input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            font-weight: normal;
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

        .current-stock {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 20px;
        }

        .stock-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(46, 204, 113, 0.95);
            padding: 15px 25px;
            border-radius: 12px;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-edit"></i> Update Blood Stock</h1>
        <p style="color: #b0b0b0;">Add or remove blood units from inventory</p>
    </div>

    <div class="update-card">
        <form id="updateForm">
            <div class="form-group">
                <label><i class="fas fa-tint"></i> Blood Group</label>
                <select id="bloodGroup" required>
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
            </div>

            <div class="form-group">
                <label><i class="fas fa-cubes"></i> Number of Units</label>
                <input type="number" id="units" min="1" required placeholder="Enter units">
            </div>

            <div class="form-group">
                <label><i class="fas fa-exchange-alt"></i> Action</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="action" value="add" checked> ➕ Add to Stock
                    </label>
                    <label>
                        <input type="radio" name="action" value="remove"> ➖ Remove from Stock
                    </label>
                </div>
            </div>

            <button type="submit"><i class="fas fa-save"></i> Update Stock</button>
        </form>
    </div>

    <div class="current-stock">
        <h3><i class="fas fa-chart-simple"></i> Current Stock Levels</h3>
        <div id="stockList">
            <?php while ($row = mysqli_fetch_assoc($stock)): ?>
            <div class="stock-item">
                <span><strong><?= $row['blood_group'] ?></strong></span>
                <span id="stock-<?= $row['blood_group'] ?>"><?= $row['units_available'] ?> units</span>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
    document.getElementById('updateForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const bloodGroup = document.getElementById('bloodGroup').value;
        const units = document.getElementById('units').value;
        const action = document.querySelector('input[name="action"]:checked').value;
        
        if (!bloodGroup || !units) {
            showToast('Please fill all fields', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('ajax', true);
        formData.append('blood_group', bloodGroup);
        formData.append('units', units);
        formData.append('action', action);
        
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById(`stock-${bloodGroup}`).textContent = data.new_units + ' units';
            showToast(`Stock updated successfully! New balance: ${data.new_units} units`, 'success');
            document.getElementById('updateForm').reset();
        }
    });
    
    function showToast(message, type) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.backgroundColor = type === 'success' ? 'rgba(46, 204, 113, 0.95)' : 'rgba(231, 76, 60, 0.95)';
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
</script>
</body>
</html>