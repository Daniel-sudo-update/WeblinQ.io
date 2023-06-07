<?php  
include("../config/config.php");
include("../includes/user.php");
include("../includes/Post.php");

$limit = 10; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->loadPostsFriends($_REQUEST, $limit);



?>