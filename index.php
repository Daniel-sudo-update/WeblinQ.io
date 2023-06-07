<?php  
require("./config/config.php");
require("./includes/index_handler.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <title>Conectare</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body text="#ffffff">  
<div class="bg-white dark:bg-gray-900">
    <div class="flex justify-center min-h-screen">
        <div class="hidden bg-cover lg:block lg:w-2/3" style="background-image: url(../Logo.jpg)">
           
        </div>
        <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
            <div class="flex-1">
                <div class="text-center">
                    <p class="mt-3 text-gray-500 dark:text-gray-300">Conectați-vă pentru a vă accesa contul</p>
                </div>
<!-- form -->
                <div class="mt-8">
                    <form action="home.php" method="POST" >
                        <div>
                            <label for="email" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Email</label>
                            <input type="email" name="log_email" id="email" placeholder="exemplu@email.com" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
                            value="<?php 
                                if(isset($_SESSION['log_email'])) {
                                    echo $_SESSION['log_email'];
                                } 
                                ?>" required>                                 
                                <br>
                        </div>
                        <div class="mt-6">
                            <div class="flex justify-between mb-2">
                                <label for="password" class="text-sm text-gray-600 dark:text-gray-200">Parola</label>
                                
                            </div>

                            <input type="password" name="log_password"" id="password" placeholder="Your Password" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            <br>
                            <?php if(in_array("E-mailul sau parola au fost incorecte<br>", $error_array)) echo  "E-mailul sau parola au fost incorecte<br>"; ?>
                        </div>
                        <!-- <?php if(in_array("E-mailul sau parola au fost incorecte<br>", $error_array)) echo  "E-mailul sau parola au fost incorecte<br>"; ?> -->
                        
                        <div class="link forget-pass text-left"style="color: gray"><a href="./log/forgot.php">Ai uitat parola?</a></div><br>
                        <div class="g-recaptcha" data-sitekey="6Lc4GXomAAAAAM1xAASeLuiXHdIlgJpBvSWBVkeW"></div>

                         
                        <div class="mt-6">
                            <input type="submit" name="login_button" placeholder="Conectare" value="Conectare" class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                         </div>

                    </form>

                    <p class="mt-6 text-sm text-center text-gray-400">Nu aveți încă un cont? <a href="./log/contact.php" class="text-blue-500 focus:outline-none focus:underline hover:underline">Inscrie-te</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<!-- nu modifica mai jos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>
</html>