<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Giỏ hàng</title>
    <style>
        .cart-container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart-table th, .cart-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .cart-table th {
            background-color: #4CAF50;
            color: white;
        }
        .cart-table td img {
            max-width: 100px;
            height: auto;
        }
        .cart-table .total {
            text-align: right;
            font-weight: bold;
        }
        .cart-table .total-price {
            color: #4CAF50;
        }
        .cart-buttons {
            margin-top: 20px;
            text-align: right;
        }
        .cart-buttons button {
            background: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background 0.3s;
            border-radius: 5px;
            margin-left: 10px;
        }
        .cart-buttons button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h1>Giỏ hàng</h1>
        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item):
                        $subtotal = $item['Price'] * $item['Quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo $item['ProductName']; ?></td>
                            <td><?php echo number_format($item['Price'], 0, ',', '.'); ?> VND</td>
                            <td><?php echo $item['Quantity']; ?></td>
                            <td><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="total">Tổng cộng:</td>
                        <td class="total-price"><?php echo number_format($total, 0, ',', '.'); ?> VND</td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-buttons">
                <button onclick="location.href='checkout.php'">Thanh toán</button>
                <button onclick="location.href='dangsp.php'">Tiếp tục mua sắm</button>
                <button onclick="location.href='../index.php'">Về trang chủ</button>
            </div>
        <?php else: ?>
            <p>Giỏ hàng của bạn đang trống.</p>
            <button onclick="location.href='dangsp.php'">Tiếp tục mua sắm</button>
            <button onclick="location.href='../index.php'">Về trang chủ</button>
        <?php endif; ?>
    </div>
</body>
</html>