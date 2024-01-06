<?php
include "init.php";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["orderID"])) {

    $referer = $_SERVER["HTTP_REFERER"];
    $orderID = $_GET["orderID"];
    $mssgUpdate = "";
    if (isset($_GET["updateStatus"])) {   // Update Status Of Order
        $status = $_POST["status"];
        $mssgUpdate = "تم تحديث حالة طلبك من طرف الادمن";

        $stm = $conn->prepare("UPDATE orders SET status = ? , update_time = CURRENT_TIME() , mssg_update = ? WHERE order_id = ?");
        $stm->execute(array($status, $mssgUpdate, $orderID));

        $_SESSION["mssg"] = "تم تعديل الحالة";

        header("Location: $referer");
        exit();
    } elseif (isset($_GET["updateUser"])) {  // Update User Information
        $name  = htmlspecialchars($_POST["userName"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $mssgUpdate = "تم تحديث معلوماتك الشخصية من طرف الادمن";

        $stm = $conn->prepare("UPDATE orders SET name = ? , phone = ? , update_time = CURRENT_TIME() , mssg_update = ? WHERE order_id = ?");
        $stm->execute(array($name, $phone, $mssgUpdate, $orderID));

        $_SESSION["mssg"] = "تم تعديل معلومات العميل";

        header("Location: $referer");
        exit();
    } elseif (isset($_GET["updateShipping"])) {  // Update Shipping Address
        $wilaya  = htmlspecialchars($_POST["wilaya"]);
        $city = htmlspecialchars($_POST["city"]);
        $address = htmlspecialchars($_POST["address"]);
        $mssgUpdate = "تم تعديل عنوان شحنك من طرف الادمن";

        $stm = $conn->prepare("UPDATE orders SET wilaya = ? , city = ?  , address = ? , update_time = CURRENT_TIME() , mssg_update = ? WHERE order_id = ?");
        $stm->execute(array($wilaya, $city, $address, $mssgUpdate, $orderID));

        $_SESSION["mssg"] = "تم تعديل عنوان الشحن";

        header("Location: $referer");
        exit();
    }
} else {
    echo "Page Not Found";
}
?>


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