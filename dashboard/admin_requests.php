<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle approve/reject via AJAX
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];
    
    $req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood_requests WHERE id=$id"));
    
    if ($req) {
        if ($action == "approve") {
            mysqli_query($conn, "UPDATE blood_requests SET status='approved' WHERE id=$id");
            mysqli_query($conn, "UPDATE blood_stock SET units_available = units_available - {$req['units']} WHERE blood_group='{$req['blood_group']}'");
            echo json_encode(['success' => true, 'message' => 'Request approved successfully']);
        } elseif ($action == "reject") {
            mysqli_query($conn, "UPDATE blood_requests SET status='rejected' WHERE id=$id");
            echo json_encode(['success' => true, 'message' => 'Request rejected']);
        }
    }
    exit();
}

// Fetch filtered data - Updated to join with users table
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$blood_filter = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';

$query = "SELECT br.*, u.email, u.phone 
          FROM blood_requests br
          JOIN users u ON br.hospital_id = u.id
          WHERE 1=1";

if ($search) $query .= " AND (br.hospital_name LIKE '%$search%' OR br.patient_name LIKE '%$search%')";
if ($status_filter) $query .= " AND br.status='$status_filter'";
if ($blood_filter) $query .= " AND br.blood_group='$blood_filter'";
$query .= " ORDER BY br.id DESC";

$result = mysqli_query($conn, $query);
?>

<!-- Rest of the HTML remains the SAME as before -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Requests | Admin Panel</title>
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
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2rem;
            background: linear-gradient(135deg, #fff, #c7362b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .filter-bar {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 2;
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #c7362b;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
        }

        .filter-select {
            flex: 1;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            cursor: pointer;
        }

        .reset-btn {
            padding: 12px 24px;
            background: rgba(199, 54, 43, 0.2);
            border: 1px solid #c7362b;
            border-radius: 12px;
            color: #c7362b;
            cursor: pointer;
            transition: 0.3s;
        }

        .reset-btn:hover {
            background: rgba(199, 54, 43, 0.4);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow-x: auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            color: #c7362b;
            border-bottom: 2px solid rgba(199, 54, 43, 0.3);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-pending {
            background: rgba(243, 156, 18, 0.2);
            color: #f39c12;
        }

        .badge-approved {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
        }

        .badge-rejected {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .btn-approve, .btn-reject {
            padding: 6px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-approve {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .btn-approve:hover {
            background: #2ecc71;
            color: white;
        }

        .btn-reject {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .btn-reject:hover {
            background: #e74c3c;
            color: white;
        }

        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(46, 204, 113, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            border-radius: 12px;
            display: none;
            animation: slideIn 0.3s ease;
            z-index: 1000;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-tasks"></i> Blood Requests Management</h1>
        <p style="color: #b0b0b0; margin-top: 10px;">Review and manage blood requests from hospitals</p>
    </div>

    <div class="filter-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by hospital or patient...">
        </div>
        <select id="statusFilter" class="filter-select">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
        <select id="bloodFilter" class="filter-select">
            <option value="">All Blood Groups</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>
        <button class="reset-btn" onclick="resetFilters()"><i class="fas fa-redo"></i> Reset</button>
    </div>

    <div class="table-container">
        <table id="requestsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hospital</th>
                    <th>Patient</th>
                    <th>Blood Group</th>
                    <th>Units</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr data-id="<?= $row['id'] ?>">
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['hospital_name']) ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><strong><?= $row['blood_group'] ?></strong></td>
                    <td><?= $row['units'] ?></td>
                    <td><?= htmlspecialchars(substr($row['reason'], 0, 30)) ?>...</td>
                    <td>
                        <span class="badge badge-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'pending'): ?>
                        <div class="action-btns">
                            <button class="btn-approve" onclick="updateRequest(<?= $row['id'] ?>, 'approve')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn-reject" onclick="updateRequest(<?= $row['id'] ?>, 'reject')">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                        <?php else: ?>
                        <span style="color: #666;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
    function applyFilters() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        const blood = document.getElementById('bloodFilter').value;
        
        const rows = document.querySelectorAll('#requestsTable tbody tr');
        
        rows.forEach(row => {
            const hospital = row.cells[1].textContent.toLowerCase();
            const patient = row.cells[2].textContent.toLowerCase();
            const rowStatus = row.cells[6].textContent.trim().toLowerCase();
            const rowBlood = row.cells[3].textContent.trim();
            
            let show = true;
            
            if (search && !hospital.includes(search) && !patient.includes(search)) show = false;
            if (status && rowStatus !== status) show = false;
            if (blood && rowBlood !== blood) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('bloodFilter').value = '';
        applyFilters();
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilters);
    document.getElementById('statusFilter').addEventListener('change', applyFilters);
    document.getElementById('bloodFilter').addEventListener('change', applyFilters);

    function updateRequest(id, action) {
        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=${action}&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            }
        });
    }

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