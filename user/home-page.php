<?php
$pageTitle = "الصفحة الرئيسية";
include "init.php";
?>

<?php
if (!isset($_SESSION["id"])) {
  header("Location: login.php");
  exit();
}
?>

<div class="container mt-4 mb-4 profile">

  <div class="profile-edit">
    <div class="welcome border shadow-sm text-center" dir="rtl">
      مرحبا
      بك <span> <?php echo isset($_SESSION["fullName"]) ? $_SESSION["fullName"] : "" ?> </span>
      في حسابك
      <img src="includes/images/hello.jpg" width="100%" height="500">
    </div>
  </div>

  <div class="profile-menu p-2 border shadow-sm" dir="rtl">
    <span class="mb-2 d-block text-center" style="color:var(--smoke-color); font-size:15px">القائمة</span>
    <hr class="mt-1 mb-1">
    <ul class="list">
      <li>
        <a href="home-page.php" class="active_link"> الصفحة الرئيسية </a>
      </li>
      <li>
        <a href="my-orders.php"> طلباتي </a>
      </li>
      <li>
        <a href="my-account.php">حسابي </a>
      </li>
      <li>
        <a href="tracking.php"> تتبع الطلب </a>
      </li>
      <li>
        <a href="logout.php"> تسجيل الخروج </a>
      </li>
    </ul>
  </div>
</div>

<?php include "templates/footer.php"; ?>