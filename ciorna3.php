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


