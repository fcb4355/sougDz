<?php
$pageTitle = "الطلبات";
include "init.php";

if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 0) { // ###################### if isset session And user is not Admin
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
        <div class="page m-0">
            <?php
            $do = isset($_GET["do"]) ? $_GET["do"]  : "Manage";
            // Get All Orders
            $stm = $conn->prepare("SELECT * FROM orders");
            $stm->execute();
            $orders = $stm->fetchAll();
            $countOrders = count($orders);

            if ($do == "Manage") {
            ?>
                <?php
                if ($countOrders == 0) {
                ?>
                    <img src="includes/images/empty-search.png" width="600" height="600" class="d-block mx-auto">
                    <div class="p-2 text-center">لا يوجد طلبات </div>
                <?php
                } else {
                ?>
                    <div class="ord_page">
                        <table class="table table-responsive orders" dir="rtl">
                            <thead>
                                <td>المعرف</td>
                                <td>الاسم</td>
                                <td>الهاتف</td>
                                <td>الحالة</td>
                                <td>الاجمالي</td>
                                <td>تم الانشاء</td>
                                <td>تم التحديث</td>
                                <td>خيارات</td>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($orders as $order) {
                                ?>
                                    <tr>
                                        <td> <span> <?= $order["order_id"] ?> </span></td>
                                        <td><span><?= $order["name"] ?></span></td>
                                        <td><span>0<?= $order["phone"] ?></span></td>
                                        <td> <span class="<?php
                                                            if ($order["status"] == "طلب جديد") {
                                                                echo "status_new";
                                                            } elseif ($order["status"] == "تم التاكيد" || $order["status"] == "جاهز") {
                                                                echo "status_ready";
                                                            } elseif ($order["status"] == "مكتمل" || $order["status"] == "تم الارسال") {
                                                                echo "status_finish";
                                                            } elseif ($order["status"] == "طلب مرفوض") {
                                                                echo "status_refuse";
                                                            }
                                                            ?>"> <?= $order["status"] ?> </span> </td>
                                        <td><span><?= $order["total_price"] ?> </span> $</td>
                                        <td>
                                            <span>
                                                <?php
                                                // Category Create Time
                                                $created_date = new DateTime($order["time"]);

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
                                            <span>
                                                <?php
                                                // Category Create Time
                                                $created_date = new DateTime($order["update_time"]);

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
                                            <span>
                                                <a href="?do=Edit&orderID=<?= $order["order_id"] ?>" class="text-warning ms-2"> <i class="fa-solid fa-eye fs-5"></i> </a>
                                                <a href="?do=Delete&orderID=<?= $order["order_id"] ?>" class="text-danger" onclick="return confirm('هل انت متاكد من حذف الطلب')">
                                                    <i class="fa-solid fa-trash fs-5"></i>
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
            } elseif ($do == "Edit") {

                $orderID = $_GET["orderID"];

                // Get Order By OrderID
                $stm = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
                $stm->execute(array($orderID));
                $CurrentOrder = $stm->fetch();

                // echo "<pre>";
                // print_r($CurrentOrder);
                // echo "</pre>";

                ?>
                <div class="grid-container" dir="rtl">
                    <a href="orders.php" class="btn btn-primary d-flex align-items-center gap-2 justify-content-center" style="width: fit-content;margin-right: auto;"> عودة <i class="fa-solid fa-arrow-left"></i> </a>

                    <!-- Link To Open Facture -->
                    <div class="show-facture">
                        <a href="facture.php?orderID=<?= $orderID ?>" target="_blank" class="factureBtn btn btn-success btn-block"> عرض الفاتورة </a>
                    </div>

                    <!-- Form 1 -->
                    <form method="post" action="updateOrder.php?orderID=<?= $CurrentOrder["order_id"] ?>&updateStatus" class="info-order border item1 box">
                        <div class="item-title"> معلومات الطلب </div>

                        <!-- Order Number -->
                        <div class="number-order">
                            رقم الطلب :
                            <br>
                            <b><?= $CurrentOrder["order_id"] ?>#</b>
                        </div>

                        <!-- Status of Order -->
                        <div class="status">
                            <div class="mb-1 mt-2"> الحالة: </div>
                            <select name="status" class="w-100 p-2 border shadow-sm">
                                <option value="طلب جديد" <?= $CurrentOrder["status"] == "طلب جديد" ? "selected" : "" ?>>طلب جديد</option>
                                <option value="تم التاكيد" <?= $CurrentOrder["status"] == "تم التاكيد" ? "selected" : "" ?>>تم التاكيد</option>
                                <option value="جاهز" <?= $CurrentOrder["status"] == "جاهز" ? "selected" : "" ?>>جاهز</option>
                                <option value="تم الارسال" <?= $CurrentOrder["status"] == "تم الارسال" ? "selected" : "" ?>>تم الارسال</option>
                                <option value="مكتمل" <?= $CurrentOrder["status"] == "مكتمل" ? "selected" : "" ?>>مكتمل</option>
                                <option value="طلب مرفوض" <?= $CurrentOrder["status"] == "طلب مرفوض" ? "selected" : "" ?>>طلب مرفوض</option>
                            </select>
                        </div>

                        <!-- Date Of Order -->
                        <div class="date mt-3">
                            تم الانشاء
                            <input type="text" readonly disabled class="border w-100 outline-none p-2" value="<?php

                                                                                                                // To Separate The Date And Time From The DateTime.
                                                                                                                $s = strtotime($CurrentOrder["time"]);
                                                                                                                // Get The Date From DAteTime
                                                                                                                $date = date('m/d/Y', $s);

                                                                                                                echo $date;
                                                                                                                ?>">
                            <div class="mt-2" style="font-size: 13px;color: var(--smoke-color);">
                                تم الانشاء
                                <span>
                                    <?php
                                    // Category Create Time
                                    $created_date = new DateTime($CurrentOrder["time"]);

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
                                <br>
                                <?php
                                if ($CurrentOrder["time"] !== $CurrentOrder["update_time"]) {
                                ?>
                                    تم التحديث
                                    <span>
                                        <?php
                                        // Category Create Time
                                        $created_date = new DateTime($CurrentOrder["update_time"]);

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
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <button type="submit"> حفط </button>
                    </form>

                    <hr>

                    <!-- Code Tracking Order -->
                    <div class="box border">
                        <div class="tracking mt-2">
                            <div class="item-title"> تتبع طلبي </div>
                            <span> رابط التتبع </span>
                            <br>
                            <input type="text" readonly disabled value="<?= $CurrentOrder["tracking_code"] ?>" class="clipboard border">
                            <button class="copy">نسخ</button>

                            <script>
                                let btnCopy = document.querySelector(".copy");
                                let trackingCode = document.querySelector(".clipboard");

                                btnCopy.addEventListener("click", () => {
                                    code_track = trackingCode.value;
                                    navigator.clipboard.writeText(code_track);
                                    btnCopy.innerHTML = "تم النسخ";
                                    setTimeout(() => {
                                        btnCopy.innerHTML = "نسخ";
                                    }, 1000);
                                })
                            </script>
                        </div>
                    </div>

                    <hr>

                    <!-- Form 2 -->
                    <form method="post" action="updateOrder.php?orderID=<?= $CurrentOrder["order_id"] ?>&updateUser" class="info-user border item2 box">

                        <div class="item-title"> معلومات العميل</div>

                        <!-- Name Of user -->
                        <div class="group">
                            <div class="icon">
                                <i class="fa-solid fa-user"></i>
                                <div class="name"> العميل </div>
                            </div>
                            <input type="text" name="userName" class="border" value="<?= $CurrentOrder["name"] ?>">
                        </div>
                        <hr class="mt-2 mb-2">

                        <!-- Phone Of user -->
                        <div class="group">
                            <div class="icon">
                                <i class="fa-solid fa-phone"></i>
                                <div class="name"> الهاتف </div>
                            </div>
                            <input type="text" name="phone" class="border" value="0<?= $CurrentOrder["phone"] ?>">
                        </div>

                        <button type="submit" class="shadow-sm"> حفظ </button>
                    </form>
                    <hr>

                    <!-- Form 3 -->
                    <form method="post" action="updateOrder.php?orderID=<?= $CurrentOrder["order_id"] ?>&updateShipping" class="info-levraison border item3 box">
                        <div class="item-title"> عنوان الشحن </div>

                        <!-- Wilaya Ordering -->
                        <div class="group">
                            <div class="icon">
                                <i class="fa-solid fa-city"></i>
                                <div class="name"> الولاية </div>
                            </div>
                            <input type="text" name="wilaya" class="border" value="<?= $CurrentOrder["wilaya"] ?>">
                        </div>

                        <hr class="mt-2 mb-2">

                        <!-- Cité Ordering -->
                        <div class="group">
                            <div class="icon">
                                <i class="fa-solid fa-building"></i>
                                <div class="name"> المدينة </div>
                            </div>
                            <input type="text" name="city" class="border" value="<?= $CurrentOrder["city"] ?>">
                        </div>

                        <hr class=" mt-2 mb-2">

                        <!-- Address Ordering -->
                        <div class="group">
                            <div class="icon">
                                <i class="fa-solid fa-location-dot"></i>
                                <div class="name"> العنوان </div>
                            </div>
                            <input type="text" name="address" class="border" value="<?= $CurrentOrder["address"] ?>">
                        </div>

                        <button type=" submit" class="shadow-sm"> حفظ </button>
                    </form>
                    <hr>

                    <!-- Form 4 -->
                    <form method="post" action="" class="table-order border item4 box">

                        <div class="item-title"> معرف الطلب <?= $_GET["orderID"] ?># </div>


                        <?php
                        $orderID =  $_GET["orderID"];

                        $stm = $conn->prepare("SELECT basket.* , 
                                items.new_price as price_item,
                                items.item_Name as name_item
                            FROM 
                                basket
                            INNER JOIN 
                                items 
                            ON 
                                items.item_ID = basket.item_id
                            WHERE 
                                isOrdering = ?");
                        $stm->execute(array($orderID));

                        $orders = $stm->fetchAll();
                        ?>

                        <?php

                        if (count($orders) == 0) {
                            echo "<div class='text-center p-2 text-danger alert alert-danger'>تم حذف كل المشتريات من الطلب</div>";
                        } else {
                        ?>
                            <div class="items_order">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> المنتج </th>
                                            <th> الكمية </th>
                                            <th> السعر </th>
                                            <th> الاجمالي </th>
                                            <th> تعديل </th>
                                            <th> حذف </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $Total = 0;

                                        foreach ($orders as $order) {
                                            $itemID =  $order["item_id"];

                                            // Get Image Of The Product.
                                            $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ?");
                                            $stm2->execute(array($itemID));

                                            $img = $stm2->fetch();

                                            $imgSrc = $img["img"];
                                        ?>
                                            <tr>
                                                <!-- Image Of Product -->
                                                <td>
                                                    <img src="../item_images/<?= $imgSrc ?>" width="50" height="50" class="border p-1">
                                                    <?= $order["name_item"] ?>
                                                </td>

                                                <!-- Quantity Of Product -->
                                                <td>
                                                    <span class="Product-quantity">
                                                        <?= $order["Quantity"] ?>
                                                    </span>
                                                </td>

                                                <!-- Price Product -->
                                                <td>
                                                    <span>
                                                        DA <?= $order["price_item"] ?>
                                                    </span>
                                                </td>

                                                <!-- Total Price Of All Quantity -->
                                                <td>
                                                    <span>

                                                        DA
                                                        <?php
                                                        $Total = $Total + ($order["Quantity"] * $order["price_item"]);

                                                        echo $order["Quantity"] * $order["price_item"];
                                                        ?>
                                                    </span>
                                                </td>

                                                <!-- Button Edit Product -->
                                                <td>
                                                    <span class="text-warning ms-2 fs-5 EditProduct" style="cursor: pointer;">
                                                        <i class="fa-solid fa-edit"></i>
                                                        <input type="text" value="<?= $order["Quantity"] ?>" hidden>
                                                        <input type="text" value="<?= $order["item_id"] ?>" hidden>
                                                        <input type="text" value="<?= $order["isOrdering"] ?>" hidden>
                                                    </span>
                                                </td>

                                                <!-- Button Delete Product -->
                                                <td>
                                                    <a href="?do=Delete&ordID=<?= $_GET["orderID"] ?>&itemID=<?= $order["item_id"] ?>" class="text-danger fs-5" onclick="return confirm('هل انت متاكد من حذف المنتج من الطلبية')"> <i class="fa-solid fa-trash"></i> </a>
                                                </td>
                                            </tr>

                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        }
                        ?>
                    </form>

                    <!-- Box Form Edit Product  -->
                    <div class="boxEdit">
                        <form action="?do=Update" method="post" class="FormEditProduct border">
                            <div class="item-title">
                                تعديل المنتج
                            </div>

                            <hr>

                            <div class="qnt">
                                <span> كمية المنتج </span>
                                <input type="number" name="quantity" class="quantity" min="1">
                                <input type="hidden" name="itemID" class="itemID">
                                <input type="hidden" name="orderID" class="orderID">
                            </div>

                            <hr>

                            <div class="btns">
                                <span class="cancel"> الغاء </span>
                                <button> <i class="fa-solid fa-check"></i> تعديل </button>
                            </div>
                        </form>
                    </div>

                    <!-- Total Price Of All Products in Order -->
                    <div class="Price" style="margin-right: auto; padding:10px;background-color:var(--alert-info); color:var(--white-color);">
                        السعر الاجمالي
                        : <span style="font-weight: bold;">DA <?= count($orders) == 0 ? 0 : $Total ?></span>
                    </div>
                </div>
            <?php
            } elseif ($do == "Update") {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $quantity = htmlspecialchars($_POST["quantity"]);
                    $orderID = $_POST["orderID"];
                    $itemID = $_POST["itemID"];
                    $mssgUpdate = "";
                    // Edit Quantity Of Product in DataBase
                    $stm = $conn->prepare(" UPDATE basket 
                                        SET 
                                                Quantity = ? 
                                        WHERE 
                                                item_id = ? 
                                        AND 
                                                isOrdering = ?");

                    $stm->execute(array($quantity, $itemID, $orderID));

                    $mssgUpdate = "تم تعديل كمية المنتج من طرف الادمن";
                    $stm = $conn->prepare("UPDATE orders SET update_time = CURRENT_TIME() , mssg_update = ? WHERE order_id = ?");
                    $stm->execute(array($mssgUpdate, $orderID));

                    $_SESSION["mssg"] = "تم تعديل المنتج";

                    $referer = $_SERVER["HTTP_REFERER"];

                    header("Refresh:0; url=$referer");
                    exit();
                } else {
                    echo "Page Not Found";
                }
            } elseif ($do == "Delete") {
                if (isset($_SERVER["HTTP_REFERER"]) && isset($_GET["ordID"]) && isset($_GET["itemID"])) {

                    $orderID = $_GET["ordID"];
                    $itemID  = $_GET["itemID"];

                    $stm = $conn->prepare("DELETE FROM basket WHERE item_id = ? AND isOrdering = ?");

                    $stm->execute(array($itemID, $orderID));

                    $_SESSION["mssg"] = "تم حذف المنتج من الطلبية";

                    $referer = $_SERVER["HTTP_REFERER"];

                    header("Refresh:0; url=$referer");
                    exit();
                } elseif (isset($_SERVER["HTTP_REFERER"]) && isset($_GET["orderID"])) {

                    $orderID =  $_GET["orderID"];

                    // Delete The Order
                    $stm = $conn->prepare("DELETE FROM orders WHERE order_id = ?");

                    $stm->execute(array($orderID));

                    // Delete The Product Referer With Current Order
                    $stm = $conn->prepare("DELETE FROM basket WHERE isOrdering = ?");

                    $stm->execute(array($orderID));

                    $_SESSION["mssg"] = "تم حذف الطلب";

                    $referer = $_SERVER["HTTP_REFERER"];

                    header("Refresh:0; url=$referer");
                    exit();
                } else {
                    echo "Page Not Found";
                }
            }
            ?>
        </div>


        <!-- Side Bar Options -->
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
                    <li>
                        <a href="items.php">
                            <i class="fa-solid fa-bag-shopping ms-2"></i>
                            المنتجات
                        </a>
                    </li>
                    <li class="current_link">
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