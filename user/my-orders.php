<?php
$pageTitle = "طلباتي";
include "init.php";
?>

<?php
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}
?>

<div class="container mt-4 mb-4 profile">

    <div class="profile-edit" dir="rtl">
        <div class="my-orders border shadow-sm">
            <div class="title-orders">
                الطلبات
            </div>
            <div class="content">
                <?php
                // Get All orders By userId.
                $userID = $_SESSION["id"];
                $stm = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ");
                $stm->execute(array($userID));
                $orders = $stm->fetchAll();
                ?>
                <?php
                if (count($orders) == 0) {
                ?>
                    <img src="includes/images/order.jpg" width="100%" height="450">
                    <div class="alert alert-danger text-danger p-2 text-center"> ليس لديك اي طلبات </div>
                <?php
                } else {
                ?>
                    <table class="border">
                        <thead>
                            <tr>
                                <td>رقم التتبع</td>
                                <td>عدد المنتجات</td>
                                <td>الاجمالي</td>
                                <td>حالة الطلب</td>
                                <td>تاريح الطلب</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($orders as $order) {
                                // Get Number of products With this Order By orderID.
                                $stm2 = $conn->prepare("SELECT * FROM basket WHERE isOrdering = ? ");
                                $stm2->execute(array($order["order_id"]));
                                $products = $stm2->fetchAll();
                            ?>
                                <tr>
                                    <td>
                                        <a href="tracking.php" class="T_code" style="color: var(--main-color) !important;"><?= $order["tracking_code"] ?></a>
                                    </td>
                                    <td><?= count($products) ?></td>
                                    <td>DA <?= $order["total_price"] ?></td>
                                    <td><?= $order["status"] ?></td>
                                    <td>
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
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div class="profile-menu p-2 border shadow-sm" dir="rtl">
        <span class="mb-2 d-block text-center" style="color:var(--smoke-color); font-size:15px">القائمة</span>
        <hr class="mt-1 mb-1">
        <ul class="list">
            <li>
                <a href="home-page.php"> الصفحة الرئيسية </a>
            </li>
            <li>
                <a href="my-orders.php" class="active_link"> طلباتي </a>
            </li>
            <li>
                <a href="my-account.php">حسابي </a>
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