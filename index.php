<?php  
include("../includes/header.php");
if(isset($_POST['post'])){
	$post = new Post($con, $userLoggedIn);
	$post->submitPost($_POST['post_text'], 'none');
  header("Location: index.php");
}

?>
<!-- <!doctype html> -->
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="./background.css">
  <link rel="stylesheet" href="../css/style.css">


 
 <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
 <link href="../src/output.css" rel="stylesheet">
</head>
<body >
<div class="p-4 sm:ml-64 ">
  <div class="mt-14">
               <!-- END PUBLISH FORM -->
<!-- publish content -->              
               <div class="d-flex justify-content-center scrollbar-container" style="color:white">
               <div class="posts_area " ></div>
               
                   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                  <script>
                     
                     var userLoggedIn = '<?php echo $userLoggedIn; ?>';
                     

                     $(document).ready(function() {

                        $('#loading').show();

                        //Original ajax request for loading first posts 
                        $.ajax({
                           url: "../includes/ajax_load_posts.php",
                           type: "POST",
                           data: "page=1&userLoggedIn=" + userLoggedIn,
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
                                    url: "../includes/ajax_load_posts.php",
                                    type: "POST",
                                    data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
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
   </div>




   
    </body>
</html>

