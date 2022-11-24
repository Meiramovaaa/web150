<?php
	include "config/base_url.php";
	include "config/db.php";
	include "common/time.php";

	if(isset($_GET['nickname'])){
		$prep = mysqli_prepare($con,
		"SELECT b.*, c.name, u.nickname 
		FROM blogs b
		LEFT OUTER JOIN 
		categories c ON b.category_id = c.id
		LEFT OUTER JOIN
		users u ON b.author_id = u.id
		WHERE u.nickname=?");

		mysqli_stmt_bind_param($prep, "s", $_GET['nickname']);
		mysqli_stmt_execute($prep);
		$query = mysqli_stmt_get_result($prep);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Профиль</title>
	<?php include "views/head.php"; ?>
</head>
<body>


<?php include "views/header.php"; ?>
<section class="container page">
	<div class="page-content">
		<div class="page-header">
			<?php
				$user_prep = mysqli_prepare($con, 
				"SELECT * FROM users WHERE nickname=?");
				mysqli_stmt_bind_param($user_prep, "s", $_GET['nickname']);
				mysqli_stmt_execute($user_prep);
				$users = mysqli_stmt_get_result($user_prep);
				$user = mysqli_fetch_assoc($users);

				if($user['nickname'] == $_SESSION['nickname']){
			?>
				<h2>Мои блоги</h2>
				<a class="button" href="newblog.php">Новый блог</a>
			<?php
				}else{
			?>
				<h2>Блоги <?=$user['full_name']?> </h2>
			<?php
				}
			?>
		</div>

		<div class="blogs">
			<?php
			if(mysqli_num_rows($query) > 0){
				while($blog = mysqli_fetch_assoc($query)){
			?>
				<div class="blog-item">
					<img class="blog-item--img" src="<?=$BASE_URL?>/<?=$blog['img']?>" alt="">
					<div class="blog-header">
						<h3><?=$blog['title']?></h3>
						<?php
							if($_SESSION['nickname'] == $blog['nickname']){
						?>
							<span class="link">
								<img src="images/dots.svg" alt="">
								Еще

								<ul class="dropdown">
									<li> <a href="<?=$BASE_URL?>/editblog.php?id=<?=$blog['id']?>">Редактировать</a> </li>
									<li><a href="<?=$BASE_URL?>/api/blog/delete.php?id=<?=$blog['id']?>" class="danger">Удалить</a></li>
								</ul>
							</span>
						<?php
							}
						?>

					</div>
					<p class="blog-desc"><?=$blog['description']?></p>

					<div class="blog-info">
						<span class="link">
							<img src="images/date.svg" alt="">
							<?=time_elapsed_string(strtotime($blog['date']))?>
						</span>
						<span class="link">
							<img src="images/visibility.svg" alt="">
							21
						</span>
						<a class="link">
							<img src="images/message.svg" alt="">
							<?php
								$blog_id = $blog['id'];
								$c = mysqli_query($con, 
								"SELECT * FROM comments WHERE blog_id=$blog_id");
								echo mysqli_num_rows($c);
							?>
						</a>
						<span class="link">
							<img src="images/forums.svg" alt="">
							<?=$blog['name']?>
						</span>
						<a class="link">
							<img src="images/person.svg" alt="">
							<?=$blog['nickname']?>
						</a>
					</div>
				</div>
			
			<?php
				}
			}else{
			?>
				<h1>0 blogs</h1>

			<?php
			}
			?>

		</div>
	</div>
	<div class="page-info">
		<div class="user-profile">
			<img class="user-profile--ava" src="images/avatar.png" alt="">
			<?php
				$id = $_SESSION['user_id'];
				$query_user = mysqli_query($con, 
				"SELECT * FROM users WHERE
				id = $id");
				$row = mysqli_fetch_assoc($query_user);
			?>
			<h1><?=$row['full_name']?></h1>
			
			<?php 
				if(isset($row['about']) && strlen($row['about'])>0){
			?>
				<h2><?=$row['about']?></h2>
			<?php
				}
			?>
			
			<p><?=mysqli_num_rows($query)?> постa за все время</p>
			<a href="<?=$BASE_URL?>/edit-profile.php" class="button">Редактировать</a>
			<a href="<?=$BASE_URL?>/api/user/signout.php" class="button button-danger"> Выход</a>
		</div>
	</div>
</section>	
<?php
	}else{
			header("Location:$BASE_URL/index.php");
		}
?>
</body>
</html>