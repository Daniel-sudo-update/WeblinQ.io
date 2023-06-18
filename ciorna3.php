<?php

if (isset($_POST['submit'])) {
  $secret = "YOUR_SECRET_KEY";
  $response = $_POST['g-recaptcha-response'];
  $remoteip = $_SERVER['REMOTE_ADDR'];
  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
  $data = file_get_contents($url);
  $row = json_decode($data, true);

  if ($row['success'] == "true") {
    echo "<script>alert('Wow you are not a robot 😲');</script>";
  } else {
    echo "<script>alert('Oops you are a robot 😡');</script>";
  }
}


else
{
 $msg = "Nu putem gasi un utilizator pentru adresa aceasta";
}

$post_body = $_POST['post_body'];




























// mama mia terasa 

// 0729136518


// if (isset($_POST['update_password'])) {
//     $old_password = strip_tags($_POST['old_password']);
//     $new_password_1 = strip_tags($_POST['new_password_1']);
//     $new_password_2 = strip_tags($_POST['new_password_2']);
//     $stmt = $con->prepare("SELECT password FROM users WHERE username=?");
//     $stmt->bind_param("s", $userLoggedIn);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $row = $result->fetch_assoc();
//     $db_password = $row['password'];
//     if (md5($old_password) == $db_password) {
//         if ($new_password_1 == $new_password_2) {
//             if (strlen($new_password_1) <= 4) {
//                 $password_message = "Parola trebuie sa conțină minim 4 caractere<br><br>";
//             } else {
//                 $new_password_md5 = md5($new_password_1);
//                 $stmt = $con->prepare("UPDATE users SET password=? WHERE username=?");
//                 $stmt->bind_param("ss", $new_password_md5, $userLoggedIn);
//                 $stmt->execute();
//                 $password_message = "Parola a fost schimbată!<br><br>"; }
//         } else {
//             $password_message = "Parolele nu se potrivesc!<br><br>";}
//     } else {
//         $password_message = "Parola veche este incorectă! <br><br>";}



?>


























































<?php  
require '../config/config.php';
include("../includes/user.php");
include("../includes/Post.php");
include("../includes/Notification.php");

if(isset($_POST['post_body'])) {
    $post_body = $_POST['post_body'];

    // Listează cuvintele pe care nu le permiți
    $cuvinte_improprii = ["curva", "curvă", "muie", "muist", "muistă", "futu-i",
						  "găoază", "găozar", "gaoaza", "gaozar", "muista", "poponar",
						  "pulă", "pula", "pula bleaga", "pulă bleagă", "sloboz",
						  "pupincurist", "căcănar", "cacanar", "puleasca", "puleașcă",
						  "prost", "prostănac", "proastă", "curvar", "curist", "curista",
						  "sugaci", "pupincurist", "labagiu", "labajiu",];
    
    // Verifică dacă postarea conține cuvinte improprii
    foreach($cuvinte_improprii as $cuvant) {
        if (mb_stripos($post_body, $cuvant) !== false) {
            // În cazul în care postarea conține un cuvânt nepotrivit, oprește procesarea și trimite un mesaj de eroare
            http_response_code(400);
            echo "Postarea conține conținut nepotrivit.";
            exit();
        }
    }

    $apiKey = getenv('AZURE_API_KEY'); // cheia API a resursei Content Moderator
    $endpoint = "https://weblinqmoderator.cognitiveservices.azure.com/contentmoderator/moderate/v1.0/ProcessText/Screen?classify=True"; // punctul de acces al resursei Content Moderator

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: text/plain",
        "Ocp-Apim-Subscription-Key: $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);

    $raspuns = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $rezultat = json_decode($raspuns, true);

    if($httpCode == 200) {
        if($rezultat["Classification"]["ReviewRecommended"] == true) {
            http_response_code(400);
            echo "Postarea conține conținut nepotrivit.";
            exit();
        }
    }

    $post = new Post($con, $_POST['user_from']);
    $post->submitPost($post_body, $_POST['user_to']);
}
error_reporting(E_ALL); ini_set('display_errors', 1);



?>