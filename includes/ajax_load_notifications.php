<?php
include("../config/config.php");
include("../includes/user.php");
include("../includes/Notification.php");

$limit = 8; //Number of messages to load

$notification = new Notification($con, $_REQUEST['userLoggedIn']);
echo $notification->getNotifications($_REQUEST, $limit);

?>