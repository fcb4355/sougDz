<?php 

	if(isset($_SERVER['HTTP_REFERER'])){
		session_start();

		session_unset();

		session_destroy();

		header("Location: login.php");
		exit();
	}else{
		header("Location: index.php");
		exit();
	}
?>