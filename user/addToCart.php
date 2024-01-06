<?php
$pageTitle = " ";
include "init.php";
?>

<?php

if (isset($_SESSION["id"])) {

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["pr-id"]) && isset($_SERVER["HTTP_REFERER"])) {

    $count = $_POST["token"];
    $referer = $_SERVER["HTTP_REFERER"];
    $productID = $_GET["pr-id"];
    $userID = $_SESSION["id"];

    // Check if isset This item in dataBase Basket
    $stm = $conn->prepare("SELECT * FROM basket WHERE item_id = ? AND user_id = ? AND isOrdering = 0");
    $stm->execute(array($productID, $userID));

    $check = $stm->fetchAll();

    if (count($check) > 0) { // if isset This product in basket => increment ++  just The Quantity of this product 

      $referer = "";
      if (isset($_SERVER["HTTP_REFERER"])) {
        $referer = $_SERVER["HTTP_REFERER"];
      }

      $new_Quantity = $check[0]["Quantity"] + $count;

      $stm_upd = $conn->prepare("UPDATE basket SET Quantity = ? WHERE item_id = ? AND user_id = ? AND isOrdering = 0");

      $stm_upd->execute(array($new_Quantity, $productID, $userID));

      $_SESSION["mssg"] = "تمت اضافة المنتج الى السلة";

      header("Location:$referer");
      exit();
    } else {

      $referer = "";
      if (isset($_SERVER["HTTP_REFERER"])) {
        $referer = $_SERVER["HTTP_REFERER"];
      }

      $stm1 = $conn->prepare("INSERT INTO basket (Quantity , item_id , user_id) VALUES (? , ? , ?)");

      $stm1->execute(array($count, $productID, $userID));

      $_SESSION["mssg"] = "تمت اضافة المنتج الى السلة";

      $_SESSION["basketCount"] = $_SESSION["basketCount"] + 1;

      header("Location:$referer");
      exit();
    }
  } else {
?>
    <div class="container page_404 mt-3">

      <div class="error bg-light text-dark border text-center p-1 rounded-1 mb-3"> عذرا الصفحة غير متوفرة </div>

      <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

      <div class="not-found p-2 mt-4 text-center">
        <a href="login.php" class="mt-0 btn btn-primary btn-sm text-light"> الصفحة الرئيسية </a>
      </div>

    </div>
<?php
  }
} else {
  header("Location: login.php");
  exit();
}
?>


<?php include "templates/footer.php"; ?>