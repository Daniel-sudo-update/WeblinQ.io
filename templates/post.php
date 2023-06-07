<?php 
include("../includes/header2.php");

if(isset($_GET['id'])) {
	$id = $_GET['id'];
}
else {
	$id = 0;
}

?>
<head>
<link rel="stylesheet" href="./background.css">
</head>
<div class="p-4 sm:ml-64 ">
  <div class="mt-14">
<div class="main_column column" id="main_column">

		<div class="posts_area">

			<?php 
				$post = new Post($con, $userLoggedIn);
				$post->getSinglePost($id);
			?>

		</div>

	</div>
</div>
</div>