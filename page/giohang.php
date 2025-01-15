<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="giohang.css">

</head>
<body>
    <div class="container">
        <h2>Giỏ hàng của bạn</h2>
        <table>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
                <th>Hành động</th>
            </tr>
            <!-- Dòng này sẽ lặp lại cho mỗi sản phẩm trong giỏ hàng -->
            <tr>
                <td>Sản phẩm 1</td>
                <td>100,000 VND</td>
                <td>1</td>
                <td>100,000 VND</td>
                <td><a class="remove-btn" href="#">Xóa</a></td>
            </tr>
        
        </table>
        <p>Giỏ hàng của bạn đang trống.</p>

        <!-- thêm sản phẩm vào giỏ hàng -->
        <div class="add-form">
            <form method="post" action="#">
                <input type="hidden" name="product_id" value="1">
                <input type="hidden" name="product_name" value="Sản phẩm 1">
                <input type="hidden" name="product_price" value="100000">
                <input type="number" name="product_quantity" value="1" min="1">
                <input type="submit" name="add_to_cart" value="Thêm vào giỏ hàng">
            </form>
        </div>
    </div>
</body>
</html>