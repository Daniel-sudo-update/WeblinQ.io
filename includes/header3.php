<?php
require '../config/config.php';
include("../includes/user.php");
include("../includes/Post.php");
include("../includes/Message.php");
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



<!-- navbar -->
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--FLOWBITE AND TAILWIND  -->
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
  
 
  <link rel="stylesheet" href="../includes/style.css">
  <script src="../includes/WeblinQ.js"></script>
  <link href="../src/output.css" rel="stylesheet">

 
</head>
<body>


<nav class="fixed top-0 z-50 h-14 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
<?php
				//Mesajele necitite
				$messages = new Message($con, $userLoggedIn);
				$num_messages = $messages->getUnreadNumber();
        //Notificari necitite
				$notifications = new Notification($con, $userLoggedIn);
				$num_notifications = $notifications->getUnreadNumber();
         //Unread friend request
				$user_obj = new User($con, $userLoggedIn);
				$num_requests = $user_obj->getNumberOfFriendRequests();
			?>

 <div class="px-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
        <button  type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
               <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
         </button>
        <a href="../templates/home.php" class="flex ml-2 md:mr-24">
          
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">WeblinQ</span>
        </a>
      </div>
      


      <div class="flex items-center">
        

         <!-- home button -->
         <div class="mr-1">
                <a href="../templates/home.php" class="inline-flex items-center p-2 ml-3 text-sm font-medium text-gray-900 truncate dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>

                </a>
            </div>
            <!-- end home button -->
          <!-- message button -->
          <div class="mr-1">
                  <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')" class="inline-flex items-center p-2 ml-3 text-sm font-medium text-gray-900 truncate dark:text-gray-300">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>

                  <?php
                    if($num_messages > 0)
                    echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
                    ?>


                  </a>
              </div>
              <!-- end message button -->


              <!-- notification button -->
              <div class="mr-1">
                  <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')" class="inline-flex items-center p-2 ml-3 text-sm font-medium text-gray-900 truncate dark:text-gray-300">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                  </svg>


                  <?php
                    if($num_notifications > 0)
                    echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
                    ?>


                  </a>
              </div>
              <!-- end notification button -->

            
             <!-- friends requests list -->
      <button id="dropdownUsersButton" data-dropdown-toggle="dropdownUsers" data-dropdown-placement="bottom" class="text-white  focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center " type="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
            <?php
              if($num_requests > 0)
              echo '<span class="notification_badge" id="unread_request">' . $num_requests . '</span>';
              ?>

        </button>
        <!-- Dropdown menu -->
        <div id="dropdownUsers" class="z-10 hidden rounded-lg shadow w-auto dark:bg-gray-700">
          <ul class="h-auto overflow-y-auto text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUsersButton">
            <li class="text-center">
            <?php  

                  $query = mysqli_query($con, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");
                  if(mysqli_num_rows($query) == 0)
                    echo "You have no friend requests at this time!";
                  else {

                    while($row = mysqli_fetch_array($query)) {
                      $user_from = $row['user_from'];
                      $user_from_obj = new User($con, $user_from);
                         ?> 
                            <hr class="h-px my-1 bg-gray-500 border-0 dark:bg-gray-900">
                         <?php
                      echo $user_from_obj->getFirstAndLastName();

                      $user_from_friend_array = $user_from_obj->getFriendArray();

                      if(isset($_POST['accept_request' . $user_from ])) {
                        $add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$user_from,') WHERE username='$userLoggedIn'");
                        $add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$userLoggedIn,') WHERE username='$user_from'");

                        $delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
                        echo "You are now friends!";
                        header("Location: home.php");
                      }

                      if(isset($_POST['ignore_request' . $user_from ])) {
                        $delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
                        echo "Request ignored!";
                        header("Location: home.php");
                      }

                      ?>
                      <form action="home.php" method="POST">
                        <input type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-1 mr-2 ml-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" name="accept_request<?php echo $user_from; ?>" id="accept_button" value="Accept">
                        <input type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-1 mr-2 ml-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" name="ignore_request<?php echo $user_from; ?>" id="ignore_button" value="Ignore">
                      </form>
                      <?php


                    }

                  }

                  ?>
            </li>
            <li>
              
            </li>
          </ul>
          
        </div>
<!-- end friends requests list -->
            
          <div class="flex items-center ml-3">
            <div>
              <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                <span class="sr-only">Open user menu</span>
                <img class="w-8 h-8 rounded-full" src="<?php echo $user["profile_pic"]; ?>" alt="user photo">
              </button>
            </div>
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
              <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900 dark:text-white" role="none">
                  <?php echo $user['first_name']; 
                  
                        ?>
                </p>
                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                  <?php echo $user['email']; ?>
                </p>
              </div>
              <ul class="py-2 px-2" role="none">
                <li>
                <a href="../log/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Schimbă contul</a>
                </li>
              
                <li>
                    
                  <a href="../settings/settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Setări</a>
                </li>
                
                <li>
                  <a href="../log/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Deconectare</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
  </div>
</nav>


<div class="dropdown_data_window" style="height:0px; border:noone;">
  <input type="hidden" id="dropdown_data_type" value="">
</div>


<!-- scriptul pentru scroll la tabul de mesage -->
<script>
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';

	$(document).ready(function() {

		$('.dropdown_data_window').scroll(function() {
			var inner_height = $('.dropdown_data_window').innerHeight(); //Div containing data
			var scroll_top = $('.dropdown_data_window').scrollTop();
			var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
			var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

			if ((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {

				var pageName; //Holds name of page to send ajax request to
				var type = $('#dropdown_data_type').val();


				if(type == 'notification')
					pageName = "ajax_load_notifications.php";
				else if(type = 'message')
					pageName = "ajax_load_messages.php"


				var ajaxReq = $.ajax({
					url: "includes/handlers/" + pageName,
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
					cache:false,

					success: function(response) {
						$('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current .nextpage 
						$('.dropdown_data_window').find('.noMoreDropdownData').remove(); //Removes current .nextpage 


						$('.dropdown_data_window').append(response);
					}
				});

			} //End if 

			return false;

		}); //End (window).scroll(function())


	});

	</script>


<!-- finalul scriptului pentru scroll la tabul de mesage -->





<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
     
      <!-- FLOWBITE AND TAILWIND -->
      
    </body>
</html>