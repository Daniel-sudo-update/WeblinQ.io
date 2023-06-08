<html>  
<head>  
    <title>Forgot Password</title>  
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />   -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link href="../src/output.css" rel="stylesheet"> 
</head>

<?php
require("../config/config.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if(isset($_REQUEST['pwdrst']))
{
  $email = $_REQUEST['email'];
  $check_email = mysqli_query($connection,"select email from users where email='$email'");
  $res = mysqli_num_rows($check_email);
  if($res>0)
  {
    $message = '<div>
     <p><b>Hello!</b></p>
     <p>Ai primit acest email, deoarece s-a creat o cerere de resetare a parolei.</p>
     <br>
     <p><button class="btn btn-primary"><a href="https://weblinq/log/passwordreset.php?secret='.base64_encode($email).'">Resetare parola</a></button></p>
     <br>
     <p>Daca nu ai făcut o cerere de resetare a parolei, nu fă nimic.</p>
    </div>';

$email = $email; 
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;                 
$mail->SMTPSecure = "tls";      
$mail->Host = 'smtp.mailgun.org';
$mail->Port = 587; 
$mail->Username = "postmaster@sandbox334b587771084448b5262bfe125f2fa1.mailgun.org";   //Enter your Mailgun username
$mail->Password = "4b0a57462c9572aebb42aeb9177de227-6d1c649a-cd9c77ca";   //Enter your Mailgun password
$mail->FromName = "WeblinQ";
$mail->AddAddress($email);
$mail->Subject = "Resetare_parolă";
$mail->isHTML( TRUE );
$mail->Body =$message;
if($mail->send())
{
  $msg = "v-am trimis prin email link-ul de resetare al parolei";
}
}
else
{
  $msg = "Nu putem gasi un utilizator pentru adresa aceasta";
}
}
?>

<body class="bg-gray-800 " text="#ffffff">
<div class=" w-full max-w-md m-auto mt-4 h-screen flex justify-center items-center">  
    <div>
    <h3 align="center" class="text-gray-300">Ai uitat parola?</h3><br/>
    
     <form id="validate_form" method="post" class="bg-gray-900 rounded px-8 pt-6 pb-8 mb-4">  
       <div class="relative z-0 w-full mb-4 group">
            <label for="email" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Adresa de email</label>
            <input type="text" name="email" id="email" placeholder="Email" required class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            data-parsley-type="email" data-parsley-trigg
            er="keyup" class="form-control" />
      </div>

      <div class="relative z-0 w-full mb-4 group">
            <input type="submit" id="login" name="pwdrst" value="Trimiteți linkul de resetare al parolei!" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" />
       </div>
       
       <p class="error"><?php if(!empty($msg)){ echo $msg; } ?></p>
     </form>
     </div>
    
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <!-- nu modifica mai jos -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
   
</body>
</html>