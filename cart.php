<?php
session_start();
include 'db.php'; // DB connection file

$cart = $_SESSION['cart'] ?? [];
$products = [];

if (!empty($cart)) {
    $ids = implode(",", array_map('intval', array_keys($cart)));
    $sql = "SELECT id, name, price FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart[$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart - Paper Tales</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f3ee; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #d4a373; color: white; }
        a.button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #d4a373;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        a.button:hover {
            background-color: #c07f45;
        }
    </style>
</head>
<body>
    <h1>Your Cart</h1>

    <?php if (empty($products)) : ?>
        <p>Your cart is empty.</p>
    <?php else : ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Price (Rs)</th>
                <th>Quantity</th>
                <th>Subtotal (Rs)</th>
            </tr>
            <?php 
            $total = 0;
            foreach ($products as $product): 
                $total += $product['subtotal'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= $product['price'] ?></td>
                    <td><?= $product['quantity'] ?></td>
                    <td><?= $product['subtotal'] ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Total:</strong></td>
                <td><strong><?= $total ?> Rs</strong></td>
            </tr>
        </table>

        <a class="button" href="checkout.php">Proceed to Checkout</a>
    <?php endif; ?>
</body>
</html>
