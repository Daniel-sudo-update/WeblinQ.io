<?php  
require '../config/config.php';
include("../includes/user.php");
include("../includes/Post.php");
include("../includes/Notification.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(isset($_POST['post_body'])) {

	$post = new Post($con, $_POST['user_from']);
	$post->submitPost($_POST['post_body']  , $_POST['user_to']);
}
	
?>