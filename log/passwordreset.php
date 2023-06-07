<html>  
<head>  
    <title>Password Reset</title>  
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />   -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link href="../src/output.css" rel="stylesheet"> 
</head>

<?php
require("../config/config.php");
if(isset($_REQUEST['pwdrst']))
{
  $email = $_REQUEST['email'];
  $pwd = md5($_REQUEST['pwd']);
  $cpwd = md5($_REQUEST['cpwd']);
  if($pwd == $cpwd)
  {
    $reset_pwd = mysqli_query($connection,"update users set password='$pwd' where email='$email'");
    if($reset_pwd>0)
    {
      $msg = 'Parola a fost actualizată cu succes <a href="../log/login.php" class="text-blue-600">Click ici</a> pentru a vă conecta';
    }
    else
    {
      $msg = "Eroare in schimbarea parolei!";
    }
  }
  else
  {
    $msg = "Parolele nu sunt identice!";
  }
}

if($_GET['secret'])
{
  $email = base64_decode($_GET['secret']);
  $check_details = mysqli_query($connection,"select email from users where email='$email'");
  $res = mysqli_num_rows($check_details);
  if($res>0)
    { ?>
<body class="bg-gray-800 " text="#ffffff">
<div class=" w-full max-w-md m-auto mt-4 h-screen flex justify-center items-center">  
    <div class="table-responsive">  
        <h3 align="center" class="text-gray-300">Resetare parolă</h3><br/>
        <div class="box">
            <form id="validate_form" method="post" class="bg-gray-900 rounded px-8 pt-6 pb-8 mb-4">  
                <input type="hidden" name="email" value="<?php echo $email; ?>"/>
                <div class="form-group">
                    <label for="pwd" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Parola</label>
                    <input type="password" name="pwd" id="pwd" placeholder="Parola" required 
                    data-parsley-type="pwd" data-parsley-trigg
                    er="keyup" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                </div>
                <div class="form-group">
                    <label for="cpwd" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Confirmă parola</label>
                    <input type="password" name="cpwd" id="cpwd" placeholder="Confirmă parola" required data-parsley-type="cpwd" data-parsley-trigg
                    er="keyup" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                </div><br>
                    <div class="form-group">
                    <input type="submit" id="login" name="pwdrst" value="Resetare parola" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" />
                </div>
                
                <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
            </form>
        </div>
   </div>  
  </div>
<?php } } ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <!-- nu modifica mai jos -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
   
</body>
</html>