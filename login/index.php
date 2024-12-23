<?php
	$connect = mysqli_connect('localhost','root','','nogsan');
	mysqli_set_charset($connect,"utf8");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Index</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>

	<!-- 'start thực hiện kiểm tra dữ liệu người dùng đăng ký' -->
	<?php
		if(isset($_POST["register"])){
			$user_name = $_POST["user_name"];
			$pass1 = $_POST["pass1"];
			$pass2 = $_POST["pass2"];
			$name = $_POST["full_name"];
			//kiểm tra xem 2 mật khẩu có giống nhau hay không:
			if($pass1!=$pass2){
				header("location:index.php?page=register");
				setcookie("error", "Đăng ký không thành công!", time()+1, "/","", 0);
			}
			else{
				$pass = md5($pass1);
				mysqli_query($connect,"
					insert into user (user_name,password,full_name)
					values ('$user_name','$pass','$name')
				");
				header("location:index.php?page=register");
				setcookie("success", "Đăng ký thành công!", time()+1, "/","", 0);
			}
		}

	?>
	<!-- 'end thực hiện kiểm tra dữ liệu người dùng đăng ký' -->


	<!-- 'start thực hiện kiểm tra dữ liệu người dùng nhập ở form đăng nhập' -->
	<?php
		if(isset($_POST["dangnhap"])){
			$tk = $_POST["user_name_lg"];
			$mk = md5($_POST["passlg"]);
			$rows = mysqli_query($connect,"
				select * from user where user_name = '$tk' and password = '$mk'
			");
			$count = mysqli_num_rows($rows);
			if($count==1){
				$_SESSION["loged"] = true;
				header("location:index.php");
				setcookie("success", "Đăng nhập thành công!", time()+1, "/","", 0);
			}
			else{
				header("location:index.php");
				setcookie("error", "Đăng nhập không thành công!", time()+1, "/","", 0);
			}
			
		}
	?>
	<!-- 'end thực hiện kiểm tra dữ liệu người dùng nhập ở form đăng nhập' -->



	

	<div class="container">
		<div class="row">
			<a href="index.php?page=register" class="btn btn-success">'Đăng ký'</a>
			<a href="index.php" class="btn btn-info">'Trang chủ'</a>
			<?php if(isset($_SESSION["loged"])) echo "<a href='index.php?act=logout' class='btn btn-danger'>Đăng xuất</a>"; ?>
		</div>

		<div class="row">
			<!-- 'start nếu xảy ra lỗi thì hiện thông báo:' -->
			<?php
				if(isset($_COOKIE["error"])){
			?>
			<div class="alert alert-danger">
			  	<strong>'Có lỗi!'</strong> <?php echo $_COOKIE["error"]; ?>
			</div>
			<?php } ?>
			<!-- 'end nếu xảy ra lỗi thì hiện thông báo:' -->


			<!-- 'start nếu thành công thì hiện thông báo:' -->
			<?php
				if(isset($_COOKIE["success"])){
			?>
			<div class="alert alert-success">
			  	<strong>'Chúc mừng!'</strong> <?php echo $_COOKIE["success"]; ?>
			</div>
			<?php } ?>
			<!-- 'end nếu thành công thì hiện thông báo:' -->




			
			<?php
			//nếu tồn tại biến $_GET["page"] = "register" thì gọi trang đăng ký:
			if(isset($_GET["page"])&&$_GET["page"]=="register")
				include "register.php";


			//nếu không tồn tại biến $_GET["page"] = "register"
			if(!isset($_GET["page"])){
				//nếu tồn tại biến session $_SESSION["loged"] thì gọi nội dung trang admin.php vào
				if(isset($_SESSION["loged"]))
					include "admin.php";
				//nếu không tồn tại biến session $_SESSION["loged"] thì gọi nội dung trang login.php vào
				else
					include "login.php";
			}
			?>
		</div>

	</div>
</body>
</html>
