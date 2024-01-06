<?php
$noNavBar = "";
$pageTitle = "الفئات";
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
        <div class="barsBox">
            <i class="fa-solid fa-bars"></i>
        </div>

        <div class="page">
            <?php

            $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

            if ($do == "Manage") { // ######################## Page Manage Category

                $stm = $conn->prepare("SELECT * FROM category ORDER BY time DESC");
                $stm->execute();

                $cats = $stm->fetchAll();

            ?>
                <!-- Start Manage Page -->

                <div class="users-page border container mt-3 cat_page">
                    <div class="users-thumbnail">
                        <a href="?do=Add" class="btn btn-primary btn-sm shadow"> اضافة جديد + </a>
                        <div class="dash-title mb-2" dir="rtl">
                            <div class="titl"> كل الفئات </div>
                            <span> لديك <?= calcAll("category") ?> فئات </span>
                        </div>
                    </div>
                    <div class="users-content">
                        <table class="table" dir="rtl">
                            <thead>
                                <tr>
                                    <th> الاسم </th>
                                    <th> عدد المنتجات </th>
                                    <th> الصورة </th>
                                    <th class="text-center"> فئة مرئية </th>
                                    <th class="text-center"> السماح بالنشر </th>
                                    <th> تاريخ الانشاء </th>
                                    <th> خيارات </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($cats as $cat) {
                                ?>
                                    <tr>
                                        <td>
                                            <span> <?= $cat["cat_name"] ?> </span>
                                        </td>
                                        <td>
                                            <span>
                                                <?php
                                                if (CalcItemsInCatg($cat["cat_ID"]) == 0) {
                                                    echo "لا يوجد";
                                                } else {
                                                    echo CalcItemsInCatg($cat["cat_ID"]) . " منتجات";
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <img class="border shadow-sm rounded-pill" src="../category_images/<?= $cat["cat_img"] ?>" alt="category-img" width="60" height="60" style="object-fit: cover;">
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($cat["visiblity"] == 1) {
                                                echo "<span class='yes'>نعم</span>";
                                            } else {
                                                echo "<span class='no'>لا</span>";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($cat["allow_ads"] == 1) {
                                                echo "<span class='yes'>نعم</span>";
                                            } else {
                                                echo "<span class='no'>لا</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span>
                                                <?php
                                                // Category Create Time
                                                $created_date = new DateTime($cat["time"]);

                                                // Calculate The Deffrent Time with created time and current time
                                                $diff = $created_date->diff(new DateTime());

                                                $second = $diff->s; // seconds
                                                $minuts = $diff->i;  // minuts
                                                $hours = $diff->h;  // hours
                                                $days = $diff->d;   // days
                                                $month = $diff->m; // months

                                                if ($month !== 0) {
                                                    echo " منذ " . $month . " أشهر";
                                                } elseif ($days !== 0 && $month == 0) {
                                                    echo " منذ " . $days . " يوم";
                                                } elseif ($days == 0 && $hours !== 0) {
                                                    echo "منذ " . $hours . " ساعة";
                                                } elseif ($days == 0 && $hours == 0 && $minuts !== 0) {
                                                    echo "منذ " . $minuts . " دقيقة";
                                                } else {
                                                    echo "منذ " . $second . " ثانية";
                                                }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?do=Edit&catID=<?= $cat["cat_ID"] ?>" class="text-warning ms-2 fs-5"> <i class="fa-solid fa-edit"></i> </a>
                                            <a href="?do=Delete&catID=<?= $cat["cat_ID"] ?>" class="text-danger fs-5" onclick="return confirm('تحذير : اذ قمت بحذف الفئة سيتم حذف كل المنتجات المضافة الى  هذه الفئة هل انت متاكد من الحذف')"> <i class="fa-solid fa-trash"></i> </a>
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
            } elseif ($do == "Add") { // ######################## Page Add Category 

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $name               = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
                    $visible              = $_POST["visible"];
                    $allow_ads         = $_POST["ads"];

                    $file          = $_FILES["img"];
                    // Info Files
                    $img_name  = $file["name"];
                    $img_tmp   = $file["tmp_name"];
                    $img_error = $file["error"];

                    if (!empty($name) && $img_error == 0 && $visible !== "" && $allow_ads !== "") {

                        $arr_extension = array("jpg", "png", "jpeg", "gif");

                        $exp_arr = explode(".", $img_name);

                        $exp = strtolower(end($exp_arr));

                        if (!in_array($exp, $arr_extension)) {
                            echo showMssg("You Don't Select Image", "danger");
                        } else {

                            // Rename Image Selected And move it in our folder project
                            $time = time();

                            $n_name = $time . $img_name;

                            move_uploaded_file($img_tmp, "../category_images/" . $n_name);

                            // Start Add To DataBase
                            $stm = $conn->prepare("INSERT INTO 
                    category (cat_name , cat_img , visiblity , allow_ads , time) VALUES 
                        (? , ? , ? , ? , ? , CURRENT_TIME() )");

                            $stm->execute(array($name, $n_name, $visible, $allow_ads));

                            $_SESSION["mssg"] = "تمت اضافة الفئة";

                            header("Location: category.php");
                            exit();
                        }
                    } else {
                        $_SESSION["mssg"] = "كل الحقول مطلوبة";

                        header("Location: category.php");
                        exit();
                    }
                }
            ?>
                <!-- Start Form Add Category -->

                <a href="category.php" class="btn btn-primary btn-sm"> رجوع </a>

                <div class="login-form shadow-sm border" dir="rtl">
                    <h3 dir="rtl"> اضافة فئة جديد</h3>
                    <hr class="m-1">

                    <form action="<?= $_SERVER["PHP_SELF"] . "?do=Add" ?>" method="post" enctype="multipart/form-data">

                        <!-- Name of Category -->
                        <div class="group">
                            <label> اسم الفئة </label>
                            <input type="text" name="name" placeholder="اسم الفئة" required="required">
                        </div>

                        <!-- image of Category -->
                        <div class="group">
                            <label> صورة الفئة </label>
                            <input type="file" name="img" required="required">
                        </div>

                        <!-- Visibility of Category -->
                        <div class="group">
                            <label> فئة مرئية </label>
                            <select name="visible" required="required" class="border">
                                <option value="" selected> --- </option>
                                <option value="0"> لا </option>
                                <option value="1"> نعم </option>
                            </select>
                        </div>

                        <!-- Allow Ads of Category -->
                        <div class="group">
                            <label> السماح بالنشر </label>
                            <select name="ads" required="required" class="border">
                                <option value="" selected> --- </option>
                                <option value="0"> لا </option>
                                <option value="1"> نعم </option>
                            </select>
                        </div>
                        <hr class="m-1">

                        <!-- Button Submit -->
                        <div class="group">
                            <button type="submit"> اضافة </button>
                        </div>
                    </form>
                </div>

                <!-- End Form Add Category -->
                <?php
            } elseif ($do == "Edit") { // ######################## Page Edit Category

                if (isset($_GET["catID"]) && isset($_SERVER["HTTP_REFERER"])) {

                    $catID = $_GET["catID"];

                    // Get All Info From DataBase With Get Request id.
                    $stm = $conn->prepare("SELECT * FROM category WHERE cat_ID = ?");
                    $stm->execute(array($catID));

                    $results = $stm->fetch();

                ?>
                    <!-- Start Edit Page -->


                    <div class="login-form shadow-sm border mt-0" dir="rtl">

                        <h3 dir="rtl" class="d-flex justify-content-between align-items-end">
                            <div class="about d-flex align-items-end gap-2">
                                <img src="../category_images/<?= $results["cat_img"] ?>" width="80" height="80" class="border">
                                تعديل الفئة
                            </div>
                            <a href="category.php" class="btn btn-primary btn-sm"> رجوع </a>
                        </h3>

                        <hr class="m-1">

                        <form action="?do=Update" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="catID" value="<?= $results["cat_ID"] ?>">
                            <input type="hidden" name="catImg" value="<?= $results["cat_img"] ?>">

                            <!-- Name of Category -->
                            <div class="group">
                                <label> اسم الفئة </label>
                                <input type="text" name="cat_name" placeholder="اسم الفئة" required="required" value="<?= $results["cat_name"] ?>">
                            </div>

                            <!-- image of Category -->
                            <div class="group">
                                <label> صورة الفئة </label>
                                <input type="file" name="img">
                            </div>

                            <!-- Visibility of Category -->
                            <div class="group">
                                <label> فئة مرئية </label>
                                <select name="visible" required="required" class="border">
                                    <option value="0" <?= $results["visiblity"] == 0 ? "selected" : "" ?>> لا </option>
                                    <option value="1" <?= $results["visiblity"] == 1 ? "selected" : "" ?>> نعم </option>
                                </select>
                            </div>

                            <!-- Allow Ads of Category -->
                            <div class="group">
                                <label> السماح بالنشر </label>
                                <select name="ads" required="required" class="border">
                                    <option value="0" <?= $results["allow_ads"] == 0 ? "selected" : "" ?>> لا </option>
                                    <option value="1" <?= $results["allow_ads"] == 1 ? "selected" : "" ?>> نعم </option>
                                </select>
                            </div>

                            <hr class="m-1">

                            <!-- Button Submit -->
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

            } elseif ($do == "Update") { // ######################## Page update Category

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $catID   = $_POST["catID"];
                    $catImg  = $_POST["catImg"];
                    $name    = htmlspecialchars($_POST["cat_name"]);
                    $visible = $_POST["visible"];
                    $ads     = $_POST["ads"];

                    $file    = $_FILES["img"];
                    // Info Files
                    $img_name  = $file["name"];
                    $img_tmp   = $file["tmp_name"];
                    $img_error = $file["error"];

                    if (!empty($name)) {

                        $n_img = "";

                        if ($img_error !== 0) {

                            $n_img = $catImg;
                        } else {

                            $arr_extension = array("jpg", "png", "jpeg", "gif");

                            $exp_arr = explode(".", $img_name);

                            $exp = strtolower(end($exp_arr));

                            if (!in_array($exp, $arr_extension)) {
                                echo showMssg("You Don't Select Image", "danger");
                            } else {

                                // Rename Image Selected And move it in our folder project
                                $time = time();

                                $n_name = $time . $img_name;

                                unlink("../category_images/$catImg"); // Remove The Old img From Admin Folder.

                                // Move The New img Selected To Folder Project.
                                move_uploaded_file($img_tmp, "../category_images/" . $n_name);

                                $n_img = $n_name;
                            }
                        }

                        // Start Add To DataBase
                        $stm = $conn->prepare(" UPDATE 
                                            category 
                                        SET 
                                            cat_name   = ? ,
                                            cat_img    = ? ,
                                        visiblity = ? ,
                                            allow_ads  = ?
                                        WHERE
                                            cat_ID     = ?
                                            ");

                        $stm->execute(array($name, $n_img, $visible, $ads, $catID));

                        $_SESSION["mssg"] = "تمت تعديل الفئة";

                        header("Location: category.php");
                        exit();
                    } else {
                        $_SESSION["mssg"] = "كل الحقول اجبارية";

                        header("Location: category.php");
                        exit();
                    }
                } else {
                ?>
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="category.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
                        </div>

                    </div>
                <?php
                }
            } elseif ($do == "Delete") { // ######################## Page Delete Category 

                if (isset($_GET["catID"]) && isset($_SERVER["HTTP_REFERER"])) {

                    $catID = $_GET["catID"];

                    $stm = $conn->prepare("DELETE FROM category WHERE cat_ID = ? ");
                    $stm->execute(array($catID));

                    $_SESSION["mssg"] = "تمت حذف الفئة";

                    header("Location: category.php");
                    exit();
                } else {
                ?>
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="category.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
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
                    <li class="current_link">
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