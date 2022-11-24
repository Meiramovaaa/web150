<?php
	include "config/db.php";
	include "config/base_url.php";

	if(!isset($_GET['id'])){
		header("Location:$BASE_URL/index.php");
	}

	$id = $_GET['id'];

	$query = mysqli_query($con, 
	"SELECT b.*, u.nickname, c.name FROM blogs b
	LEFT OUTER JOIN users u ON u.id = b.author_id 
	LEFT OUTER JOIN categories c ON b.category_id = c.id
	WHERE b.id = $id");	

	if(mysqli_num_rows($query) == 0){
		header("Location:$BASE_URL/index.php");
	}

	$row = mysqli_fetch_assoc($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Профиль</title>
    <?php include "views/head.php"; ?>
</head>

<body 
data-blogid = '<?=$row["id"]?>'
data-baseurl = "<?=$BASE_URL?>"
data-authorblog = "<?=$row['author_id']?>"
data-currentuser = "<?=$_SESSION['user_id']?>"
>
<?php include "views/header.php"; ?>
<section class="container page">
	<div class="page-content">
		<div class="blogs">
			<div class="blog-item">
				<img class="blog-item--img" src="<?=$BASE_URL?>/<?=$row['img']?>" alt="">

                <div class="blog-info">
					<span class="link">
						<img src="images/date.svg" alt="">
						<?=$row['date']?>
					</span>
					<span class="link">
						<img src="images/visibility.svg" alt="">
						21
					</span>
					<a class="link">
						<img src="images/message.svg" alt="">
						4
					</a>
					<span class="link">
						<img src="images/forums.svg" alt="">
						<?=$row['name']?>
					</span>
					<a class="link">
						<img src="images/person.svg" alt="">
						<?=$row['nickname']?>
					</a>
				</div>

				<div class="blog-header">
					<h3><?=$row['title']?></h3>
				</div>
				<p class="blog-desc"><?=$row['description']?></p>
			</div>
		</div>

        <div class="comments" id="comments"></div>

		<?php
		if(isset($_SESSION['nickname'])){
		?>
			<span class="comment-add">
				<textarea id="textarea" class="comment-textarea" placeholder="Введит текст комментария"></textarea>
				<button id="add-btn" class="button">Отправить</button>
			</span>
		<?php
		}else{
		?>
			<span class="comment-warning">
				Чтобы оставить комментарий <a href="">зарегистрируйтесь</a> , или  <a href="">войдите</a>  в аккаунт.
			</span>
		<?php
		}
		?>
	</div>
	

    <?php
		include "views/categories.php";
	?>
</section>	

	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.0/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="js/comments.js"></script>
</body>
</html>