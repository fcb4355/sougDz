<?php
$pageTitle = "المنتج";
include "init.php";
?>

<?php

if (isset($_GET["pr-id"])) {

    $productID = $_GET["pr-id"];

    // Get info And images With Get Requst id
    $stm = $conn->prepare("	SELECT items.* , 
														category.cat_name AS catName 
													FROM 
														items 
													INNER JOIN 
														category 
													ON 
														items.cat_ID = category.cat_ID 
													WHERE 
														item_ID = ? ");
    $stm->execute(array($productID));
    // $item = $stm->fetch();

    $item = $stm->fetchAll();


    // Get All Images From item_images With product id.

    $stm2 = $conn->prepare("SELECT * FROM items_images WHERE item_id = ?");
    $stm2->execute(array($productID));

    $imgs = $stm2->fetchAll();
    $count_imgs = count($imgs);
}
?>

<?php
if (count($item)) {
?>
    <div class="container product-container mt-4" style="margin-bottom: 70px;">

        <div class="content">
            <!-- Thumbnail Product info -->
            <div class="product-thumbnail" dir="rtl">

                <div class="pr-category"> <a class="text-light" href="category.php?catID=<?= $item[0]["cat_ID"] ?>"><?= $item[0]["catName"] ?></a></div>

                <div class="pr-name">
                    <div class="name"><?= $item[0]["item_Name"] ?></div>
                    <?php
                    if ($item[0]["descount"] !== 0) {
                        echo '<div class="discount">%' . $item[0]["descount"] . '</div>';
                    }
                    ?>
                </div>

                <div class="pr-description mt-0 mb-2">
                    <?= $item[0]["item_Description"] ?>
                </div>

                <div class="stars">
                    <?php
                    // Calc The Rating System Stars
                    $stm = $conn->prepare("SELECT * FROM comments WHERE item_id = ?");
                    $stm->execute(array($_GET["pr-id"]));

                    $cmmnts = $stm->fetchAll();

                    if (count($cmmnts) > 0) {
                        $num = 0;
                        foreach ($cmmnts as $cmmnt) {
                            $num = $cmmnt["rating"] + $num;
                        }
                        $numStars = round($num / count($cmmnts));

                        for ($i = 0; $i < $numStars; $i++) {
                    ?>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                        <?php
                        }

                        for ($i = 0; $i < 5 - $numStars; $i++) {
                        ?>
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                        <?php
                        }
                        echo "(" . round($num / count($cmmnts), 1) . ")";
                    } else {
                        ?>
                        <i class="fa-solid fa-star" style="color:#ccc;"></i>
                        <i class="fa-solid fa-star" style="color:#ccc;"></i>
                        <i class="fa-solid fa-star" style="color:#ccc;"></i>
                        <i class="fa-solid fa-star" style="color:#ccc;"></i>
                        <i class="fa-solid fa-star" style="color:#ccc;"></i>
                    <?php
                        echo "(0)";
                    }

                    ?>
                </div>

                <div class="prices">
                    <?php
                    if ($item[0]["descount"] !== 0) {
                    ?>
                        <div class="new"><?= $item[0]["new_price"] ?> DA </div>
                        <div class="old"><?= $item[0]["price"] ?> DA </div>
                    <?php
                    } else {
                    ?>
                        <div class="new"><?= $item[0]["price"] ?> DA </div>
                    <?php
                    }
                    ?>
                </div>

                <div class="store">
                    <?php
                    if ($item[0]["store"] !== 0) {
                    ?>
                        <i class="fa-solid fa-check"></i> متوفر <?= $item[0]["store"] ?> في المخزن
                    <?php
                    } else {
                    ?>
                        <div class="text-danger d-flex align-items-center gap-1">
                            <i class="fa-solid fa-xmark bg-danger"></i> غير متوفر في المخزن
                        </div>
                    <?php
                    }

                    ?>
                </div>
            </div>

            <hr class="m-2">

            <!-- Form Add Order -->
            <form method="post" action="thankyou.php" class="ordering border shadow-sm" dir="rtl">
                <div class="group">
                    <input type="text" placeholder="الاسم الكامل" class="border" name="name" required="required">
                    <input type="text" placeholder="رقم الهاتف" class="border" name="phone" required="required">
                </div>
                <div class="group">
                    <input type="text" placeholder="الولاية" class="border" name="wilaya" required="required">
                    <input type="text" placeholder="المدينة" class="border" name="city" required="required">
                </div>
                <input type="hidden" name="price" value="<?= $item[0]["new_price"] ?>">
                <input type="hidden" name="itemID" value="<?= $_GET["pr-id"] ?>">
                <input type="text" placeholder="العنوان" class="border" name="address" required="required">
                <div class="group-buy">
                    <div class="num-product">
                        <span class="plusNum">+</span>
                        <input type="number" name="count" min="1" value="1" class="border count-pr">
                        <span class="minusNum">-</span>
                    </div>
                    <button type="submit" class="buyBtn"> <i class="fa-solid fa-cart-shopping"></i> اشتري الان</button>
                </div>
            </form>

            <!-- Form Add To Cart  -->
            <form action="addToCart.php?pr-id=<?= $item[0]["item_ID"] ?>" method="POST" class="mt-2 form_cart">
                <input type="hidden" value="1" name="token" class="hide">
                <input type="submit" value="اضف الى السلة">
            </form>

            <hr class="m-2">

            <!-- Rating  -->
            <div class="rating">
                <h4> تقييمات العملاء </h4>
                <!-- Form Add Comment -->
                <?php
                if (!isset($_SESSION["id"])) {
                ?>
                    <div class="text-center p-2 border mb-2">
                        قم ب<a href="login.php" class="text-primary">تسجيل الدخول</a> لتتمكن من تقييم
                        المنتج
                    </div>
                <?php
                } else {
                ?>
                    <button class="d-block btn btn-primary btns-sm mx-auto mb-2 mt-2 btnReview"> اضافة تقييم </button>
                    <!-- Rating Box -->
                    <div class="rating-box">
                        <form action="comment.php" method="post" class="form-rating border shadow-sm" dir="rtl">
                            <h5 class="px-3"> اضافة مراجعة </h5>

                            <hr class="mt-4 mb-2">

                            <div class="stars px-3">
                                <div> تقييمك <span class="text-danger">*</span> </div>
                                <div class="stars d-flex align-items-center gap-2">
                                    <!-- G-1 -->
                                    <div class="group">
                                        <input type="radio" name="rate" id="star1" value="1" required>
                                        <label for="star1">
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                        </label>
                                    </div>
                                    <!-- G-2 -->
                                    <div class="group">
                                        <input type="radio" name="rate" id="star2" value="2" required>
                                        <label for="star2">
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                        </label>
                                    </div>
                                    <!-- G-3 -->
                                    <div class="group">
                                        <input type="radio" name="rate" id="star3" value="3" required>
                                        <label for="star3">
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                        </label>
                                    </div>
                                    <!-- G-4 -->
                                    <div class="group">
                                        <input type="radio" name="rate" id="star4" value="4" required>
                                        <label for="star4">
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                        </label>
                                    </div>
                                    <!-- G-5 -->
                                    <div class="group">
                                        <input type="radio" name="rate" id="star5" value="5" required>
                                        <label for="star5">
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                            <i class="fa-regular fa-star" style="color: #ffea00;"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="itemID" value="<?= $_GET["pr-id"] ?>">

                            <div class="comment mt-3 px-3">
                                <div> اكتب التعليق <span class="text-danger">*</span> </div>
                                <textarea name="comment" cols="30" rows="6" class="border mt-2" style="resize: vertical; width:100%; outline:none" required></textarea>
                            </div>

                            <hr class="mt-4 mb-2">

                            <div class="btns" dir="ltr">
                                <button type="submit" class="BtnComment"> اضافة </button>
                                <span class="BtnCancel border"> الغاء </span>
                            </div>
                        </form>
                    </div>
                <?php
                }
                ?>

                <!-- Details Of Rating -->
                <div class="details-rating">
                    <div class="info-rating">
                        <div class="group">
                            <?php
                            // Get All user Rating Product 5 stars
                            $stm = $conn->prepare("SELECT * FROM comments WHERE rating = 5 AND item_id = ?");
                            $stm->execute(array($_GET["pr-id"]));
                            $rating = $stm->fetchAll();
                            ?>
                            (5 <i class="fa-solid fa-star fa-sm" style="color:gold;"></i>)
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            ( <i class="fa-solid fa-user"></i> <?= count($rating) ?> )
                        </div>
                        <div class="group">
                            <?php
                            // Get All user Rating Product 4 stars
                            $stm = $conn->prepare("SELECT * FROM comments WHERE rating = 4 AND item_id = ?");
                            $stm->execute(array($_GET["pr-id"]));
                            $rating = $stm->fetchAll();
                            ?>
                            (4 <i class="fa-solid fa-star fa-sm" style="color:gold;"></i>)
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            ( <i class="fa-solid fa-user"></i> <?= count($rating) ?> )
                        </div>
                        <div class="group">
                            <?php
                            // Get All user Rating Product 3 stars
                            $stm = $conn->prepare("SELECT * FROM comments WHERE rating = 3 AND item_id = ?");
                            $stm->execute(array($_GET["pr-id"]));
                            $rating = $stm->fetchAll();
                            ?>
                            (3 <i class="fa-solid fa-star fa-sm" style="color:gold;"></i>)
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            ( <i class="fa-solid fa-user"></i> <?= count($rating) ?> )
                        </div>
                        <div class="group">
                            <?php
                            // Get All user Rating Product 2 stars
                            $stm = $conn->prepare("SELECT * FROM comments WHERE rating = 2 AND item_id = ?");
                            $stm->execute(array($_GET["pr-id"]));
                            $rating = $stm->fetchAll();
                            ?>
                            (2 <i class="fa-solid fa-star fa-sm" style="color:gold;"></i>)
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            ( <i class="fa-solid fa-user"></i> <?= count($rating) ?> )
                        </div>
                        <div class="group">
                            <?php
                            // Get All user Rating Product 1 stars
                            $stm = $conn->prepare("SELECT * FROM comments WHERE rating = 1 AND item_id = ?");
                            $stm->execute(array($_GET["pr-id"]));
                            $rating = $stm->fetchAll();
                            ?>
                            (1 <i class="fa-solid fa-star fa-sm" style="color:gold;"></i>)
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <i class="fa-solid fa-star" style="color:gold;"></i>
                            ( <i class="fa-solid fa-user"></i> <?= count($rating) ?> )
                        </div>
                    </div>
                    <div class="calc-rating">
                        <?php
                        // Calc The Rating System Stars
                        $stm = $conn->prepare("SELECT * FROM comments WHERE item_id = ?");
                        $stm->execute(array($_GET["pr-id"]));

                        $cmmnts = $stm->fetchAll();

                        // echo "<pre>";
                        // print_r($cmmnts);
                        // echo "</pre>";

                        if (count($cmmnts) > 0) {
                            $num = 0;
                            foreach ($cmmnts as $cmmnt) {
                                $num = $cmmnt["rating"] + $num;
                            }
                            $numStars = round($num / count($cmmnts), 1);
                        }
                        ?>
                        <div class="average"> <?= isset($numStars) ? $numStars : 0 ?> / 5 </div>
                        <div class="stars">
                            <?php
                            if (count($cmmnts) > 0) {
                                $num = 0;
                                foreach ($cmmnts as $cmmnt) {
                                    $num = $cmmnt["rating"] + $num;
                                }
                                $numStars = round($num / count($cmmnts));

                                for ($i = 0; $i < 5 - $numStars; $i++) {
                            ?>
                                    <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <?php
                                }

                                for ($i = 0; $i < $numStars; $i++) {
                                ?>
                                    <i class="fa-solid fa-star" style="color:gold;"></i>
                                <?php
                                }
                            } else {
                                ?>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                                <i class="fa-solid fa-star" style="color:#ccc;"></i>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="reviews">
                            (<?= count($cmmnts) ?>) Views
                        </div>
                    </div>
                </div>


                <!-- Comments Of All users  -->
                <div class="comments">
                    <!-- Get All Comment From DataBase -->
                    <?php
                    $stm = $conn->prepare("SELECT comments.* , 
																															users.is_Admin 
																													AS 
																															IsAdmin ,
																															users.full_Name
																													AS
																															fullName
																													FROM 
																															comments
																													INNER JOIN 
																															users 
																													ON 
																															users.user_ID = comments.user_id
																													WHERE
																															item_id = ? 
																													ORDER BY 
																															comment_id 
																													DESC;");

                    $stm->execute(array($_GET["pr-id"]));

                    $comments = $stm->fetchAll();

                    if (count($comments) == 0) {
                        echo "<div class='text-center p-2'> لا يوجد تعليقات </div>";
                    } else {
                        foreach ($comments as $comment) {
                    ?>
                            <li class="p-2" data-aos="fade-up">
                                <div class="comment-thumbnail">

                                    <!-- Thumbnail Of comment -->
                                    <div class="thumb">
                                        <img src="includes/images/avatar.png" width="40" height="40" class="border shadow-sm">
                                        <div class="name">
                                            <?= $comment["fullName"] ?>
                                            <?= $comment["IsAdmin"] == 1 ? "<div class='authorAdmin'> ادمن </div>" : "<div class='authorClient'> زبون </div>" ?>
                                        </div>

                                        <!-- Date Of Comment -->
                                        <div class="date">
                                            (<?php

                                                // Category Create Time
                                                $created_date = new DateTime($comment["time"]);

                                                // Calculate The Deffrent Time with created time and current time
                                                $diff = $created_date->diff(new DateTime());

                                                $second = $diff->s;
                                                $minuts = $diff->i;
                                                $hours = $diff->h;
                                                $days = $diff->d;
                                                $month = $diff->m;

                                                if ($month !== 0) {
                                                    echo "منذ " . $month . " اشهر";
                                                } elseif ($days !== 0 && $month == 0) {
                                                    echo "منذ " . $days . "يوم";
                                                } elseif ($days == 0 && $hours !== 0) {
                                                    echo "منذ " . $hours . " ساعة";
                                                } elseif ($days == 0 && $hours == 0 && $minuts !== 0) {
                                                    echo "منذ " . $minuts . " دقيقة";
                                                } else {
                                                    echo "منذ " . $second . " ثانية";
                                                }
                                                ?>)
                                        </div>
                                    </div>
                                </div>

                                <!-- Stars -->
                                <div class="stars">
                                    <?php
                                    for ($j = 0; $j < 5 - $comment["rating"]; $j++) {
                                    ?>
                                        <i class="fa-solid fa-star" style="color: #ddd;"></i>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    for ($i = 0; $i < $comment["rating"]; $i++) {
                                    ?>
                                        <i class="fa-solid fa-star" style="color: gold;"></i>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <!-- Comment -->
                                <div class=" cmmnt">
                                    <?= $comment["comment"] ?>
                                </div>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Images Of Product -->
        <div class="images" data-aos="fade-up">
            <div id="carouselExample" class="carousel slide">
                <div class="carousel-inner border">
                    <?php
                    $i = 0;
                    foreach ($imgs as $img) {
                    ?>
                        <div class="carousel-item <?php echo $i == 0 ? "active" : "" ?>">
                            <img src="../item_images/<?= $img["img"] ?>" class="d-block w-100">
                        </div>
                    <?php
                        $i++;
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

    </div>
<?php
} else {
?>
    <div class="container page_404 mt-3">
        <div class="error text-center p-1 rounded-1 mb-3"> عذرا المنتج غير متوفر </div>
        <img src="../user/includes/images/404.png" width="90%" class="d-block mx-auto">


        <div class="not-found p-2 mt-4 text-center">
            <a href="index.php" class="mt-0 btn btn-primary btn-sm text-light"> الصفحة الرئيسية </a>
        </div>

    </div>
<?php
}
?>



<?php include "templates/footer.php"; ?>

<script>
    let plusBtn = document.querySelector(".plusNum");
    let minusBtn = document.querySelector(".minusNum");
    let input = document.querySelector(".count-pr");
    let hidden_input = document.querySelector(".form_cart .hide");

    plusBtn.addEventListener("click", () => {
        input.value = +input.value + 1;
        hidden_input.value = input.value;
    })

    minusBtn.addEventListener("click", () => {
        if (input.value !== "1") {
            input.value = +input.value - 1;
            hidden_input.value = input.value;
        }
    })
</script>


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