<?php 
include("../includes/header3.php");



if(isset($_GET['profile_username'])) {
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	$num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}



if(isset($_POST['remove_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->removeFriend($username);
}

if(isset($_POST['add_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->sendRequest($username);
}
if(isset($_POST['respond_request'])) {
	header("Location: requests.php");
}


 ?>
	


<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
   
   <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
<!-- <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css"> -->

<!-- link si script aduse pt a merge weblink.js --> 
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.5.2/dist/cdn.min.js" defer></script>

  <link rel="stylesheet" href="../includes/style.css">
	<link rel="stylesheet" href="../templates/background.css">
   <link rel="stylesheet" href="../css/style.css">
   
<link href="../src/output.css" rel="stylesheet">



</head>
<body>
<!-- <div class=" sm:ml-64">
   <div class=" border-0 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14 aria-hidden">
      
</div>
</div> -->
<!-- component -->
  <!-- component -->
<div class="">
  
   <main class="profile-page">
   <section class="relative block h-500-px dark:bg-gray-800 dark:border-gray-700">
      <div class="absolute top-0 w-full h-full bg-center bg-cover" style="
               background-image: url('https://images.unsplash.com/photo-1499336315816-097655dcfbda?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=2710&amp;q=80');
            ">
         <span id="blackOverlay" class="w-full h-full absolute opacity-50 dark:bg-gray-800 dark:border-gray-700"></span>
      </div>
      
   </section>
   <section class="relative py-16 dark:bg-gray-900 dark:border-gray-700">
      <div class="container mx-auto px-4">
         <div class="relative text-white flex flex-col min-w-0 break-words bg-gradient-to-br from-gray-800 to-gray-700 dark:border-gray-700 w-full mb-6 shadow-xl rounded-3xl -mt-64 ">
         <div class="px-6">
            <div class="flex flex-wrap justify-center">
               <div class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
               <div class="relative">
                  <img alt="..." src="<?php echo $user_array['profile_pic']; ?>" class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px">
               </div>
               </div>
               <div class="w-full lg:w-4/12 px-4 lg:order-3 lg:text-right lg:self-center">
               <div class="py-6 px-3 mt-32 sm:mt-0 text-white">
               <form action="<?php echo $username; ?>" method="POST">
                  <?php 
                  require_once '../includes/user.php';
                  $profile_user_obj = new User($con, $username); 
                  if($profile_user_obj->isClosed()) {
                     header("Location: user_closed.php");
                  }

                  $logged_in_user_obj = new User($con, $userLoggedIn); 

                  if($userLoggedIn != $username) {

                     if($logged_in_user_obj->isFriend($username)) {
                        echo '<input type="submit" name="remove_friend" class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" value="Sterge prieten"><br>';
                     }
                     else if ($logged_in_user_obj->didReceiveRequest($username)) {
                        echo '<input type="submit" name="respond_request" class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" value="Rapunde la cerere"><br>';
                     }
                     else if ($logged_in_user_obj->didSendRequest($username)) {
                        echo '<input type="submit" name="" class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" value="Cerere trimisa"><br>';
                     }
                     else 
                        echo '<input type="submit" name="add_friend" class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150" value="Adauga prieten"><br>';

                  }



                  ?>


               </form>
               </div>
               </div>
               <div class="w-full lg:w-4/12 px-4 lg:order-1">
               <div class="flex justify-center py-4 lg:pt-4 pt-8">
                  <div class="mr-4 p-3 text-center">
                     <span class="text-xl text-white font-bold block uppercase tracking-wide text-blueGray-600"><?php 
                                       $num_friends = isset($num_friends) ? $num_friends : 0;
                                       echo $num_friends;
                                 ?></span><span class="text-sm text-white text-blueGray-400">Prieteni</span>
                  </div>
                  <div class="mr-4 p-3 text-center">
                     <span class="text-xl text-white font-bold block uppercase tracking-wide text-blueGray-600"><?php 
                                       $num_posts = isset($user_array['num_posts']) ? $user_array['num_posts'] : 0;
                                       echo $num_posts;
                                    ?></span><span class="text-sm text-white text-blueGray-400">Postari</span>
                  </div>
                  <div class="lg:mr-4 p-3 text-center">
                     <span class="text-xl text-white font-bold block uppercase tracking-wide text-blueGray-600">  <?php 
                                       $num_likes = isset($user_array['num_likes']) ? $user_array['num_likes'] : 0;
                                       echo $num_likes;
                                 ?></span><span class="text-sm text-white text-blueGray-400">Aprecieri</span>
                  </div>
               </div>
               </div>
            </div>
            <div class="text-center mt-12">
               <h3 class="text-5xl text-white font-semibold leading-normal mb-2 text-blueGray-700 mb-2">
               <?php echo $username; ?>
               </h3>
               <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold uppercase">
               <i class="fas fa-map-marker-alt mr-2 text-lg text-blueGray-400"></i>
               Bacau Romania
               </div>
               <div class="mb-2  mt-10">
               <i class="fas fa-briefcase mr-2 text-lg text-white"></i> Job: <?php 
                                       $job = isset($user_array['job']) ? $user_array['job'] : 0;
                                       echo $job;
                                 ?>
               </div>
               <div class="mb-2 ">
               <i class="fas fa-university mr-2 text-lg  text-white"></i>Universitatea Vasile Alecsandri
               </div>
            </div>
            <div class="mt-10 py-10 border-t border-blueGray-200 text-center">
               <div class="flex flex-wrap justify-center">
               <div class="w-full lg:w-9/12 px-4">
               <p class="mb-4 text-lg leading-relaxed text-white text-left">Studii finalizate: <br>
                  <?php 
                                       $studii = isset($user_array['studii']) ? $user_array['studii'] : 0;
                                       echo $studii;
                                 ?>
                  </p>
                  <p class="mb-4 text-lg leading-relaxed text-white text-left">Profesori: <br>
                  <?php 
                                       $profi = isset($user_array['profesori']) ? $user_array['profesori'] : 0;
                                       echo $profi;
                                 ?>
                  </p>
                  <p class="mb-4 text-lg leading-relaxed text-white text-left">Despre mine/Proiecte realizate: <br>
                  <?php 
                                       $despre = isset($user_array['about']) ? $user_array['about'] : 0;
                                       echo $despre;
                                 ?>
                  </p>
                  <div>
                    
                  </div>
                  
               </div>
               </div>
            </div>
         </div>
         </div>
      </div>
      
   </section>
   </main>

<!-- colegi -->

<?php 
                    $grupa = isset($user_array['grupa']) ? $user_array['grupa'] : 0;
                    $generatie = isset($user_array['generatia']) ? $user_array['generatia'] : 0;

                    $user_list_query = mysqli_query($con, "SELECT profile_pic, first_name, last_name, phone, email FROM users WHERE `grupa` = '$grupa' AND `generatia` = '$generatie'");
                  
                    echo "
                    <section class='bg-white dark:bg-gray-800'>
                      <div class='py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6'>
                      <div class='mx-auto max-w-screen-sm text-center mb-8 lg:mb-16'>
                           <h2 class='mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white'>Grupa mea</h2>
                     </div>
                        <div class='grid gap-8 mb-6 lg:mb-16 md:grid-cols-3'>";
                        
                    
                    while ($user_info = mysqli_fetch_assoc($user_list_query)) {
                        $profile_pic = $user_info['profile_pic'];
                        $fname = $user_info['first_name'];
                        $lname = $user_info['last_name'];
                        $phone = $user_info['phone'];
                        $email = $user_info['email'];
                    
                        echo "
                          <div class='items-center bg-gray-50 rounded-lg shadow sm:flex dark:bg-gray-700 dark:border-gray-600'>
                            <a href='#'>
                              <img class='w-full rounded-lg sm:rounded-none sm:rounded-l-lg' src='$profile_pic'>
                            </a>
                            <div class='p-5'>
                              <h3 class='text-xl font-bold tracking-tight text-gray-900 dark:text-white'>
                                <a href='#'>$fname $lname</a>
                              </h3>
                              <p class='mt-3 mb-4 font-light text-gray-500 dark:text-gray-400'>Telefon:$phone</p>
                              <p class='mt-3 mb-4 font-light text-gray-500 dark:text-gray-400'>Email:$email</p>
                            </div>
                          </div>";
                    }
                    
                    echo " 
                        </div>
                      </div>
                    </section>";
                    

                     ?>

<!-- end colegi -->

  <!-- PUBLISH POST FORM  -->

  <!-- modal -->
 

  <div class="posts_area " ></div>
  
              <!--  modal for posting thing on profiles-->
<!-- Main modal -->
   <div id="post_form" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
   <div class="relative w-full h-full max-w-md md:h-auto">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
         <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="post_form">
         <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
         <span class="sr-only">Close modal</span>
         </button>
         <div class="px-6 py-6 lg:px-8 modal-body">
         <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Posteaza ceva</h3>
         <form class="space-y-6" action="" method="POST">
            <textarea name="post_body" id="editor" rows="8" class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="Noutăți..."></textarea>
            <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
            <input type="hidden" name="user_to" value="<?php echo $username; ?>">
            <button type="submit" id="submit_profile_post" name="post_button" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Postează</button>
         </form>
         </div>
      </div>
   </div>
   </div>

<!-- publish content -->

               
<div class="d-flex justify-content-center scrollbar-container" style="color:white">
               <!-- <div class="posts_area scrollbar-hide" ></div> -->
               
                   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                  <script>
                     
                     var userLoggedIn = '<?php echo $userLoggedIn; ?>';
                     var profileUsername = '<?php echo $username; ?>';
                     

                     $(document).ready(function() {

                        $('#loading').show();

                        //Original ajax request for loading first posts 
                        $.ajax({
                           url: "../includes/ajax_load_profile_posts.php",
                           type: "POST",
                           data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
                           cache:false,

                           success: function(data) {
                              $('#loading').hide();
                              $('.posts_area').html(data);
                           }
                        });
                     //   scroll start
                   

                        $(window).scroll(function() {
                           var height = $('.posts_area').height(); //Div containing posts
                           var scroll_top = $(this).scrollTop();
                           var page = $('.posts_area').find('.nextPage').val();
                           var noMorePosts = $('.posts_area').find('.noMorePosts').val();

                           if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
                              $('#loading').show();
                                 
                                 // Load friends' posts using AJAX request
                                 $.ajax({
                                    url: "../includes/ajax_load_profile_posts.php",
                                    type: "POST",
                                    data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
                                    cache: false,
                                    success: function(response) {
                                          $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
                                          $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 
                                          $('#loading').hide();
                                          $('.posts_area').append(response);
                                    }
                                 });
                                 

                           } //End if 

                           return false;

                        }); //End (window).scroll(function())
                        // scroll end    /

                     });

                  </script>


               </div>


               <!-- publish content end -->

</div>
               
	    <!-- FLOWBITE AND TAILWIND -->
  
</body>
</html>