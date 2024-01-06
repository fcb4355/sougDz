<?php
$pageTitle = "الفئات";
include "init.php";
?>

<?php

$catID = isset($_GET["catID"]) ? $_GET["catID"] : "";


$stm = $conn->prepare("SELECT * FROM category WHERE cat_ID = ?");

$stm->execute(array($catID));

$cat_row = $stm->fetch();
?>

<div class="container">
  <h3 class="content-title"> <?= $cat_row["cat_name"] ?> </h3>
  <?php
  $stm = $conn->prepare("SELECT * FROM items WHERE status = 1 AND cat_ID = ? ORDER BY time DESC ");
  $stm->execute(array($catID));
  $items = $stm->fetchAll();

  if (count($items) > 0) {
  ?>
    <div class="latest-items mt-4 mb-4">
      <?php
      foreach ($items as $item) {
      ?>
        <a href="product.php?pr-id=<?= $item["item_ID"] ?>" class="item shadow-sm">
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
                <div class="new">$<?= $item["price"] ?></div>
              <?php
              } else {
              ?>
                <div class="old">$<?= $item["price"] ?></div>
                <div class="new">$<?= $item["new_price"] ?></div>
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
  <?php
  } else {
  ?>
    <img src="includes/images/empty-search.png" width="550" height="550" class="d-block mx-auto mt-2">
  <?php
  }
  ?>
</div>

<?php include "templates/footer.php"; ?>