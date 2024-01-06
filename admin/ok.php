<?php
$noNavBar = "";
$pageTitle = "Dashboard";
include "init.php";

if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 0) {  // ###################### if isset session And user is not Admin
?>
  <!-- if Not Admin -->
  <div class="container page_404">
    <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

    <div class="not-found p-2 mt-4 text-center">
      <span class="shadow"> 403 | USER DOES NOT HAVE THE RIGHT PERMISSIONS. </span>
      <a href="../user/index.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
    </div>

  </div>
<?php
} elseif (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {  // ##################### if isset session and user is admin
?>

  <div class="dashboard">
    <div class="page">

      <?php


      function RemoveItem()
      {
        global $conn;
        $stm = $conn->prepare("DELETE FROM users WHERE user_ID = 14");
        $stm->execute();
        return "Remove Succes";
      }


      // #############################################################################
      // #############################################################################
      // #############################################################################
      // #############################################################################


      // Category Create Time
      $created_date = new DateTime("2023-12-04 15:00:00");

      // Calculate The Deffrent Time with created time and current time
      $diff = $created_date->diff(new DateTime());

      $second = $diff->s;
      $minuts = $diff->i;
      $hours = $diff->h;
      $days = $diff->d;
      $month = $diff->m;

      echo $days . " Days <br>";
      echo $hours . " hours <br>";
      echo $minuts . " minutes <br>";
      echo $second . " seconds <br>";
      echo $month . " month <br>";

      echo "################ <br>";

      if ($month !== 0) {
        echo $month . " month" . "<br>";
      } elseif ($days !== 0 && $month == 0) {
        echo $days . " Days" . "<br>";
      } elseif ($days == 0 && $hours !== 0) {
        echo $hours . " hours" . "<br>";
      } elseif ($days == 0 && $hours == 0 && $minuts !== 0) {
        echo $minuts . " minuts" . "<br>";
      } else {
        echo $second . " second" . "<br>";
      }


      // #############################################################################
      // #############################################################################
      // #############################################################################
      // #############################################################################


      // To Separate The Date And Time From The DateTime.
      $s = strtotime('8/29/2011 11:16:12 AM');

      // Get The Date From DAteTime
      $date = date('m/d/Y', $s);
      // Get The Time From DAteTime
      $time = date('H:i:s A', $s);


      // #############################################################################
      // #############################################################################
      // #############################################################################
      // #############################################################################

      // Create Code tracking Of order.
      $first = "YM";
      $scnd = random_int(1000, 4000);

      function generate()
      {
        $character = "AZERTYUIOPQSDFGHJKLMWXCVBN";

        $char = "";

        for ($i = 0; $i < 3; $i++) {
          $char = $character[rand(0, strlen($character) - 1)] . $character[rand(0, strlen($character) - 1)] . $character[rand(0, strlen($character) - 1)];
        }

        return $char;
      }

      $traking = $first . "-" . $scnd . generate();

      echo $traking;

      ?>


    </div>

    <div class="sideBar shadow">
      <div class="side-bar-content">
        <h1 class="side-title text-center mb-3">
          لوحة التحكم
        </h1>
        <ul class="links" dir="rtl">
          <li>
            <a href="dashboard.php">
              <i class="fa-solid fa-home ms-2"></i>
              الرئيسية
            </a>
          </li>
          <li>
            <a href="users.php">
              <i class="fa-solid fa-users ms-2"></i>
              المستخدمين
            </a>
          </li>
          <li>
            <a href="">
              <i class="fa-solid fa-list ms-2"></i>
              الفئات
            </a>
          </li>
          <li>
            <a href="">
              <i class="fa-solid fa-bag-shopping ms-2"></i>
              المنتجات
            </a>
          </li>
          <li>
            <a href="">
              <i class="fa-solid fa-cart-shopping ms-2"></i>
              الطلبات
            </a>
          </li>
        </ul>
      </div>
      <div class="toMarket">
        <a href="../user/index.php" class="btn btn-primary btn-sm d-block"> زيارة الموقع </a>
      </div>
    </div>
  </div>

<?php
} else {   // ############################## if not isset session 
  header("Location: ../user/login.php");
  exit();
}
?>


<?php include "templates/footer.php" ?>