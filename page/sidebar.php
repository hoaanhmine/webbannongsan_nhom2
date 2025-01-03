<link rel="stylesheet" href="giaodine.css">

    <div class="danhsach">
        <?php
        include("main/menu.php")
        ?>
        <div class="noidung">
            <?php
            if (isset($_GET['category'])) {
                $category = $_GET['category'];

                switch ($category) {
                    case 'traicay':
                        include('main/hoaqua.php');
                        break;

                    case 'raucu':
                        include('main/raucu.php');
                        break;

                    case 'amthuc':
                        include('main/amthuc.php');
                        break;

                    case 'luagao':
                        include('main/luagao.php');
                        break;

                    case 'thittrung':
                        include('main/thittrung.php');
                        break;

                    default:
                        echo "<h2>Danh Mục Không Tồn Tại</h2>";
                        break;
                }
            } else {
                echo "<h2>Chọn một danh mục để xem sản phẩm</h2>";
                include('main/hoaqua.php');
            }
            ?>
        </div>
    </div>