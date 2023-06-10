<?php  
$error_array = array(); //Vector de erori

if(isset($_POST['login_button'])) {

	$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); //email

	$_SESSION['log_email'] = $email; //Stocarea email-ului in sectiuni de variabile
	$password = md5($_POST['log_password']); //Preia parola

	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
	$check_login_query = mysqli_num_rows($check_database_query);

	$secret = "6Lc4GXomAAAAACQce16MtOBkfQUbh7EtxvpySyNB";
	$response = $_POST['g-recaptcha-response'];
	$remoteip = $_SERVER['REMOTE_ADDR'];
	$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
	$data = file_get_contents($url);
	$row = json_decode($data, true);
	
	if($check_login_query == 1 && ($row['success'] == "true")) {
		$row = mysqli_fetch_array($check_database_query);
		$username = $row['username'];

		$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
		if(mysqli_num_rows($user_closed_query) == 1) {
			$reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
		}

		$_SESSION['username'] = $username;
		header("Location: ./templates/home.php");
		exit();
	}
	else {
		array_push($error_array, "Email, parola incorecta, sau ai uitat Recaptcha.<br>");
	}
}
?>