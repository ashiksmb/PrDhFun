<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $transaction_date = $_POST['transaction_date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("UPDATE transactions SET transaction_date = ?, description = ?, amount = ? WHERE id = ?");
    $stmt->bind_param('ssdi', $transaction_date, $description, $amount, $id);
    $stmt->execute();
    header("Location: admin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Transaction</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 300px; margin: 100px auto; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Transaction</h2>
        <form method="post">
            <div>
                <label for="transaction_date">Transaction Date:</label>
                <input type="datetime-local" id="transaction_date" name="transaction_date" required value="<?php echo date('Y-m-d\TH:i', strtotime($transaction['transaction_date'])); ?>" class="form__input">
            </div>
            <div>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" maxlength="100" value="<?php echo htmlspecialchars($transaction['description']); ?>" class="form__input">
            </div>
            <div>
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required max="999999" step="0.01" value="<?php echo $transaction['amount']; ?>" class="form__input">
            </div>
            <div>
                <button type="submit" name="update" class="button">Update</button>
                <button type="button" onclick="window.location.href='admin.php'" class="button">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
