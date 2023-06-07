
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
  

<?php 


//Get id of post
    if(isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }

        $get_likes = mysqli_query($con, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
        $row = mysqli_fetch_array($get_likes);
        $total_likes = $row['likes']; 
        $user_liked = $row['added_by'];

        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user_liked'");
        $row = mysqli_fetch_array($user_details_query);
        $total_user_likes = $row['num_likes'];

    //Like button
        if(isset($_POST['like_button'])) {
            $total_likes++;
            $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
            $total_user_likes++;
            $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
            $insert_user = mysqli_query($con, "INSERT INTO likes VALUES('', '$userLoggedIn', '$post_id')");

           	//Insert Notification
            if($user_liked != $userLoggedIn) {
              $notification = new Notification($con, $userLoggedIn);
              $notification->insertNotification($post_id, $user_liked, "like");
            }
            
        }
    //Unlike button
        if(isset($_POST['unlike_button'])) {
            $total_likes--;
            $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
            $total_user_likes--;
            $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
            $insert_user = mysqli_query($con, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
        }


        // check for previous likes
        $check_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
        $num_rows = mysqli_num_rows($check_query);

        if($num_rows > 0) {

            echo '
            <div>
            <form action="like.php?post_id=' . $post_id . '" method="POST" style="color:white">
            <div class="like_value inline ">
            ('. $total_likes .')
            </div>
            <button type="submit" class="comment_like inline" name="unlike_button" style="background: none; border-0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15h2.25m8.024-9.75c.011.05.028.1.052.148.591 1.2.924 2.55.924 3.977a8.96 8.96 0 01-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398C20.613 14.547 19.833 15 19 15h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 00.303-.54m.023-8.25H16.48a4.5 4.5 0 01-1.423-.23l-3.114-1.04a4.5 4.5 0 00-1.423-.23H6.504c-.618 0-1.217.247-1.605.729A11.95 11.95 0 002.25 12c0 .434.023.863.068 1.285C2.427 14.306 3.346 15 4.372 15h3.126c.618 0 .991.724.725 1.282A7.471 7.471 0 007.5 19.5a2.25 2.25 0 002.25 2.25.75.75 0 00.75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 002.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384" />
          </svg> 

            </button>
           
          </form>
          </div>';
          
        }
        else {
            echo '
            </div>
            <form action="like.php?post_id=' . $post_id . '" method="POST" style="color:white">
            <div class="like_value inline m-1">
              ('. $total_likes .')
            </div>
            <button type="submit" class="comment_like inline m-0" name="like_button" style="background: none; border-0;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 py-0.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
          </svg> 
          
            </button>
            
          </form>
          </div>';
        }



?>




<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
      <script src="../node_modules/flowbite/dist/flowbite.min.js"></script>
</body>
</html>