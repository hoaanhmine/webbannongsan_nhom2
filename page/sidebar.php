<link rel="stylesheet" href="giaodine.css">

    <div class="containersp">
        <?php
        include("main/menu.php")
        ?>
        <div class="content">
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

                    case 'thittung':
                        include('main/thi trung.php');
                        break;

                    default:
                        echo "<h2>Danh Mục Không Tồn Tại</h2>";
                        break;
                }
            } else {
                echo "<h2>Chọn một danh mục để xem sản phẩm</h2>";
            }
            ?>
        </div>
    </div>