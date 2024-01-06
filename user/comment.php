<?php

include "init.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $comment = htmlspecialchars($_POST["comment"]);
   $rate = $_POST["rate"];
   $userID = $_SESSION["id"];
   $itemID = $_POST["itemID"];

   $referer = $_SERVER["HTTP_REFERER"];

   if (!empty($comment)) {
      $stm = $conn->prepare("INSERT INTO comments (comment , rating , time , item_id , user_id) VALUES (? , ? , CURRENT_TIME() , ? , ?)");
      $stm->execute(array($comment, $rate, $itemID, $userID));

      // Redirect To Product Page
      header("Location: $referer");
      exit();
   } else {

      // Redirect To Product Page
      header("Location: $referer");
      exit();
   }
} else {
   echo "Page Not Found";
}
