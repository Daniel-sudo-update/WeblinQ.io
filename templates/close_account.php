<?php
include("../includes/header3.php");

if(isset($_POST['cancel'])) {
	header("Location: ../settings/settings.php");
}

if (isset($_POST['close_account'])) {
    $stmt = $con->prepare("UPDATE users SET user_closed='yes' WHERE username=?");
    $stmt->bind_param("s", $userLoggedIn);
    $stmt->execute();

    session_destroy();
    header("Location: ../log/register.php");
}

?>

<head> 
<link rel="stylesheet" href="./background.css">
<link rel="stylesheet" href="../css/style.css">
</head>
<div class="p-4 sm:ml-64 text-white">
  <div class="mt-14">
     <div class="flex items-center  lg:w-3/5 mx-auto mb-10 bg-gray-800 h-auto rounded-3xl sm:flex-row flex-col my-8 status_post">
      <div class="flex-grow sm:text-left p-4 mt-6 sm:mt-0">
	   <div>

		<h4 class="">Close Account</h4>

			Are you sure you want to close your account?<br><br>
			Closing your account will hide your profile and all your activity from other users.<br><br>
			You can re-open your account at any time by simply logging in.<br><br>

			<form action="close_account.php" method="POST">
				<input type="submit" name="close_account" id="close_account" value="Yes! Close it!" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
				<input type="submit" name="cancel" id="update_details" value="No way!" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
			</form>
		
	   </div>
	 </div>
   </div>
</div>