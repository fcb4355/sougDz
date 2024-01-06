<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link Of Bootstrap -->
    <link rel="stylesheet" href="includes/css/web.css">

    <!-- Link Font icon -->
    <script src="https://kit.fontawesome.com/bc08b250b5.js" crossorigin="anonymous"></script>

    <!-- Link Of Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Link animation Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Link of Customize CSS -->
    <link rel="stylesheet" href="includes/css/frontend.css">

    <!-- icon of The Web Ecommerce  -->
    <link rel="shortcut icon" href="includes/images/icon.png" type="image/x-icon">

    <!-- Title -->
    <title><?php titlePage($pageTitle) ?></title>
</head>

<body>

    <!-- Start Nav Bar -->
    <div class="nav-bar-parent">
        <div class="container nav-bar">

            <!-- Left NavBar -->
            <div class="left-nav">

                <button class="bars">
                    <i class="fa-solid fa-bars fs-4"></i>
                </button>

                <button class="user">
                    <?php
                    if (isset($_SESSION["id"])) {
                    ?>
                        <img src="includes/images/avatar.png" alt="avatar" width="30" style="border-radius: 5px;">
                    <?php
                    } else {
                    ?>
                        <i class="fa-solid fa-user"></i>
                    <?php
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['id'])) {

                        $id = $_SESSION["id"];

                        $stm = $conn->prepare("SELECT * FROM users WHERE user_ID = ?");

                        $stm->execute(array($id));

                        $user = $stm->fetch();

                    ?>
                        <div class="me_nu menu-after-login">
                            <div class="thumbnail">
                                <div class="avatar">
                                    <img src="includes/images/avatar.png" alt="avatar">
                                </div>
                                <div class="more_user">
                                    <div class="name">
                                        <?php echo $user["full_Name"] ?>
                                    </div>
                                    <div class="email">
                                        <?php echo $user["email"] ?>
                                    </div>
                                </div>
                            </div>
                            <div class="info_user">
                                <li><a href="my-orders.php">طلباتي</a></li>
                                <li><a href="my-account.php">حسابي</a></li>
                                <li><a href="logout.php">تسجيل الخروج</a></li>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="me_nu menu-before-login">
                            <li> <a href="login.php">تسجيل الدخول</a> </li>
                            <hr class="m-0">
                            <li> <a href="sign.php">حساب جديد</a> </li>
                        </div>
                    <?php
                    }
                    ?>
                </button>

                <button class="card">
                    <a href="cart.php">
                        <i class="fa-solid fa-cart-shopping fs-4"> </i>
                        <?php
                        if (isset($_SESSION["id"])) {

                            $id = $_SESSION["id"];

                            // Get All Product in Baskets By user Id.
                            $stm = $conn->prepare("SELECT * FROM basket WHERE user_ID = ? AND isOrdering = 0");

                            $stm->execute(array($id));

                            $basketCount = count($stm->fetchAll());

                            echo "<span>" . $basketCount . "</span>";
                        }
                        ?>
                    </a>
                </button>

                <button class="homeBtn">
                    <a href="index.php">
                        <i class="fa-solid fa-home fs-4"></i>
                    </a>
                </button>
            </div>

            <!-- Right NavBar -->
            <ul class="right-nav">
                <?php
                if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1) {
                ?>
                    <li>
                        <a href="/market/admin/dashboard.php" class="<?php echo $_SERVER["PHP_SELF"] == "/market/admin/dashboard.php" ? "active" : "" ?>">
                            لوحة التحكم
                        </a>
                    </li>
                <?php
                }
                ?>

                <li>
                    <a href="tracking.php" class="<?php echo $_SERVER["PHP_SELF"] == "/market/user/tracking.php" ? "active" : "" ?>">تتبع طلبي
                    </a>
                </li>

                <li>
                    <a href="shop.php" class="<?php echo $_SERVER["PHP_SELF"] == "/market/user/shop.php" ? "active" : "" ?>">المتجر</a>
                </li>

                <li class="<?php echo $_SERVER["PHP_SELF"] == "/market/user/categorie.php" ? "active " : "" ?> list_cats">
                    <span>الفئات</span>
                    <ul class="menu-cat shadow-sm">
                        <?php
                        // Get All Category From DataBase
                        $stm = $conn->prepare("SELECT * FROM category WHERE visiblity	= 1");
                        $stm->execute();

                        $cats = $stm->fetchAll();

                        if (count($cats) == 0) {
                        ?>
                            <div class="text-center alert alert-danger text-danger"> لا يوجد فئات </div>
                            <?php
                        } else {
                            foreach ($cats as $cat) {
                            ?>
                                <li>
                                    <a href="category.php?catID=<?php echo $cat["cat_ID"] ?>"> <?php echo $cat["cat_name"] ?> </a>
                                </li>
                        <?php
                            }
                        }

                        ?>
                    </ul>
                </li>

                <li>
                    <a href="index.php" class="<?php echo $_SERVER["PHP_SELF"] == "/market/user/index.php" ? "active" : "" ?>">الصفحةالرئيسية</a>
                </li>

                <div class="logo">
                    <img src="includes/images/logo.png" alt="logo" height="50">
                </div>
            </ul>
        </div>
    </div>
    <!-- End Nav Bar -->




    <!-- Start Nav Bar 2 -->
    <div class="support p-2 text-center">
        🥰 للحصول على متجر الكتروني فقط قومو بالاتصال بنا عبر الهاتف : 0796410504
    </div>
    <!-- End Nav Bar 2 -->