<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $transaction_date = $_POST['transaction_date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO transactions (transaction_date, description, amount) VALUES (?, ?, ?)");
    $stmt->bind_param('ssd', $transaction_date, $description, $amount);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM transactions WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM transactions ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Transaction Summary</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; }
        header, footer { text-align: center; padding: 10px; background-color: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f4f4f4; }
        .error { color: red; }
    </style>
</head>
<body>
    <header>
        <h1>Help Rebuild Lives in Flood - Ravaged Wayanad</h1>
        <div class="explanation">
            <ul>
                <li>Amount Received in Prathidhwani's Account.</li>
                <li>The last received will be at the top.</li>
                <li>We are updating this manually. </li>
                <li>If you have donated money and your name is not listed in this table, don't panic; it will be updated soon.</li>
            </ul>
        </div>
        <a href="logout.php">Logout</a>
    </header>
    <div class="container">
        <form method="post">
            <div>
                <label for="transaction_date">Transaction Date:</label>
                <input type="datetime-local" id="transaction_date" name="transaction_date" required value="<?php echo date('Y-m-d\TH:i'); ?>" class="form__input">
            </div>
            <div>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" maxlength="100" class="form__input">
            </div>
            <div>
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required max="999999" step="0.01" class="form__input">
            </div>
            <div>
                <button type="submit" name="save" class="button">Save</button>
                <button type="reset" class="button">Reset</button>
            </div>
        </form>
        <table style="margin-top: 3em;margin-bottom: 3em;">
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>Transaction Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial_number = 1;
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $serial_number++; ?></td>
                    <td><?php echo date('d-m-Y h:i A', strtotime($row['transaction_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>Rs <?php echo number_format($row['amount'], 2); ?>/-</td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="admin.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
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
