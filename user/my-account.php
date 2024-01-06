<?php
$pageTitle = "تعديل حسابي";
include "init.php";
?>

<?php
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}
?>

<?php

$id = $_SESSION["id"];

$stm = $conn->prepare("SELECT * FROM users WHERE user_ID = ?");

$stm->execute(array($id));

$user = $stm->fetch();

?>


<div class="container mt-4 mb-4 profile">

    <div class="profile-edit">

        <!-- Form 1 - Update The Name -->
        <form class="form p-2 shadow-sm border" dir="rtl" method="post" action="update.php?updName" data-aos="fade-up">
            <div class="form-title"> تعديل معلومات الحساب </div>
            <hr class="mt-1 mb-1">

            <div class="group">
                <label> الاسم </label>
                <input type="text" value="<?= $user["full_Name"] ?>" class="border" dir="ltr" name="name">
            </div>

            <div class="group">
                <label> الهاتف </label>
                <input type="text" placeholder="+213634772244" class="border" name="phone" value="<?php echo $user["phone"] != 0 ?  "0" . $user["phone"]  : "" ?>">
            </div>
            <div class="footer">
                <div class="request">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"> تحديث </button>
            </div>
        </form>

        <!-- Form 2 - Update The Email -->
        <form class="form p-2 shadow-sm border" dir="rtl" method="post" action="update.php?updEmail" data-aos="fade-up">
            <div class="form-title"> تعديل البريد الإلكتروني </div>
            <hr class="mt-1 mb-1">
            <div class="group">
                <label> البريد الإلكتروني الحالي </label>
                <input type="email" value="<?= $user["email"] ?>" class="border" dir="ltr">
            </div>
            <div class="group">
                <label> البريد الإلكتروني الجديد </label>
                <input type="email" placeholder="<?= $user["email"] ?>" class="border" dir="ltr" name="email">
            </div>
            <div class="group">
                <label> كلمة المرور </label>
                <input type="password" placeholder="*******" class="border" name="password">
            </div>

            <div class="footer">
                <div class="request">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"> تحديث </button>
            </div>
        </form>

        <!-- Form 3 - Update The Password -->
        <form class="form p-2 shadow-sm border" dir="rtl" method="post" action="update.php?updpass" data-aos="fade-up">
            <div class="form-title">تعديل كلمة السر </div>
            <hr class="mt-1 mb-1">

            <div class="group">
                <label> كلمة السر القديمة </label>
                <input type="password" placeholder="*******" class="border" name="current_pass">
            </div>

            <div class="group">
                <label> كلمة السر الجديدة </label>
                <input type="password" placeholder="*******" class="border" name="new_pass">
            </div>

            <div class="group">
                <label> تاكيد كلمة السر </label>
                <input type="password" placeholder="*******" class="border" name="confirm_pass">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"> تحديث </button>
        </form>

    </div>

    <!-- Side Bar Menu Links -->
    <div class="profile-menu p-2 border shadow-sm" dir="rtl">
        <span class="mb-2 d-block text-center" style="color:var(--smoke-color); font-size:15px">القائمة</span>
        <hr class="mt-1 mb-1">
        <ul class="list">
            <li>
                <a href="home-page.php"> الصفحة الرئيسية </a>
            </li>
            <li>
                <a href="my-orders.php"> طلباتي </a>
            </li>
            <li>
                <a href="my-account.php" class="active_link">حسابي </a>
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

<script>
    AOS.init();
</script>