<?php
$pageTitle = "Sign up";
include "init.php";


if (isset($_SESSION["id"])) {
    // Redirect To Home Page And Login
    header("Location: index.php");
    exit();
}

?>


<!-- Start Coding PHP -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get Varibales
    $fullName = filter_var($_POST['fullName'], FILTER_SANITIZE_STRING);
    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass1    = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $pass2    = filter_var($_POST['confirm_Password'], FILTER_SANITIZE_STRING);


    if (!empty($fullName) && !empty($email) && !empty($pass1) && !empty($pass2)) {

        if (strlen($fullName) < 5) {
            $_SESSION["mssg"] = "الاسم قصير";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["mssg"] = "الايميل غير صالح";
        } elseif (CheckItem("users", "email", $email) > 0) {
            $_SESSION["mssg"] = "الايميل مستخدم";
        } elseif (strlen($pass1) < 8) {
            $_SESSION["mssg"] = "كلمة السر ضعيفة";
        } elseif ($pass1 !== $pass2) {
            $_SESSION["mssg"] = "كلمة السر غير متطابقة";
        } else {

            // Insert Data To DataBase
            $stm = $conn->prepare("INSERT INTO users (full_Name , email , password ,time) VALUES ( ? , ? , ?, CURRENT_TIME() )");

            // Execute The Data
            $stm->execute(array($fullName, $email, $pass1));

            // Redirect To Home Page And Login
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION["mssg"] = "كل الحقول اجبارية";
    }
}
?>
<!-- End Coding PHP -->


<!-- Start Login Form -->

<div class="container">
    <div class="login-form shadow-sm" dir="rtl">
        <h3 dir="rtl">تسجيل حساب جديد</h3>
        <hr class="m-1">

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <!-- Full Name -->
            <div class="group">
                <label for="full">الاسم الكامل</label>
                <input type="text" name="fullName" id="full" placeholder="ملاوي يوسف">
            </div>

            <!-- Email -->
            <div class="group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" placeholder="youcef@mellaoui.com">
            </div>

            <!-- Password -->
            <div class="group">
                <label for="password">كلمة المرور</label>
                <input type="password" name="password" id="password" placeholder="*********">
            </div>

            <!-- Confirm Password -->
            <div class="group">
                <label for="password">تأكيد كلمة المرور</label>
                <input type="password" name="confirm_Password" id="password" placeholder="*********">
            </div>


            <hr class="m-1">

            <!-- Submit Button -->
            <div class="group">
                <button type="submit">تسجيل حساب جديد</button>
            </div>

        </form>
    </div>
</div>

<!-- End Login Form -->

<?php include "templates/footer.php"; ?>

<?php
if (isset($_SESSION["mssg"])) {
?>
    <script>
        swal({
            title: "<?= $_SESSION["mssg"] ?>",
        });
    </script>
<?php
    unset($_SESSION["mssg"]);
}
?>