<?php
//Declaring variables to prevent errors
$fname = ""; //Nume
$lname = ""; //Prenume
$em = ""; //email
$em2 = ""; //email 2
$phone = ""; //numar de telefon
$group = ""; //grupa
$grad_year = ""; //an finalizare studii
$password = ""; //parola 1
$password2 = ""; //parola 2
$date = ""; //data logarii
$error_array = array(); //Vertor de erori

if(isset($_POST['register_button'])){

	// Valorile pentru inregistrari

	//Nume
	$fname = strip_tags($_POST['reg_fname']); //Sterge tagurile de html
	$fname = str_replace(' ', '', $fname); //sterge spatiile libere
	$fname = ucfirst(strtolower($fname)); //Prima litera-litera mare
	$_SESSION['reg_fname'] = $fname; 

	//Prenume
	$lname = strip_tags($_POST['reg_lname']); //Sterge tagurile de html
	$lname = str_replace(' ', '', $lname); //sterge spatiile libere
	$lname = ucfirst(strtolower($lname)); //Prima litera-litera mare
	$_SESSION['reg_lname'] = $lname; 

	//email
	$em = strip_tags($_POST['reg_email']); //Sterge tagurile de html
	$em = str_replace(' ', '', $em); //sterge spatiile libere
	$_SESSION['reg_email'] = $em; 

	//email 2
	$em2 = strip_tags($_POST['reg_email2']); //Sterge tagurile de html
	$em2 = str_replace(' ', '', $em2); //sterge spatiile libere
	$_SESSION['reg_email2'] = $em2; 

	//Numar de telefon
	$phone = strip_tags($_POST['reg_phone']); //Sterge tagurile de html
	$phone = str_replace(' ', '', $phone); //sterge spatiile libere
	$_SESSION['reg_phone'] = $phone; 

	//Grupa
	$group = strip_tags($_POST['reg_group']); //Sterge tagurile de html
	$group = str_replace(' ', '', $group); //sterge spatiile libere
	$_SESSION['reg_group'] = $group; 

	//Anul finalizarii studiilor
	$grad_year = strip_tags($_POST['reg_grad_year']); //Sterge tagurile de html
	$grad_year = str_replace(' ', '', $grad_year); //sterge spatiile libere
	$_SESSION['reg_grad_year'] = $grad_year; 

	//Parola
	$password = strip_tags($_POST['reg_password']); //Sterge tagurile de html
	$password2 = strip_tags($_POST['reg_password2']); //Sterge tagurile de html

	$date = date("Y-m-d"); //Data curenta

	$secret = "6LckUBQmAAAAAHa8EQNZ2pN4VWy6znyy19t2kEM2";
	$response = $_POST['g-recaptcha-response'];
	$remoteip = $_SERVER['REMOTE_ADDR'];
	$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
	$data = file_get_contents($url);
	$row = json_decode($data, true);

	if($em == $em2) {
		//Check if email is in valid format 
		if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

			//Check if email already exists 
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

			//Count the number of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows > 0) {
				array_push($error_array, "Email deja utilizat<br>");
			}

		}
		else {
			array_push($error_array, "Format de email este invalid<br>");
		}
	}
	else {
		array_push($error_array, "Emailurile nu se potrivesc<br>");
	}

	if(strlen($fname) > 25 || strlen($fname) < 2) {
		array_push($error_array, "Numele trebuie sa fie intre 2 si 25 de caractere<br>");
	}

	if(strlen($lname) > 25 || strlen($lname) < 2) {
		array_push($error_array,  "Prenumele trebuie sa fie intre 2 si 25 de caractere<br>");
	}

	if($password != $password2) {
		array_push($error_array,  "Parolele nu se potrivesc<br>");
	}
	else {
		if(preg_match('/[^A-Za-z0-9]/', $password)) {
			array_push($error_array, "Parola poate să conțină doar următoarele caractere: A-Za-z0-9 <br>");
		}
	}

	if(((strlen($password) > 30) || (strlen($password) < 5))) {
		array_push($error_array, "Parola trebuie sa conțină între 2 si 25 de caractere<br>");
	}

	if(empty($error_array) && ($row['success'] == "true")) {
		$password = md5($password); //Encryptarea parolei îninte de a ajunge in baza de date

		//Generarea usernameului prin conectarea numelui si al prenumelui prin _
		$username = strtolower($fname . "_" . $lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

		$i = 0; 
		//Daca usernameul deja exista, adaugă cifre la finalul acestuia
		while(mysqli_num_rows($check_username_query) != 0) {
			$i++; //Add 1 to i
			$username = $username . "_" . $i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
		}

		//Poza de profil
		$rand = rand(1, 2); //Numere la întamplare

		if($rand == 1)
			$profile_pic = "../defaults/head_deep_blue.png";
		else if($rand == 2)
			$profile_pic = "../defaults/head_emerald.png";


		$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',', 'no', '', '', '', '$group', '$phone', '$grad_year')");
		array_push($error_array, "<span style='color: #14C800;'>Contul a fost creat! Mergi la pagia de conectare!</span><br>");

		//Sterge variabilele
		$_SESSION['reg_fname'] = "";
		$_SESSION['reg_lname'] = "";
		$_SESSION['reg_email'] = "";
		$_SESSION['reg_email2'] = "";
		$_SESSION['reg_phone'] = "";
		$_SESSION['reg_group'] = "";
		$_SESSION['reg_grad_year'] = "";
	}
}
?>
