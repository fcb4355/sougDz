<?php
$pageTitle = "Login";
include "init.php";


if (!isset($_SESSION["id"])) {
  // Redirect To Home Page And Login
  header("Location: index.php");
  exit();
}
?>


<?php
if (isset($_SERVER['HTTP_REFERER'])) {


  // #############################
  $id = $_SESSION['id'];

  $stm = $conn->prepare("SELECT * FROM users WHERE user_ID = ?");

  $stm->execute(array($id));

  $user = $stm->fetch();


  // :: Star Update Name ::
  if (isset($_GET['updName'])) {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $name  = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
      $phone = filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT);

      if (!empty($name)) {

        $stm = $conn->prepare("UPDATE 
                                  users 
                              SET 
                                  full_Name = ? , 
                                  phone = ? 
                              WHERE 
                                  user_ID = ?");

        $stm->execute(array($name, $phone, $id));

        $_SESSION["mssg"] = "تم التحديث بنجاح";

        header("Location:my-account.php");
        exit();
      } else {

        $_SESSION["mssg"] = "الاسم اجباري";

        header("Location:my-account.php");
        exit();
      }
    }
  }
  // :: End Update Name ::
  // :: Start Update Email ::
  if (isset($_GET['updEmail'])) {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
      $email     = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
      $password  = htmlspecialchars($_POST["password"]);

      if (!empty($email) && !empty($password)) {

        $stm = $conn->prepare("SELECT * FROM users WHERE user_ID != ? AND email = ?");

        $stm->execute(array($id, $email));

        $row = $stm->fetchAll();

        $count = count($row);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $_SESSION["mssg"] = "ايميل غير صالح";

          header("Location:my-account.php");
          exit();
        } elseif ($count > 0) {

          $_SESSION["mssg"] = "هذا الاميل مستعمل";

          header("Location:my-account.php");
          exit();
        } elseif ($password !== $user["password"]) {
          $_SESSION["mssg"] = "كلمة السر غير صحيحة";

          header("Location:my-account.php");
          exit();
        } else {
          $stm = $conn->prepare("UPDATE 
                                    users 
                                  SET 
                                    email = ?
                                  WHERE 
                                    user_ID = ?
                                  AND 
                                    password = ?");

          $stm->execute(array($email, $id, $password));

          $_SESSION["mssg"] = "تم التحديث بنجاح";

          header("Location:my-account.php");
          exit();
        }
      } else {
        $_SESSION["mssg"] = "جميع الحقول اجباية";

        header("Location: my-account.php");
        exit();
      }
    }
  }
  // :: End Update Email ::
  // :: Star Update PAssword ::
  if (isset($_GET['updpass'])) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

      $password = $user["password"];

      $current_pass_field = $_POST['current_pass'];
      $new_pass = $_POST['new_pass'];
      $confirm_pass = $_POST['confirm_pass'];

      if (!empty($current_pass_field) && !empty($new_pass) && !empty($confirm_pass)) {

        if ($password !== $current_pass_field) {

          $_SESSION["mssg"] = "كلمة السر غير صحيحة";

          header("Location: my-account.php");
          exit();
        } elseif ($new_pass !== $confirm_pass) {
          $_SESSION["mssg"] = "كلمة السر غير متطابقة";

          header("Location: my-account.php");
          exit();
        } else {
          $stm = $conn->prepare("UPDATE users SET password = ? WHERE user_ID = ?");

          $stm->execute(array($new_pass, $id));

          $_SESSION["mssg"] = "تم التحديث بنجاح";

          header("Location: my-account.php");
          exit();
        }
      } else {
        $_SESSION["mssg"] = "جميع الحقول اجباية";

        header("Location: my-account.php");
        exit();
      }
    }
  }
} else {
?>
  <div class="container page_404">
    <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

    <div class="not-found p-2 mt-4 text-center">
      <a href="index.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
    </div>

  </div>
<?php
}
?>


<?php include "templates/footer.php"; ?>