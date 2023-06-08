<?php  
require("../config/config.php");

?>
<?php
use Mailgun\Mailgun;

require '../vendor/autoload.php';

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $email = $_POST["email"];

    $studentCard = $_FILES["studentCard"];

    // Check if file is an image
    $check = getimagesize($_FILES["studentCard"]["tmp_name"]);
    if($check === false) {
        die("Fișierul încărcat nu este o imagine!");
    }

    // Store the uploaded file
    $target_dir = "../defaults/uploads/";
    $target_file = $target_dir . time() . "_" . basename($_FILES["studentCard"]["name"]);

    if (!move_uploaded_file($_FILES["studentCard"]["tmp_name"], $target_file)) {
        die("A avut loc o eroarea la incarcare.");
    }

    // Instantiate the Mailgun SDK with your API key
    $mgClient = Mailgun::create('4b0a57462c9572aebb42aeb9177de227-6d1c649a-cd9c77ca'); // actualizează cheia de acces aici

    // Set the message parameters
    $params = array(
        'from'    => 'Registration Form <confirm.informatii@gmail.com>',
        'to'      => 'Contact Info <confirm.informatii@gmail.com>',
        'subject' => 'New Registration',
        'text'    => "First Name: $firstName\nEmail: $email",
        'attachment' => array($target_file)
    );

    // Send the message
    try {
        $result = $mgClient->messages()->send('sandbox334b587771084448b5262bfe125f2fa1.mailgun.org', $params); // actualizează domeniul aici
        echo '<b>Mesaj trimis, așteaptă raspunsul cu link-ul de inregistrare pe email!</b>';
    } catch (Exception $e) {
        echo 'Eroare la Mailer: ' . $e->getMessage();
    }
}
?>


<html>
<head>
	<title>Bun venit!</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
	<link href="../src/output.css" rel="stylesheet"> 
</head>
<body class="bg-gray-800 " text="#ffffff">
  <div class=" w-full max-w-md m-auto mt-4 h-screen flex justify-center items-center">
    <form action="contact.php" method="post" enctype="multipart/form-data" class="bg-gray-900 rounded px-8 pt-6 pb-8 mb-4">
      <div class="relative z-0 w-full mb-4 group">
        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200" for="firstName">
          Nume
        </label>
        <input class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="firstName" name="firstName" type="text" required>
      </div>
      <div class="mb-4">
        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200" for="email">
          Email
        </label>
        <input class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" id="email" name="email" type="email" required>
      </div>
      <div class="mb-4">
        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200" for="studentCard">
          Încarcă o imagine cu: legitimatia/carnetul de student
        </label>
        <input class="border rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none focus:shadow-outline" id="studentCard" name="studentCard" type="file" required>
      </div>
      <div class="flex items-center justify-between">
        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="submit">
          Trimite
        </button>
      </div>
    </form>


  </div>













<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <!-- nu modifica mai jos -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
   
</body>
</html>