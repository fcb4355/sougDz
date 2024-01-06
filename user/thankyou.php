<?php
$pageTitle = "شكرا لك";
include "init.php";
?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $url = $_SERVER["HTTP_REFERER"];

    $arr = parse_url($url);

    $path = $arr["path"];

    $exp = explode("/", $path);

    $referer_url = end($exp);

    $id = $_SESSION["id"];

    // :: From The Checkout Page
    if ($referer_url == "checkout.php") {

        $price = $_POST["t-price"];
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $wilaya = $_POST["wilaya"];
        $city = $_POST["city"];
        $addresse = $_POST["addresse"];

        if (!empty($name) && filter_var($phone, FILTER_SANITIZE_NUMBER_INT) && !empty($phone) && !empty($wilaya) && !empty($city) && !empty($addresse)) {


            // Create Code tracking Of order.
            $first = "YM";
            $scnd = random_int(1000, 4000);

            function generate()
            {
                $character = "AZERTYUIOPQSDFGHJKLMWXCVBN";

                $char = "";

                for ($i = 0; $i < 3; $i++) {
                    $char = $character[rand(0, strlen($character) - 1)] . $character[rand(0, strlen($character) - 1)] . $character[rand(0, strlen($character) - 1)];
                }

                return $char;
            }

            $traking = $first . "-" . $scnd . generate();

            // Insert order in Orders Table
            $stm = $conn->prepare("INSERT INTO orders (name , phone , wilaya , city , address , status , total_price , time , update_time , user_id , tracking_code) VALUES (? , ? , ? , ? , ? , 'طلب جديد' , ? , CURRENT_TIME() , CURRENT_TIME() , ? , ? ) ");

            $ok = $stm->execute(array($name, $phone, $wilaya, $city, $addresse, $price, $id, $traking));


            // Get The Last Id Of Order Of This user
            $stm2  = $conn->prepare("SELECT order_id FROM orders WHERE user_ID = ? ORDER BY order_id DESC LIMIT 1");
            $stm2->execute(array($id));

            $exc = $stm2->fetchAll();

            $ordr = $exc[0]["order_id"];

            // Update The table of basket To Delete From ShopCart.
            $stm3 = $conn->prepare("UPDATE basket SET isOrdering = ? WHERE user_id = ? AND isOrdering = 0");
            $stm3->execute(array($ordr, $id));

            if ($ok) {
?>
                <div class="container tanckyou" style="margin-top: -75px;">
                    <img src="includes/images/thankyou.png" style="margin: 0 auto;display:block">
                    <div class="descriptio text-center border mt-2 mb-1 text-dark p-1 alert alert-info">
                        شُكرا جزيلا على ثِقتكم
                        <br>
                        يرجى إبقاء هاتفك مفتوح والرد على الإتصالات حتى نتمكن من تأكيد طلبك (لن يتم إرسال الطلب بدون تأكيده هاتفياً)،
                        كما يمكنك تأكيد طلبك الآن بالإتصال بنا على أحد الأرقام التالية 0796410504 - 0559723694
                        <br>
                    </div>
                    <a href="index.php" class="btn btn-primary btn-sm text-light mt-2"> الصفحة الرئيسية </a>
                </div>
            <?php
            }
        } else {
            $_SESSION["mssg"] = "خطا  يرجى التاكد من المعلومات";

            header("Refresh:0 ; url= checkout.php");
            exit();
        }
    } elseif ($referer_url == "product.php") { // :: From The Product Page

        $ref = $_SERVER["HTTP_REFERER"];

        // Declire The Variables.
        $price = $_POST["price"];
        $count = $_POST["count"];
        $itemID = $_POST["itemID"];
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $wilaya = $_POST["wilaya"];
        $city = $_POST["city"];
        $addresse = $_POST["address"];

        if (!empty($name) && filter_var($phone, FILTER_SANITIZE_NUMBER_INT) && !empty($phone) && !empty($wilaya) && !empty($city) && !empty($addresse)) {

            // Create Code tracking Of order.
            $first = "YM";
            $scnd = random_int(1000, 4000);

            function generate()
            {
                $character = "AZERTYUIOPQSDFGHJKLMWXCVBN";

                $char = "";

                for ($i = 0; $i < 3; $i++) {
                    $char = $character[rand(0, strlen($character) - 1)] . $character[rand(0, strlen($character) - 1)] . $character[rand(0, strlen($character) - 1)];
                }

                return $char;
            }

            $traking = $first . "-" . $scnd . generate();

            // Calc Total Price.
            $total_price = $price * $count;

            // Insert order in Orders Table
            $stm = $conn->prepare("INSERT INTO orders (name , phone , wilaya , city , address , status , total_price , time , update_time , user_id , tracking_code) VALUES (? , ? , ? , ? , ? , 'طلب جديد' , ? , CURRENT_TIME() , CURRENT_TIME() , ? , ? ) ");

            $ok = $stm->execute(array($name, $phone, $wilaya, $city, $addresse, $total_price, $id, $traking));

            // Get The Last Id Of Order Of This user
            $stm2  = $conn->prepare("SELECT order_id FROM orders WHERE user_ID = ? ORDER BY order_id DESC LIMIT 1");
            $stm2->execute(array($id));

            $exc = $stm2->fetchAll();

            $ordr = $exc[0]["order_id"];

            // insert Item order in basket 
            $stm3 = $conn->prepare("INSERT INTO basket (Quantity , isOrdering , item_id  , user_id) VALUES (? , ? , ? , ?)");

            $stm3->execute(array($count, $ordr, $itemID, $id));
            if ($ok) {
            ?>
                <div class="container tanckyou" style="margin-top: -75px;">
                    <img src="includes/images/thankyou.png" style="margin: 0 auto;display:block">
                    <div class="descriptio text-center border mt-2 mb-1 text-dark p-1 alert alert-info">
                        شُكرا جزيلا على ثِقتكم
                        <br>
                        يرجى إبقاء هاتفك مفتوح والرد على الإتصالات حتى نتمكن من تأكيد طلبك (لن يتم إرسال الطلب بدون تأكيده هاتفياً)،
                        كما يمكنك تأكيد طلبك الآن بالإتصال بنا على أحد الأرقام التالية 0796410504 - 0559723694
                        <br>
                    </div>
                    <a href="index.php" class="btn btn-primary btn-sm text-light mt-2"> الصفحة الرئيسية </a>
                </div>
    <?php
            }
        } else {
            $_SESSION["mssg"] = "خطا  يرجى التاكد من المعلومات";

            header("Refresh:0 ; url= $ref");
            exit();
        }
    }
} else {

    ?>
    <div class="container page_404 mt-3">

        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">

        <div class="error alert alert-danger text-danger border text-center p-1 rounded-1 mt-3"> عذرا الصفحة غير متوفرة </div>

        <div class="not-found p-2 mt-3 text-center">
            <a href="cart.php" class="mt-0 btn btn-primary btn-sm text-light"> الصفحة الرئيسية </a>
        </div>

    </div>
<?php

}

?>


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