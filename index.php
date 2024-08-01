<?php
include 'config.php';

// Pagination settings
$limit = 100; // Number of entries per page
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$start = ($page - 1) * $limit;

$result = $conn->query("SELECT * FROM transactions ORDER BY id DESC LIMIT $start, $limit");
$total_results = $conn->query("SELECT COUNT(*) FROM transactions")->fetch_row()[0];
$total_pages = ceil($total_results / $limit);

$total_amount = $conn->query("SELECT SUM(amount) FROM transactions")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Summary</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        header, footer {
            text-align: center;
            padding: 10px;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
        }
        .total-amount {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        .logo {
            display: block;
            margin: 0 auto 10px;
            height: 150px;
            width: 450px;
        }
    </style>
</head>
<body>
    <header>
        <img src="logo.webp" alt="Prathidhwani Logo" class="logo">
        <h1>Help Rebuild Lives in Flood - Ravaged Wayanad</h1>
        <div class="explanation">
            <ul>
                <li>Amount Received in Prathidhwani's Account.</li>
                <li>The last received will be at the top.</li>
                <li>We are updating this manually.</li>
                <li>If you have donated money and your name is not listed in this table, don't panic; it will be updated soon.</li>
            </ul>
        </div>
    </header>
    <div class="total-amount">
        Total Amount Collected So Far: Rs <?php echo number_format($total_amount, 2); ?>/-
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>Transaction Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial_number = $start + 1;
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $serial_number++; ?></td>
                    <td><?php echo date('d-m-Y h:i A', strtotime($row['transaction_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>Rs <?php echo number_format($row['amount'], 2); ?>/-</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>Prathidhwani</p>
    </footer>
</body>
</html>
