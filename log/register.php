<?php  
require("../config/config.php");
require("../includes/register_handler.php");
?>
<html>
<head>
	<title>Bun venit!</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<link href="../src/output.css" rel="stylesheet"> 
</head>
<body text="#ffffff">
<section class="bg-white dark:bg-gray-900">
    <div class="flex justify-center min-h-screen">
        <div class="hidden bg-cover lg:block lg:w-3/5" style="background-image: url('../Logo.jpg')">
        </div>

        <div class="flex items-center w-full max-w-3xl p-8 mx-auto lg:px-12 lg:w-3/5">
            <div class="w-full">
                <h1 class="text-2xl font-semibold tracking-wider text-gray-800 capitalize dark:text-white">
                Obțineți contul gratuit acum.
                </h1>

                <p class="mt-4 text-gray-500 dark:text-gray-400">
                Să vă setăm totul, astfel încât să vă puteți verifica contul personal și să începeți să vă configurați profilul.
                </p>
               <form action="register.php" method="POST" class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Nume</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="text" name="reg_fname" placeholder="Nume" value="<?php 
							if(isset($_SESSION['reg_fname'])) {
								echo $_SESSION['reg_fname'];
							} 
							?>" required>
							<br>
						<?php if(in_array("Prenumele tău trebuie să aibă între 2 și 25 de caractere<br>", $error_array)) echo "Prenumele tău trebuie să aibă între 2 și 25 de caractere<br>"; ?>
		
                    </div>

                    <div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Prenume</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="text" name="reg_lname" placeholder="Prenume" value="<?php 
							if(isset($_SESSION['reg_lname'])) {
								echo $_SESSION['reg_lname'];
							} 
							?>" required>
							<br>
						<?php if(in_array("Numele dvs. de familie trebuie să aibă între 2 și 25 de caractere<br>", $error_array)) echo "Numele dvs. de familie trebuie să aibă între 2 și 25 de caractere<br>"; ?>

                    </div>

                    

                    <div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Email</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="email" name="reg_email" placeholder="Email" value="<?php 
							if(isset($_SESSION['reg_email'])) {
								echo $_SESSION['reg_email'];
							} 
							?>" required>
							<br>
                    </div>

					<div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Confirmară email</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="email" name="reg_email2" placeholder="Email" value="<?php 
							if(isset($_SESSION['reg_email2'])) {
								echo $_SESSION['reg_email2'];
							} 
							?>" required>
							<br>
                    </div>
					<!-- condiții pentru email -->
										<?php if(in_array("E-mail deja folosit<br>", $error_array)) echo "E-mail deja folosit<br>"; 
							else if(in_array("Format de email invalid<br>", $error_array)) echo "Format de email invalid<br>";
							else if(in_array("E-mailurile nu se potrivesc<br>", $error_array)) echo "E-mailurile nu se potrivesc<br>"; ?>


                    <div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Parola</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="password" name="reg_password" placeholder="Parola" required>
                    </div>

					<div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Confirma parola</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="password" name="reg_password2" placeholder="Parola" required>
                    </div>

                  <!-- condiții pentru parola -->

						<?php if(in_array("Parolele nu se potrivesc<br>", $error_array)) echo "Parolele nu se potrivesc<br>"; 
				else if(in_array("Parola dvs. poate conține doar caractere sau numere în limba engleză<br>", $error_array)) echo "Parola dvs. poate conține doar caractere sau numere în limba engleză<br>";
				else if(in_array("Parola dvs. trebuie să aibă între 5 și 30 de caractere<br>", $error_array)) echo "Parola dvs. trebuie să aibă între 5 și 30 de caractere<br>"; ?>

                  
                    <div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Telefon</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="phone" name="reg_phone" placeholder="Numarul de telefon" value="<?php 
							if(isset($_SESSION['reg_phone'])) {
								echo $_SESSION['reg_phone'];
							} 
							?>" required>
							<br>
                    </div>
					<!-- condiții pentru telefon-->
										<?php if(in_array("Numar deja folosit!<br>", $error_array)) echo "Numar deja folosit!<br>"; 
							else if(in_array("Format numar de telefon invalid<br>", $error_array)) echo "Format numar de telefon invalid<br>";
                            
							 ?>

                    <div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Grupa</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="number" name="reg_group" placeholder="Numarul grupei: 1241" value="<?php 
							if(isset($_SESSION['reg_group'])) {
								echo $_SESSION['reg_group'];
							} 
							?>" required>
							<br>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Anul finalizarii studiilor</label>
                        <input class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-lg dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" 
						type="number" name="reg_grad_year" placeholder="2023 , 2024 , ..." value="<?php 
							if(isset($_SESSION['reg_grad_year'])) {
								echo $_SESSION['reg_grad_year'];
							} 
							?>" required>
							<br>
                    </div>

                            
                <div class="g-recaptcha" data-sitekey="6Lc4GXomAAAAAM1xAASeLuiXHdIlgJpBvSWBVkeW"></div>

                 <div>
				   <button type="submit" name="register_button" value="Register" 
                        class="flex items-center justify-between w-full px-6 py-3 text-sm tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-500 rounded-lg hover:bg-blue-400 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                        <span>Inscrie-te </span> 

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 rtl:-scale-x-100" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
		         </div>
		<br>
        <p class="mt-6 text-sm text-center text-gray-400">Aveti deja un cont? <a href="../index.php" class="text-blue-500 focus:outline-none focus:underline hover:underline">Conectare</a>.</p><br>
              
        <?php if(in_array("<br><span style='color: #14C800;'>Ești gata! Continuați și conectați-vă!</span><br>", $error_array)) echo "<span style='color: #14C800;'>Ești gata! Continuați și conectați-vă!</span><br>"; ?>

		
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <!-- nu modifica mai jos -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.js"></script>
   
</body>
</html>