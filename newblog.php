<?php
	include "config/db.php";
	include "config/base_url.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Добавление нового блога</title>
	<?php include "views/head.php"; ?>
</head>
<body>

	<?php include "views/header.php"; ?>
	<section class="container page">
		<div class="page-block">

			<div class="page-header">
				<h2>Новый блог</h2>
			</div>
			
			<form class="form" action="<?=$BASE_URL?>/api/blog/add.php" method="POST" enctype="multipart/form-data">
				
			<fieldset class="fieldset">
				<input class="input" type="text" name="title" placeholder="Заголовок">
			</fieldset>

			<fieldset class="fieldset">
				<select name="category_id" id="" class="input">
					<?php
						$query = mysqli_query($con, 
						"SELECT * FROM categories");
						while($row = mysqli_fetch_assoc($query)) {
					?>
						<option value="<?=$row['id']?>"><?=$row['name']?></option>
					<?php		
						}
					?>
					
				</select>
			</fieldset class="fieldset">
			
			<fieldset class="fieldset">
				<button class="button button-yellow input-file">
					<input type="file" name="image">	
					Выберите картинку
				</button>
			</fieldset>
				
			<fieldset class="fieldset">
				<textarea class="input input-textarea" name="description" id="" cols="30" rows="10" placeholder="Описание"></textarea>
			</fieldset>
			<fieldset class="fieldset">
				<button class="button" type="submit">Сохранить</button>
			</fieldset>
			</form>

			
			<?php
				if(isset($_GET['error']) && $_GET['error'] == '1'){
			?>
				<p class="text-danger"> Заголовок и Описание не могут быть пустыми!</p>

			<?php
				}
			?>
		</div>

	</section>
	
</body>
</html>