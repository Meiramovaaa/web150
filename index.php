<?php
    include "config/db.php";
	include "config/base_url.php";
	include "common/time.php";

	$q = '';
	$limit = 2;
	$page = 1;
	$category = null;

	$sql = "SELECT b.*, c.name, u.nickname 
	FROM blogs b
	LEFT OUTER JOIN 
	categories c ON b.category_id = c.id
	LEFT OUTER JOIN
	users u ON b.author_id = u.id";

	$sql_count = "SELECT CEIL(COUNT(*)/$limit) as total
	FROM blogs b LEFT OUTER JOIN users u
	ON b.author_id = u.id";
	// 11  / 2 = 5.5 

	if(isset($_GET["cat_id"])){
		$id = $_GET['cat_id'];
		$sql .= " WHERE b.category_id=$id";
		$sql_count .= " WHERE b.category_id=$id";
		$category = $_GET['cat_id'];
	}

	if(isset($_GET['q'])){
		$q = strtolower($_GET['q']);
		$sql .= " WHERE LOWER(b.title) LIKE ? OR
		LOWER(b.description) LIKE ? OR
		LOWER(u.nickname) LIKE ? OR
		LOWER(c.name) LIKE ?";
		$sql_count .= " WHERE LOWER(b.title) LIKE ? OR
		LOWER(b.description) LIKE ? OR
		LOWER(u.nickname) LIKE ? OR
		LOWER(c.name) LIKE ?";
	}

	if(isset($_GET['page']) && intval($_GET['page'])){
		$skip = ($_GET['page'] - 1) * $limit;
		$sql .= " LIMIT $skip, $limit";
		$page = $_GET['page'];
	}else{
		$sql .= " LIMIT 0, $limit";
	}

	if($q){
		$param = "%$q%";

		$prep_count = mysqli_prepare($con, $sql_count);
		mysqli_stmt_bind_param($prep_count, "ssss", 
		$param, $param, $param, $param);
		mysqli_stmt_execute($prep_count);
		// $prep_count = [{total:3}]
		$count = mysqli_stmt_get_result($prep_count);
		// $count['total'] = 3

		$prep = mysqli_prepare($con, $sql);
		mysqli_stmt_bind_param($prep, "ssss", 
		$param, $param, $param, $param);
		mysqli_stmt_execute($prep);
		$query = mysqli_stmt_get_result($prep);
	}else{
		$query = mysqli_query($con, $sql);
		$query_count = mysqli_query($con, $sql_count);
		// [{total:3}]
		$count = mysqli_fetch_assoc($query_count);
		// $count['total] = 3
	}

	// LIMIT 
	// page=1 LIMIT 0, 2
	// page=2 LIMIT 2, 2
	// page = 3 LIMIT 4, 2
	// ($page - 1) * limit

	



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Главная</title>
    <?php include "views/head.php"; ?>
</head>
<body>

<?php include "views/header.php"; ?>
<section class="container page">
	<div class="page-content">
			<h2 class="page-title">Блоги по программированию</h2>
			<p class="page-desc">Популярные и лучшие публикации по программированию для начинающих
 и профессиональных программистов и IT-специалистов.</p>

		<div class="blogs">
			<?php
				if(mysqli_num_rows($query) > 0) {
					while($blog = mysqli_fetch_assoc($query)) {
			?>
				<div class="blog-item">
					<img class="blog-item--img" src="<?=$BASE_URL?>/<?=$blog['img']?>" alt="">
					<div class="blog-header">
						<a href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog['id']?>"><h3><?=$blog['title']?></h3></a> 
					</div>
					<p class="blog-desc">
						<?=$blog['description']?>
					</p>

					<div class="blog-info">
						<span class="link">
							<img src="<?=$BASE_URL; ?>/images/date.svg" alt="">
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
						<a class="link" href="<?=$BASE_URL?>/profile.php?nickname=<?=$blog['nickname']?>">
							<img src="images/person.svg" alt="">
							<?=$blog['nickname']?>
						</a>
					</div>
				</div>

			<?php }
			} else {
			?>
				<h3>0 blogs</h3>
			<?php
				}
			?>

			<?php
				// $logo = "Dec";
				// for($i = 1; $i <= $count['total']; $i++){
				// 	$logo .= "o";
				// }
				// $logo .= "de";
			?>

			<div class='pagination'>
				<?php
				// decoooode$

				// $logo_final = "dec";
				// 	for($i = 3; $i < strlen($logo) - 2; $i++){
				// 		$page = 2;
				// 		$i = 4;
				// 		if($page == $i-2){
				// 			$logo_final .= '<span>'.$logo[$i].'</span>';
				// 		}
				// 	}
				?>
			
				<div>

			
			<?php
				$cat_str = "";
				if($category){
					$cat_str = "&cat_id=".$category;
				}
				$search = '';
				if($q){
					$search = "&q=".$q;
				}
				if($page != 1){
			?>
			<a href="?page=<?=$page-1?><?=$cat_str?><?=$search?>">Prev</a>
			<?php
				}
				for($i = 1; $i <= $count['total']; $i++){
			?>
			
				<a href="?page=<?=$i?><?=$cat_str?><?=$search?>"><?=$i?></a>
			<?php
				}
				if($page != $count['total']){
			?>
				<a href="?page=<?=$page+1?><?=$cat_str?><?=$search?>">Next</a>
			<?php
				}
			?>
				</div>
			</div>

			
		</div>
	</div>

	<?php
		include "views/categories.php";
	?>

</section>	
</body>
</html>

<!-- localhost:8080/ -->