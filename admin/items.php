<?php
$noNavBar = "";
$pageTitle = "المنتجات";
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

        <div class="page p-0 products-box">
            <?php
            $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

            if ($do == "Manage") { // ################################## Manage Page
            ?>
                <!-- Start Manage Page -->

                <div class="users-page  page border mt-3 items_page">

                    <div class="users-thumbnail">
                        <a href="?do=Add" class="btn btn-primary btn-sm shadow"> اضافة جديد + </a>
                        <div class="dash-title mb-2" dir="rtl">
                            <div class="titl"> كل المنتجات </div>
                            <span> لديك <?= calcAll("items") ?> منتج</span>
                        </div>
                    </div>
                    <div class="users-content" dir="rtl">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th> الصورة </th>
                                    <th> الاسم </th>
                                    <th> المخزون </th>
                                    <th> السعر </th>
                                    <th> نسبة التخفيض </th>
                                    <th> السعر الجديد </th>
                                    <th> الفئات </th>
                                    <th> الحالة </th>
                                    <th> تاريخ </th>
                                    <th> خيارات </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                // Get All Items
                                $stm = $conn->prepare("  SELECT items.* , 
                                category.cat_name as cat_name 
                            FROM 
                                items 
                            INNER JOIN 
                                category 
                            ON 
                                items.cat_ID = category.cat_ID
                            ORDER BY
                                time DESC");

                                $stm->execute();

                                $items = $stm->fetchAll();
                                foreach ($items as $item) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $item_id =  $item["item_ID"];
                                            // Get Photo With item_id.
                                            $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ? LIMIT 1");
                                            $stm2->execute(array($item_id));
                                            $img = $stm2->fetchAll();

                                            if (count($img) > 0) {
                                                $src = $img[0]["img"];
                                                echo "<img src='../item_images/$src' width='50' height='50' style='object-fit:cover' class='border shadow-sm'>";
                                            } else {
                                                echo "لا يوجد صور";
                                            }
                                            ?>
                                        </td>
                                        <td><?= $item["item_Name"] ?></td>
                                        <td> متوفر <?= $item["store"] ?></td>
                                        <td><?= $item["price"] ?> DA</td>
                                        <td>
                                            <?php
                                            if ($item["descount"] == 0) {
                                                echo "لا يوجد تخفيض";
                                            } else {
                                                echo $item["descount"] . " %";
                                            }
                                            ?>
                                        </td>
                                        <td><?= $item["new_price"] ?> DA</td>
                                        <td><?= $item["cat_name"] ?></td>
                                        <td>
                                            <?php
                                            if ($item["status"] == 1) {
                                                echo "<span class='yes'> منشور </span>";
                                            } else {
                                                echo "<span class='no'> غير منشور</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            // Category Create Time
                                            $created_date = new DateTime($item["time"]);

                                            // Current Time
                                            $current_date = date("h-i-s");

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
                                                echo "منذ " . $days . " يوم";
                                            } elseif ($days == 0 && $hours !== 0) {
                                                echo "منذ " . $hours . " ساعة";
                                            } elseif ($days == 0 && $hours == 0 && $minuts !== 0) {
                                                echo "منذ " . $minuts . " دقيقة";
                                            } else {

                                                echo "منذ " . $second . " ثانية";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="?do=Edit&itemID=<?= $item["item_ID"] ?>" class="text-warning ms-2 fs-5"><i class="fa-solid fa-edit"></i></a>
                                            <a href="?do=Delete&itemID=<?= $item["item_ID"] ?>" onclick="return confirm('هل انت متاكد من حذف المنتج')" class="text-danger fs-5"><i class="fa-solid fa-trash"></i></a>
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
            } elseif ($do == "Add") { // ################################## Add Page

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $name     = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
                    $price    = $_POST["price"];
                    $discount = $_POST["discount"];
                    $n_price  = $_POST["n_price"];
                    $imgs    = $_FILES["files"];
                    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
                    $status = $_POST["status"];
                    $category = isset($_POST["cat"]) ? $_POST["cat"] : "";
                    $store = $_POST["store"];


                    $img_name = $imgs["name"];
                    $img_tmp = $imgs["tmp_name"];
                    $img_error = $imgs["error"];

                    $counts = count($img_name);

                    if (
                        !empty($name) &&
                        !empty($price) &&
                        $img_error[0] == 0 &&
                        !empty($description) &&
                        $category !== "" &&
                        !empty($store)
                    ) {


                        $stm = $conn->prepare("INSERT INTO 
            items (item_Name , item_Description , price , descount , new_price , status , store , cat_ID , time) 
                        VALUES 
            (? , ? , ? , ? , ? , ? , ? , ? ,  CURRENT_TIME())");

                        $stm->execute(array($name, $description, $price, $discount, $n_price, $status, $store, $category));

                        for ($i = 0; $i < $counts; $i++) {

                            $arr_extensions = array("jpg", "jpeg", "png", "gif");

                            $exp_arr = explode(".", $img_name[$i]);

                            $exp = strtolower(end($exp_arr));

                            if (!in_array($exp, $arr_extensions)) {
                                echo "You Must Select Just Image";
                            } else {

                                $time = time();

                                $n_name = $time . $img_name[$i];

                                move_uploaded_file($img_tmp[$i], "../item_images/" . $n_name);

                                $items = GetLastRow("items", "item_ID");

                                $item_ID = $items["item_ID"];

                                $stm2 = $conn->prepare("INSERT INTO 
                items_images (img , item_id) 
                            VALUES 
                (? , ? )");

                                $stm2->execute(array($n_name, $item_ID));
                            }
                        }

                        $_SESSION["mssg"] = "تمت اضافة المنتج";

                        header("Location: items.php");
                        exit();
                    } else {
                        $_SESSION["mssg"] = "كل الحقول اجبارية";

                        header("Location: items.php");
                        exit();
                    }
                }

            ?>
                <!-- Start Add Form -->
                <a href="items.php" class="btn btn-primary btn-sm btnBack"> عودة </a>
                <form class="page users-container" method="post" action="<?= $_SERVER["PHP_SELF"] . '?do=Add' ?>" enctype="multipart/form-data">

                    <div class="border shadow-sm general p-2 rounded-1" dir="rtl">

                        <div class="dash-title mb-2 d-flex justify-content-between align-items-end">
                            <div class="titl">عام</div>
                        </div>

                        <!-- Product name -->
                        <div class="group">
                            <label>اسم المنتج</label>
                            <input type="text" placeholder="اسم المنتج" name="name" class="border">
                        </div>

                        <!-- Product price -->
                        <div class="group">
                            <label for=""> الاسعار </label>
                            <div class="prices">
                                <input type="number" placeholder="سعر المنتج" name="price" class="border price">
                                <input type="number" placeholder="% نسبة الخصم" name="discount" value="0" class="border discount" min="0">
                                <input type="text" placeholder="السعر الجديد" name="n_price" class="border new_price" readonly>
                            </div>
                        </div>

                        <!-- Image Product -->
                        <div class="group">
                            <label> صور المنتج </label>
                            <input type="file" name="files[]" class="border" multiple="multiple" accept="image/*">
                        </div>

                        <!-- Description Product -->
                        <div class="group">
                            <label> الوصف </label>
                            <textarea name="description" cols="30" rows="6" class="border" placeholder="قدم وصفا دقيقا عن المنتج"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mx-0"> اضافة </button>

                    </div>

                    <div class="more">
                        <div class="more-details">
                            <!-- status -->
                            <div class="status border shadow-sm p-2" dir="rtl">
                                <div class="status-header">
                                    الحالة
                                    <div class="point"></div>
                                </div>
                                <select name="status" class="border">
                                    <option value="1" selected> منشور </option>
                                    <option value="0"> غير منشور </option>
                                </select>
                                <span> قم بتعيين حالة المنتج </span>
                            </div>
                            <!-- category -->
                            <div class="category border shadow-sm p-2" dir="rtl">
                                <div class="cat-title">
                                    معلومات المنتج
                                </div>
                                <span>اختر فئة</span>
                                <ul class="cats">
                                    <?php
                                    $stm = $conn->prepare("SELECT * FROM category WHERE allow_ads = 1");
                                    $stm->execute();
                                    $cats = $stm->fetchAll();

                                    $count_cats = count($cats);

                                    if ($count_cats == 0) {
                                        echo "<div class='alert alert-danger text-danger p-1 text-center'> لا يوجد فئات يرجى اضهار او اضافة على الاقل واحدة منها لتتمكن من النشر </div>";
                                    }

                                    foreach ($cats as $cat) {
                                    ?>
                                        <label for="<?= $cat["cat_ID"] ?>" class="border">
                                            <input type="radio" name="cat" id="<?= $cat["cat_ID"] ?>" value="<?= $cat["cat_ID"] ?>">
                                            <div class="cat-name"><?= $cat["cat_name"] ?></div>
                                        </label>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <!-- Store -->
                            <div class="store border shadow-sm p-2" dir="rtl">
                                <div class="store-title">
                                    ادارة المخزون
                                </div>
                                <span>المخزون</span>
                                <input class="border shadow-sm" type="text" placeholder="الكمية المتواجدة في المخزون" name="store">
                            </div>
                        </div>
                    </div>

                </form>
                <!-- End Add Form -->

                <?php
            } elseif ($do == "Edit") { // ################################## Edit Page
                if (isset($_GET["itemID"]) && isset($_SERVER["HTTP_REFERER"])) {

                    $itemID = $_GET["itemID"];

                    $stm = $conn->prepare("SELECT * FROM items WHERE item_ID = ?");

                    $stm->execute(array($itemID));

                    $item = $stm->fetch();

                ?>
                    <a href="items.php" class="btn btn-primary btn-sm btnBack"> عودة </a>
                    <!-- Start Edit Form -->
                    <form class="page users-container" method="post" action="?do=Update" enctype="multipart/form-data">

                        <div class="border shadow-sm general p-2 rounded-1" dir="rtl">

                            <div class="dash-title mb-2 d-flex justify-content-between align-items-end">
                                <div class="titl">عام</div>
                            </div>

                            <input type="hidden" name="itmID" value="<?= $itemID ?>">

                            <!-- Product name -->
                            <div class="group">
                                <label>اسم المنتج</label>
                                <input type="text" placeholder="اسم المنتج" name="name" class="border" value="<?= $item["item_Name"] ?>">
                            </div>

                            <!-- Product price -->
                            <div class="group">
                                <label for=""> الاسعار </label>
                                <div class="prices">
                                    <input type="number" placeholder="سعر المنتج" name="price" class="border price" value="<?= $item["price"] ?>">
                                    <input type="number" placeholder="% نسبة الخصم" name="discount" class="border discount" value="<?= $item["descount"] ?>">
                                    <input type="text" placeholder="السعر الجديد" name="n_price" class="border new_price" value="<?= $item["new_price"] ?>">
                                </div>
                            </div>

                            <!-- Image Product -->
                            <div class="group">
                                <label> اختر صور للمنتج </label>
                                <input type="file" name="files[]" class="border" multiple="multiple" accept="image/*">

                                <span style="font-size: 16px;">صور المنتج</span>
                                <div class="boxs-images border shadow-sm p-1">
                                    <?php
                                    $stmm = $conn->prepare("SELECT * FROM items_images WHERE item_id = ? ");
                                    $stmm->execute(array($item["item_ID"]));
                                    $imgs = $stmm->fetchAll();
                                    $count_imgs = count($imgs);
                                    if ($count_imgs > 0) {
                                        foreach ($imgs as $img) {
                                    ?>
                                            <div class="box-img">
                                                <a href="?do=Delete&imgID=<?= $img["img_id"] ?>" onclick="return confirm('هل انت متاكد من حذف الصورة')"><i class="fa-solid fa-xmark btnX"></i></a>
                                                <img src="../item_images/<?= $img["img"] ?>" class="border" width="100" height="100" style="object-fit: cover;">
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger text-danger p-2 mt-3 w-100 text-center'>ليس لديك صور للمنتج يرجى اخيار صور</div>";
                                    }
                                    ?>
                                </div>

                            </div>

                            <!-- Description Product -->
                            <div class="group">
                                <label> الوصف </label>
                                <textarea name="description" cols="30" rows="6" class="border" placeholder="قدم وصفا دقيقا عن المنتج"> <?= $item["item_Description"] ?> </textarea>
                            </div>

                            <!-- Button Submit -->
                            <button type="submit" class="btn btn-primary btn-sm mx-0"> تعديل </button>

                        </div>

                        <div class="more">
                            <div class="more-details">

                                <!-- status -->
                                <div class="status border shadow-sm p-2" dir="rtl">
                                    <div class="status-header">
                                        الحالة
                                        <div class="point <?= $item["status"] == 1 ? "pointVert" : "pointRed" ?>"></div>
                                    </div>
                                    <select name="status" class="border">
                                        <option value="1" <?= $item["status"] == 1 ? "selected" : "" ?>> منشور </option>
                                        <option value="0" <?= $item["status"] == 0 ? "selected" : "" ?>> غير منشور </option>
                                    </select>
                                    <span> قم بتعيين حالة المنتج </span>
                                </div>

                                <!-- category -->
                                <div class="category border shadow-sm p-2" dir="rtl">
                                    <div class="cat-title">
                                        معلومات المنتج
                                    </div>
                                    <span>اختر فئة</span>
                                    <ul class="cats">
                                        <?php
                                        $stm = $conn->prepare("SELECT * FROM category");
                                        $stm->execute();
                                        $cats = $stm->fetchAll();

                                        foreach ($cats as $cat) {
                                        ?>
                                            <label for="<?= $cat["cat_ID"] ?>" class="border">
                                                <input type="radio" name="cat" id="<?= $cat["cat_ID"] ?>" value="<?= $cat["cat_ID"] ?>" <?= $item["cat_ID"] == $cat["cat_ID"] ? "checked" : "" ?>>
                                                <div class="cat-name"><?= $cat["cat_name"] ?></div>
                                            </label>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>

                                <!-- Store -->
                                <div class="store border shadow-sm p-2" dir="rtl">
                                    <div class="store-title">
                                        ادارة المخزون
                                    </div>
                                    <span>المخزون</span>
                                    <input class="border shadow-sm" type="text" placeholder="الكمية المتواجدة في المخزون" name="store" value="<?= $item["store"] ?>">
                                </div>
                            </div>
                        </div>

                    </form>
                    <!-- End Edit Form -->
                <?php
                } else {
                ?>
                    <!-- if Not Admin -->
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="items.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
                        </div>

                    </div>
                <?php
                }
            } elseif ($do == "Update") { // ################################## update Page

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $itmID = $_POST["itmID"];
                    $name     = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
                    $price    = $_POST["price"];
                    $discount = $_POST["discount"];
                    $n_price  = $_POST["n_price"];
                    $imgs    = $_FILES["files"];
                    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
                    $status = $_POST["status"];
                    $category = isset($_POST["cat"]) ? $_POST["cat"] : "";
                    $store = $_POST["store"];


                    $img_name = $imgs["name"];
                    $img_tmp = $imgs["tmp_name"];
                    $img_error = $imgs["error"];

                    $counts = count($img_name);

                    if (
                        !empty($name) &&
                        !empty($price) &&
                        !empty($description) &&
                        $category !== "" &&
                        $store >= 0
                    ) {


                        $stm = $conn->prepare("UPDATE 
              items 
            SET
              item_Name = ? ,
              item_Description = ? ,
              price = ? ,
              descount = ? ,
              new_price = ? ,
              status = ? ,
              store  = ? ,
              cat_ID = ? 
            WHERE 
              item_ID = ? ");

                        $stm->execute(array($name, $description, $price, $discount, $n_price, $status, $store, $category, $itmID));

                        for ($i = 0; $i < $counts; $i++) {

                            $arr_extensions = array("jpg", "jpeg", "png", "gif");

                            $exp_arr = explode(".", $img_name[$i]);

                            $exp = strtolower(end($exp_arr));

                            if (!in_array($exp, $arr_extensions)) {
                                echo "You Must Select Just Image";
                            } else {

                                $time = time();

                                $n_name = $time . $img_name[$i];

                                move_uploaded_file($img_tmp[$i], "../item_images/" . $n_name);

                                $stm2 = $conn->prepare("INSERT INTO 
                items_images (img , item_id) 
                            VALUES 
                (? , ? )");

                                $stm2->execute(array($n_name, $itmID));
                            }
                        }

                        $_SESSION["mssg"] = "تم التعديل على المنتج";

                        header("Location: items.php");
                        exit();
                    } else {
                        echo showMssg("All Field Required", "danger");
                    }
                } else {
                ?>
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="items.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
                        </div>
                    </div>
                <?php
                }
            } elseif ($do == "Delete") { // ################################## Delete Page

                if (isset($_SERVER["HTTP_REFERER"])) {

                    // Delete Images From Edit Page.
                    if (isset($_GET["imgID"])) {
                        $referer = $_SERVER['HTTP_REFERER'];
                        $imgID = $_GET["imgID"];

                        $stm1 = $conn->prepare("DELETE FROM items_images WHERE img_id = ?");
                        $stm1->execute(array($imgID));

                        header("Location: $referer");
                        exit();
                    }

                    // Delete item From Manage Page.
                    if (isset($_GET["itemID"])) {
                        $referer = $_SERVER['HTTP_REFERER'];
                        $itemID = $_GET["itemID"];

                        $stm2 = $conn->prepare("DELETE FROM items WHERE item_ID = ?");
                        $stm2->execute(array($itemID));

                        header("Location: $referer");
                        exit();
                    }
                } else {
                ?>
                    <div class="container page_404">
                        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

                        <div class="not-found p-2 mt-4 text-center">
                            <a href="items.php" class="mt-2 btn btn-primary btn-sm"> الصفحة الرئيسية </a>
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
                    <li>
                        <a href="category.php">
                            <i class="fa-solid fa-list ms-2"></i>
                            الفئات
                        </a>
                    </li>
                    <li class="current_link">
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