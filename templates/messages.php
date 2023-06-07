<?php 
include("../includes/header3.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u'])) {
    $user_to = $_GET['u'];
} else {
    $user_to = $message_obj->getMostRecentUser();
    if($user_to == false) {
        $user_to = 'new';
    }
}

if($user_to != 'new') {
    $user_to_obj = new User($con, $user_to);
}

if(isset($_POST['post_message'])) {
    if(isset($_POST['message_body'])) {
        $body = $con->real_escape_string($_POST['message_body']);
        $date = date('Y-m-d H:i:s');
        $message_obj->sendMessage($user_to, $body, $date);
    }
}
?>
<!-- Moved the <head> tag outside of the PHP code block -->
<head>
    <link rel="stylesheet" href="../templates/background.css">
</head>
<body class="text-white">

    
<button data-drawer-target="separator-sidebar" data-drawer-toggle="separator-sidebar" aria-controls="separator-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="separator-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full mt-10 px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
   <div class="user_details column mx-4" id="conversations">
   <a class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
         href="messages.php?u=new">Conversatie noua</a><br>
			<h4 class="mt-4">Conversatii</h4>
			<div class="loaded_conversations">
                <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
                    <li>
                    <?php echo $message_obj->getConvos(); ?>
                    </li>
                </ul>
			</div>
            
		</div>
   </div>
</aside>

<div class="p-4 sm:ml-64">
   
</div>

    <div class="">
   
    <div class="p-4 sm:ml-64 flex items-center justify-center">
        
        <div class="w-1/2 mt-8">

        <!-- users with whom I have had conversations -->

      
        <?php  
        if($user_to != 'new') {
            echo "<h4 class='text-white'>You and <a href='" . $user_to_obj->getUsername() . "'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr><br>";

            echo "<div class='loaded_messages text-white' id='scroll_messages'>";
            echo $message_obj->getMessages($user_to);
            echo "</div>";
        } else {
            echo "<h4 class='text-white'>New Message</h4>";
        }
        ?>

        <div class="loaded_messages text-white">
            <form action="" method="POST">
                <?php
                if($user_to == 'new') {
                    echo "Select the friend you would like to message <br><br>";
                    echo 'To: <input type="text" onkeyup="getUsers(this.value, \'' . $userLoggedIn . '\')" name="q" placeholder="Name" autocomplete="off" id="seach_text_input" class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400">';
                    echo "<div class='results'></div>";
                } else {
                    echo '<textarea name="message_body" id="message_textarea" rows="8" class="block w-full p-4 rounded-3xl text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="Write your message ..."></textarea>';
                    echo '<button type="submit" name="post_message" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm mt-4 px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" id="message_submit">Send</button>';
                }
                ?>
            </form>
            </div>
        <!-- Removed the unnecessary commented-out JavaScript code -->

</div>
</div>
</body>
