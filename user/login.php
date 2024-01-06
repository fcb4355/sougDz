<?php
$pageTitle = "Login";
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

    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);


    if (!empty($email) && !empty($password)) {

        $stm = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");

        $stm->execute(array($email, $password));

        $row = $stm->fetchAll();

        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";

        $count = count($row);

        if ($count > 0) {

            // Start SESSION
            $_SESSION['id'] = $row[0]["user_ID"];
            $_SESSION['fullName'] = $row[0]["full_Name"];
            $_SESSION['email'] = $row[0]["email"];
            $_SESSION['date'] = $row[0]["date"];
            $_SESSION["isAdmin"] = $row[0]["is_Admin"];

            // Redirect To Home Page And Login
            header("Location: index.php");
            exit();
        } else {
            $_SESSION["mssg"] = "المستخدم غير موجود";
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
        <h3 dir="rtl">تسجيل الدخول</h3>
        <hr class="m-1">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" placeholder="youcef@mellaoui.com">
            </div>
            <div class="group">
                <label for="password">كلمة المرور</label>
                <input type="password" name="password" id="password" placeholder="*********">
            </div>
            <hr class="m-1">
            <div class="group">
                <button type="submit">تسجيل الدخول</button>
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