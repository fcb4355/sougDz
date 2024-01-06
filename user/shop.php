<?php
$pageTitle = "منتجات";
include "init.php";
?>

<h3 class="content-title" data-aos="fade-up"> جميع المنتجات </h3>

<div class="container mt-5 mb-5 shop-container latest-items">
  <?php
  $stm = $conn->prepare("SELECT items.* , 
                                        category.visiblity as cat_visible
                                      FROM 
                                        items
                                      INNER JOIN 
                                        category 
                                      ON 
                                        items.cat_ID = category.cat_ID
                                      WHERE
                                        category.visiblity = 1
                                      AND
                                        status = 1 
                                      ORDER BY time DESC ");
  $stm->execute();
  $items = $stm->fetchAll();

  foreach ($items as $item) {
  ?>
    <a href="product.php?pr-id=<?= $item["item_ID"] ?>" class="item shadow-sm" data-aos="fade-up">
      <div class="itemImg">
        <?php
        $itemID = $item["item_ID"];
        // Get All Images With This item ID
        $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ? LIMIT 1");
        $stm2->execute(array($itemID));
        $img = $stm2->fetch();
        ?>
        <img src="../item_images/<?= $img["img"] ?>">
      </div>
      <div class="itemAbout">
        <div class="name"><?= $item["item_Name"] ?></div>
        <div class="stars">
          <?php
          // Calc The Rating System Stars
          $stm = $conn->prepare("SELECT * FROM comments WHERE item_id = ?");
          $stm->execute(array($item["item_ID"]));

          $cmmnts = $stm->fetchAll();

          if (count($cmmnts) > 0) {
            $num = 0;
            foreach ($cmmnts as $cmmnt) {
              $num = $cmmnt["rating"] + $num;
            }
            $numStars = round($num / count($cmmnts));

            for ($i = 0; $i < $numStars; $i++) {
          ?>
              <i class="fa-solid fa-star" style="color:gold;"></i>
            <?php
            }

            for ($i = 0; $i < 5 - $numStars; $i++) {
            ?>
              <i class="fa-solid fa-star" style="color:#ccc;"></i>
            <?php
            }
          } else {
            ?>
            <i class="fa-solid fa-star" style="color:#ccc;"></i>
            <i class="fa-solid fa-star" style="color:#ccc;"></i>
            <i class="fa-solid fa-star" style="color:#ccc;"></i>
            <i class="fa-solid fa-star" style="color:#ccc;"></i>
            <i class="fa-solid fa-star" style="color:#ccc;"></i>
          <?php

          }

          ?>
        </div>
        <div class="prices">

          <?php
          if ($item["descount"] == 0) {
          ?>
            <div class="new">DA <?= $item["price"] ?></div>
          <?php
          } else {
          ?>
            <div class="old">DA <?= $item["price"] ?></div>
            <div class="new">DA <?= $item["new_price"] ?></div>
            <div class="persent">%<?= $item["descount"] ?></div>
          <?php
          }
          ?>
        </div>
      </div>
    </a>
  <?php
  }
  ?>
</div>


<?php include "templates/footer.php"; ?>

<script>
  AOS.init();
</script>