<?php  

	require '../config/config.php';
	include("../includes/user.php");
	include("../includes/Post.php");
	include("../includes/Notification.php");



		if (isset($_SESSION['username'])) {
			$userLoggedIn = $_SESSION['username'];
			$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
			$user = mysqli_fetch_array($user_details_query);
		}
		else {
			header("Location: register.php");
		}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarii</title>
	<link href="../src/output.css" rel="stylesheet">
	
</head>
<body>
  


<script>
		function toggle() {
			var element = document.getElementById("comment_section");

			if(element.style.display == "block") 
				element.style.display = "none";
			else 
				element.style.display = "block";
		}
	</script>

<?php  
	//Get id of post
	if(isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
	}

	$user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
	$row = mysqli_fetch_array($user_query);

	$posted_to = $row['added_by'];

	if (isset($_POST['postComment' . $post_id])) {
		$post_body = $_POST['post_body'];
		$post_body = mysqli_escape_string($con, $post_body);
		$date_time_now = date("Y-m-d H:i:s");
		$insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('', '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id')");
		
		// notification code
		if($posted_to != $userLoggedIn) {
			$notification = new Notification($con, $userLoggedIn);
			$notification->insertNotification($post_id, $posted_to, "comment");
		}
		
		if($user_to != 'none' && $user_to != $userLoggedIn) {
			$notification = new Notification($con, $userLoggedIn);
			$notification->insertNotification($post_id, $user_to, "profile_comment");
		}


		$get_commenters = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id'");
		$notified_users = array();
		while($row = mysqli_fetch_array($get_commenters)) {

			if($row['posted_by'] != $posted_to && $row['posted_by'] != $user_to 
				&& $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'], $notified_users)) {

				$notification = new Notification($con, $userLoggedIn);
				$notification->insertNotification($post_id, $row['posted_by'], "comment_non_owner");

				array_push($notified_users, $row['posted_by']);
			}

		}

		// end notification code
		
		echo "<p class='flex p-4 inline' style='color:green'>Comment Posted! </p>";
	}
	
	?>
<!-- form comentarii -->
          

<form class="mb-6" action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
				<div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
					<label for="comment" class="sr-only">Your comment</label>
					<textarea id="comment" rows="6" name="post_body" style="color:white"
						class=" w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
						placeholder="Write a comment..." required>
					</textarea>
				</div>
				<button type="submit" name="postComment<?php echo $post_id; ?>" value="Post"
					class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-500 rounded-lg hover:bg-blue-300">
					Post comment
				</button>

			</form>
   
<!-- end form comentarii -->

	<!-- Load comments -->

	<?php 
	$get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
	$count = mysqli_num_rows($get_comments);

		if($count !=0){
			while($comment = mysqli_fetch_array($get_comments)){
				$comment_body = $comment['post_body'];
				$posted_to = $comment['posted_to'];
				$posted_by = $comment['posted_by'];
				$date_added = $comment['date_added'];
				$removed = $comment['removed'];
			

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_added); // Time of post
					$end_date = new DateTime($date_time_now); // Current time
					$interval = $start_date->diff($end_date); // Difference between dates

					if ($interval->y >= 1) {
						$time_message = ($interval->y == 1) ? $interval->y . " year ago" : $interval->y . " years ago";
					} else if ($interval->m >= 1) {
						$days = ($interval->d == 0) ? " ago" : (($interval->d == 1) ? $interval->d . " day ago" : $interval->d . " days ago");
						$time_message = ($interval->m == 1) ? $interval->m . " month" . $days : $interval->m . " months" . $days;
					} else if ($interval->d >= 1) {
						$time_message = ($interval->d == 1) ? "Yesterday" : $interval->d . " days ago";
					}else if ($interval->h >= 1) {
						$time_message = ($interval->h == 1) ? $interval->h . " hour ago" : $interval->h . " hours ago";
					} else if ($interval->i >= 1) {
						$time_message = ($interval->i == 1) ? $interval->i . " minute ago" : $interval->i . " minutes ago";
					} else {
						$time_message = ($interval->s < 30) ? "Just now" : $interval->s . " seconds ago";
					}

					$user_obj = new User($con,$posted_by);


					?>


<!-- incarcare comentarii 1 -->
<section class="bg-white dark:bg-gray-900 py-2 comment_section ">
  <div class="max-w-2xl mx-auto px-2">
   
    <article class="p-4 mb-0 text-base bg-white rounded-lg dark:bg-gray-900 ">
        <footer class="flex justify-between items-center mb-0">
            <div class="flex items-center">
                <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
					<a>
				<img src="<?php echo $user_obj->getProfilePic() ?>" width="40" class=" rounded-full " 
						title="<?php echo $posted_by; ?>" height="20"></a>

                <p class="text-sm text-gray-600 dark:text-gray-400">
				<a href="<?php echo $posted_by ?>" target="_parent">
						<?php echo $user_obj->getFirstAndLastName() . "<br>" . $time_message; ?>	
					</a>
				</p>
            </div>
            <button id="dropdownComment1Button" data-dropdown-toggle="dropdownComment1"
                class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                type="button">
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                    </path>
                </svg>
                <span class="sr-only">Comment settings</span>
            </button>
            <!-- Dropdown menu -->
            <div id="dropdownComment1"
                class="hidden z-10 w-36 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                    aria-labelledby="dropdownMenuIconHorizontalButton">
                    <li>
                        <a href="#"
                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Remove</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Report</a>
                    </li>
                </ul>
            </div>
        </footer>
        <p class="text-gray-500 dark:text-gray-400">
			<?php echo $comment_body ?>
		</p>
        <div class="flex items-center mt-4 space-x-4">
            <button type="button"
                class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400">
                <svg aria-hidden="true" class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Reply
            </button>
        </div>
    </article>
  </div>
</section>



<!-- end incarcare comentarii 1-->






				<!-- end comment load -->
				<?php

			}
		}
		else {
			echo"<center style='color:white'><br><br>No comments to show</center>";
		}



	             ?>
	


	<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
      <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>