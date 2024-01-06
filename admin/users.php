<?php
ob_start();
$pageTitle = "المستخدمين";
include "init.php";

$my_id = $_SESSION["id"];

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
        <div class="barsBox">
            <i class="fa-solid fa-bars"></i>
        </div>
        <div class="page">

            <?php
            $do = isset($_GET['do']) ? $_GET['do'] : "Manage";

            $my_id = $_SESSION['id'];

            if ($do == "Manage") {

                // Get All users From DataBase
                $stm = $conn->prepare("SELECT * FROM users WHERE user_ID != ? ORDER BY time DESC");
                $stm->execute(array($my_id));

                $users = $stm->fetchAll();

            ?>

                <!-- Start Manage Page -->

                <div class="users-page border mt-3">

                    <div class="users-thumbnail">
                        <a href="?do=Add" class="btn btn-primary btn-sm shadow"> اضافة جديد + </a>
                        <div class="dash-title mb-2" dir="rtl">
                            <div class="titl"> كل المستخدمين </div>
                            <span> لديك <?= calcAll("users") - 1 ?> مستخدم (المالك ليس ضمن القائمة) </span>
                        </div>
                    </div>

                    <div class="users-content" dir="rtl">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th> الاسم </th>
                                    <th> البريد الاكتروني </th>
                                    <th> الادوار </th>
                                    <th> تاريخ الانشاء </th>
                                    <th> خيارات </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($users as $user) {
                                ?>
                                    <tr>
                                        <td>
                                            <span> <?= $user["full_Name"] ?> </span>
                                        </td>
                                        <td>
                                            <span> <?= $user["email"] ?> </span>
                                        </td>
                                        <td>
                                            <?php
                                            if ($user["is_Admin"] == 1) {
                                                echo "<span class='admin'> ادمن </span>";
                                            } else {
                                                echo "<span class='user'> زبون </span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span>
                                                <?php
                                                // Category Create Time
                                                $created_date = new DateTime($user["time"]);

                                                // Calculate The Deffrent Time with created time and current time
                                                $diff = $created_date->diff(new DateTime());

                                                $second = $diff->s;
                                                $minuts = $diff->i;
                                                $hours = $diff->h;
                                                $days = $diff->d;
                                                $month = $diff->m;

                                                if ($month !== 0) {
                                                    echo " منذ " . $month . " أشهر";
                                                } elseif ($days !== 0 && $month == 0) {
                                                    echo " منذ " . $days . " يوم";
                                                } elseif ($days == 0 && $hours !== 0) {
                                                    echo "منذ "   . $hours . " ساعة";
                                                } elseif ($days == 0 && $hours == 0 && $minuts !== 0) {
                                                    echo "منذ " . $minuts . " دقيقة";
                                                } else {
                                                    echo "منذ " . $second . " ثانية";
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?do=Edit&userID=<?= $user["user_ID"] ?>" class="text-warning ms-2 fs-5"> <i class="fa-solid fa-edit"></i> </a>
                                            <a href="?do=Delete&userID=<?= $user["user_ID"] ?>" class="text-danger fs-5" onclick="return confirm('هل انت متاكد من حذف المستخدم')"> <i class="fa-solid fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- End Manage Page -->

            <?php
            } elseif ($do == "Add") {

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    // Get Varibales
                    $fullName = filter_var($_POST['fullName'], FILTER_SANITIZE_STRING);
                    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $pass1    = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                    $status   = $_POST["status"];
                    // Ahmed Regoubi

                    if (!empty($fullName) && !empty($email) && !empty($pass1) && $status !== "") {
                        // Ahmed Regoubi
                        if (strlen($fullName) < 5) {
                            $_SESSION["mssg"] = "الاسم قصير";
                        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $_SESSION["mssg"] = "الايميل غير صالح";
                        } elseif (CheckItem("users", "email", $email) > 0) {
                            $_SESSION["mssg"] = "الايميل مستخدم";
                        } elseif (strlen($pass1) < 8) {
                            $_SESSION["mssg"] = "كلمة السر ضعيفة";
                        } else {

                            // Insert Data To DataBase
                            $stm = $conn->prepare("INSERT INTO users (full_Name , email , password , is_Admin , time ) VALUES ( ? , ? , ? , ? , CURRENT_TIME() )");

                            // Execute The Data
                            $stm->execute(array($fullName, $email, $pass1, $status));

                            $_SESSION["mssg"] = "تمت اضافة المستخدم";

                            header("Location: users.php");
                            exit();
                        }
                    } else {
                        $_SESSION["mssg"] = "جميع الحقول اجبارية";

                        header("Location: users.php");
                        exit();
                    }
                }
            ?>
                <!-- Start Add Page -->
                <a href="users.php" class="btn btn-primary btn-sm"> رجوع </a>
                <div class="login-form shadow-sm border" dir="rtl">
                    <h3 dir="rtl"> تسجيل مستخدم جديد</h3>
                    <hr class="m-1">
                    <form action="<?= $_SERVER["PHP_SELF"] . "?do=Add" ?>" method="post">
                        <div class="group">
                            <label> الاسم الكامل</label>
                            <input type="text" name="fullName" placeholder="يوسف ملاوي" required="required">
                        </div>
                        <div class="group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" placeholder="youcef@mellaoui.com" required="required">
                        </div>
                        <div class="group">
                            <label for="password">كلمة المرور</label>
                            <input type="password" name="password" id="password" placeholder="*********" required="required">
                        </div>
                        <div class="group">
                            <label> حالة المستخدم </label>
                            <select name="status" required="required" class="border">
                                <option value="" selected> --- </option>
                                <option value="1"> ادمن </option>
                                <option value="0"> زبون </option>
                            </select>
                        </div>

                        <hr class="m-1">
                        <div class="group">
                            <button type="submit"> تسجيل مستخدم جديد</button>
                        </div>
                    </form>
                </div>

                <!-- End Add Page -->
                <?php
            } elseif ($do == "Edit") {

                if (isset($_GET["userID"]) && isset($_SERVER["HTTP_REFERER"])) {

                    $user_id = $_GET["userID"];

                    // Get All Info From DataBase With Get Request id.
                    $stm = $conn->prepare("SELECT * FROM users WHERE user_ID = ?");
                    $stm->execute(array($user_id));

                    $results = $stm->fetch();

                ?>
                    <!-- Start Edit Page -->

                    <a href="users.php" class="btn btn-primary btn-sm"> رجوع </a>

                    <div class="login-form shadow-sm border" dir="rtl">
                        <h3 dir="rtl"> تعديل المستخدم</h3>
                        <hr class="m-1">
                        <form action="?do=Update" method="post">

                            <input type="hidden" name="userID" value="<?= $_GET["userID"] ?>">

                            <div class="group">
                                <label> الاسم الكامل</label>
                                <input type="text" name="fullName" placeholder="يوسف ملاوي" value="<?php echo $results["full_Name"] ?>" required="required">
                            </div>
                            <div class="group">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" placeholder="youcef@mellaoui.com" value="<?php echo $results["email"] ?>" required="required">
                            </div>
                            <div class="group">
                                <label for="password">كلمة المرور</label>
                                <input type="password" name="password" id="password" placeholder="*********" value="<?php echo $results["password"] ?>" required="required">
                            </div>
                            <div class="group">
                                <label> حالة المستخدم </label>
                                <select name="status" required="required" class="border">
                                    <option value="1" <?php echo $results["is_Admin"] == 1 ? "selected" : "" ?>> ادمن </option>
                                    <option value="0" <?php echo $results["is_Admin"] ==  0 ? "selected" : "" ?>> زبون </option>
                                </select>
                            </div>
                            <hr class="m-1">
                            <div class="group">
                                <button type="submit"> تعديل </button>
                            </div>
                        </form>
                    </div>

                    <!-- End Edit Page -->
                <?php
                } else {
                ?>
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="users.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
                        </div>

                    </div>
                <?php
                }
                ?>
                <?php
            } elseif ($do == "Update") {
                if (isset($_SERVER["HTTP_REFERER"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

                    // Get Varibales
                    $userID = $_POST["userID"];
                    $fullName = filter_var($_POST['fullName'], FILTER_SANITIZE_STRING);
                    $email    = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $pass1    = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                    $status   = $_POST["status"];

                    // Get All user withou this get request id
                    $stm = $conn->prepare("SELECT * FROM users WHERE user_ID != ? AND email = ?");

                    $stm->execute(array($userID, $email));

                    $rows = $stm->fetchAll();

                    $count_exist = count($rows);

                    if (!empty($fullName) && !empty($email) && !empty($pass1) && $status != "") {

                        if (strlen($fullName) < 5) {
                            $_SESSION["mssg"] = "الاسم قصير";
                            header("Location: users.php");
                            exit();
                        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $_SESSION["mssg"] = "الايميل غير صالح";
                            header("Location: users.php");
                            exit();
                        } elseif ($count_exist > 0) {
                            $_SESSION["mssg"] = "الايميل مستخدم";
                            header("Location: users.php");
                            exit();
                        } elseif (strlen($pass1) < 8) {
                            $_SESSION["mssg"] = "كلمة السر ضعيفة";
                            header("Location: users.php");
                            exit();
                        } else {
                            $stm2 = $conn->prepare("UPDATE 
                                            users 
                                        SET 
                                            full_Name = ? ,
                                            email = ? ,
                                        password = ? ,
                                            is_Admin = ?
                                        WHERE 
                                            user_ID = ? ");

                            $stm2->execute(array($fullName, $email, $pass1, $status, $userID));

                            $_SESSION["mssg"] = "تم التعديل بنجاح";

                            header("Location: users.php");
                            exit();
                        }
                    } else {
                        $_SESSION["mssg"] = "جميع الحقول اجبارية";

                        header("Location: users.php");
                        exit();
                    }
                } else {
                ?>
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="users.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
                        </div>

                    </div>
                <?php
                }
            } elseif ($do == "Delete") {

                if (isset($_GET["userID"]) && isset($_SERVER["HTTP_REFERER"])) {

                    $user_id = $_GET["userID"];

                    $stm = $conn->prepare("DELETE FROM users WHERE user_ID = ?");

                    $stm->execute(array($user_id));

                    $_SESSION["mssg"] = "تمت خذف المستخدم";

                    header("Location: users.php");
                    exit();
                } else {
                ?>
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="users.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
                        </div>
                    </div>
            <?php
                }
            }
            ?>

        </div>

        <div class="sideBar shadow">
            <div class="side-bar-content">
                <h1 class="side-title text-center mb-3">
                    لوحة التحكم
                </h1>
                <hr class="m-1">
                <ul class="links" dir="rtl">
                    <li>
                        <a href="dashboard.php">
                            <i class="fa-solid fa-home ms-2"></i>
                            الرئيسية
                        </a>
                    </li>
                    <li class="current_link">
                        <a href="users.php">
                            <i class="fa-solid fa-users ms-2"></i>
                            المستخدمين
                        </a>
                    </li>
                    <li>
                        <a href="category.php">
                            <i class="fa-solid fa-list ms-2"></i>
                            الفئات
                        </a>
                    </li>
                    <li>
                        <a href="items.php">
                            <i class="fa-solid fa-bag-shopping ms-2"></i>
                            المنتجات
                        </a>
                    </li>
                    <li>
                        <a href="orders.php">
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