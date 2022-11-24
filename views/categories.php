
<div class="page-info">
    <div class="page-header">
        <h2>Категории</h2>
    </div>
    
    <?php 
        $query = mysqli_query($con,
        "SELECT * FROM categories");

        while($row = mysqli_fetch_assoc($query)) {
        ?>

            <a href="?cat_id=<?=$row['id']?>" class="list-item"><?=$row['name']?></a>

        <?php
        }
    ?>
    
</div>

