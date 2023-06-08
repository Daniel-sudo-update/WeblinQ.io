<?php 
include("../includes/header3.php");
include("../includes/settings_handler.php");
?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <link rel="stylesheet" href="../templates/background.css">
</head>
<body>
<div class="p-4 sm:ml-32 ">
  <div class="mt-14">    
    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">Account settings</h2>
       <!-- actualizare poza de profil -->
       <?php
            if (isset($_SESSION['username'])) {
                if (isset($_POST['submit'])) {
                    $image = $_FILES['profile_pic']['name'];
                    $target = "../defaults/uploads" . basename($image);

                    // Încărcarea imaginii în folderul "uploads"
                    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                        $msg = "Image uploaded successfully";

                        // Actualizarea bazei de date cu noua poză de profil
                        $query = "UPDATE users SET profile_pic = '$target' WHERE username = '{$_SESSION['username']}'";
                        $result = mysqli_query($con, $query);

                        if ($result) {
                            header("Location: ../settings/settings.php"); // Redirecționare către pagina de profil
                        } else {
                            echo "Error updating profile picture: " . mysqli_error($con);
                        }
                    } else {
                        $msg = "Failed to upload image";
                    }
                }
            } else {
                header("Location: ../index.php"); // Redirecționare către pagina de login dacă utilizatorul nu este autentificat
            }
            ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="size" value="1000000">
                <div class="text-gray-700 dark:text-gray-200">
                    <?php
                    echo "<img src='" . $user['profile_pic'] ."' id='small_profile_pics'>";
                    ?>
                    <br>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    <input type="file" name="profile_pic">
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    <button type="submit" name="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează poza</button>
                </div>
            </form>
            <!-- final actualizare poza de profil -->
<!-- cod pentru updatare date -->
            <?php
                $stmt = $con->prepare("SELECT first_name, last_name, email, job FROM users WHERE username=?");
                $stmt->bind_param("s", $userLoggedIn);
                $stmt->execute();
                $result = $stmt->get_result();
                $job_message = "";

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $email = $row['email'];
                    $job = $row['job'];
                } else {
                    // Handle the case when no user is found
                    echo "No user found with username: " . $userLoggedIn;
                }

                
            ?>

<!-- cod pentru updatare date -->



<!-- schimbare date job -->
        <form action="settings.php" method="POST">
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" ></label>
                    <input type="text" name="job" value="<?php echo $job; ?>" autocomplete="off" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>
            </div>
                <p><?php echo $job_message; ?></p>
                <div class="flex justify-end mt-6">
                    <button type="submit" name="update_detailss" id="save_detailss" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
                </div>
        </form>

<!-- schimbare date -->
        <form action="settings.php" method="POST">
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                
                <div>
                    <label class="text-gray-700 dark:text-gray-200" >First Name</label>
                    <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" autocomplete="off" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" >Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" autocomplete="off" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" >Email Address</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" autocomplete="off" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>
          </div>
              <?php echo $message; ?>
            <div class="flex justify-end mt-6">
                <button type="submit" name="update_details" id="save_detail" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
            </div>
        </form>
<!-- schimbare parola -->
        <form action="settings.php" method="POST">
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200">Old Password</label>
                    <input  type="password" name="old_password" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200">New Password</label>
                    <input  type="password" name="new_password_1" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" >New Password Confirmation</label>
                    <input type="password" name="new_password_2" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>
            </div>

            <?php echo $password_message; ?>

            <div class="flex justify-end mt-6">
                <button type="submit" name="update_password" id="save_details" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
            </div>
        </form>
        <!-- inchidere cont -->
        <form action="settings.php" method="POST">
            <label class="text-gray-700 dark:text-gray-200">Inchide contul</label>
                  
            <div class="flex justify-end mt-6">
              <button type="submit" name="close_account" id="close_account" value="close account" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
               Inchide contul
            </div>
        </form>
    </section>

    <!-- alegere studii -->
    <section class="text-white max-w-4xl mt-4 p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <!-- actualizare about -->
            <form action="settings.php" method="POST">
                <div>
                    <label for="about" class="text-gray-700 dark:text-gray-200">Despre mine</label>
                    <input type="text" name="about" id="about" autocomplete="off" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>
                <p><?php echo $about_message; ?></p>
                <div class="flex justify-end mt-6">
                    <button type="submit" name="update_about" id="save_about" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
                </div>
            </form>

            <!-- actualizare grupa -->

            <form action="settings.php" method="POST">
                <div>
                    <label for="grupa" class="text-gray-700 dark:text-gray-200">Grupa:</label>
                    <input type="text" name="grupa" id="grupa" autocomplete="off" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>
                <p><?php echo $grupa_message; ?></p>
                <div class="flex justify-end mt-6">
                    <button type="submit" name="update_grupa" id="save_grupa" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
                </div>
            </form>

            <!-- actualizare generatie -->

            <form action="settings.php" method="POST">
                <div>
                    <label for="generatia" class="text-gray-700 dark:text-gray-200">Generația:</label>
                    <input type="text" name="generatia" id="generatia" autocomplete="off" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>
                <p><?php echo $generatia_message; ?></p>
                <div class="flex justify-end mt-6">
                    <button type="submit" name="update_generatia" id="save_generatia" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
                </div>
            </form>

            <!--actualizare studii  -->
            <form action="settings.php" method="POST">
                <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                    <div>
                        <label class="text-gray-700 dark:text-gray-200">Studii</label>
                        <div class="mt-2">
                            <div>
                                <label>
                                    <input type="checkbox" name="studies[]" value="Licenta">
                                    Licentă
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" name="studies[]" value="Master">
                                    Master
                                </label>
                            </div>
                            <div>
                                <label>
                                    <input type="checkbox" name="studies[]" value="Doctorat">
                                    Doctorat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <p><?php echo $studies_message; ?></p>
                <div class="flex justify-end mt-6">
                    <button type="submit" name="update_studies" id="save_studies" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
                </div>
            </form>


        <!-- actualizare profesori -->
        <form action="settings.php" method="POST">
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200">Profesori</label>
                    <div id="profesori">
                        
                        
                        <!-- Checkboxurile vor fi generate de script -->
                    </div>
                </div>
            </div>
            <p id=""><?php echo $profesori_message;?></p>
            <div class="flex justify-end mt-6">
                <button type="button" id="save_profesorii" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Actualizează</button>
            </div>
        </form>
    </section>
  </div>
</div>

<!-- script actualizare studii -->
<script>
$(document).ready(function() {
    $('#save_studies').on('click', function(){
        var studies = [];
        $("input[name='studies[]']:checked").each(function(){
            studies.push($(this).val());
        });

        $.ajax({
            url: "settings.php",
            type: "POST",
            data: {
                'update_studies': true,
                'studies': studies
            },
            success: function(data){
                $('#studies_message').text(data);
            },
            error: function(error){
                console.log(error);
            }
        });
    });
});
</script>

<!-- script pentru crearea automata a checkboxurilor pentru profesori -->
<script>
        let professors = [  "ACSINTE Alexandru",
                            "ABABEI Cătălina",
                            "ABABEI Radu",
                            "ALEXA Irina-Claudia",
                            "ALEXE Cristina-Ioana",
                            "ALEXE Dan-Iulian",
                            "AMĂLĂNCEI Brîndușa-Mariana",
                            "ANDRIOAI Gabriela",
                            "ANDRIOAIA Dragoș-Alexandru",
                            "ANGHEL Mihaela",
                            "ARDELEANU Elena Roxana",
                            "ARUŞ Vasilica Alisa",
                            "BALINT Gheorghe",
                            "BALINT Nela-Tatiana",
                            "BIBIRE Luminiţa",
                            "BOGDAN Antohe",
                            "BOGHIAN Ioana",
                            "BONTA Elena",
                            "BOTEZ Daniel",
                            "BRABIE Gheorghe",
                            "BREAHNĂ-PRAVĂȚ Ionela-Cristina",
                            "BUCUR Iulia-Andreea",
                            "BÂRSAN Narcis",
                            "BĂLAN Veronica-Loredana",
                            "BĂLINIȘTEANU-FURDU Cătălina-Dumitrița",
                            "BĂLĂIȚĂ Raluca",
                            "CEOCEA Costel",
                            "CHIRIȚĂ Bogdan-Alexandru",
                            "CHIŢIMUŞ Alexandra-Dana",
                            "CIOBANU Elena",
                            "CIOCAN Dana-Maria",
                            "CIOCAN Vasile-Cătălin",
                            "CIUBOTARIU Vlad-Andrei",
                            "CIUNTEA Mihai-Lucian",
                            "COJOCARIU Venera-Mihaela",
                            "COTÎRLEȚ Adrian-Valentin",
                            "COTÎRLEȚ Paul-Claudiu",
                            "CRISTEA Ion",
                            "CRISTUȚĂ Mihaela-Alina",
                            "CRIȘAN Gloria-Cerasela",
                            "CULEA Cătălina-Mihaela",
                            "CULEA George",
                            "CULEA Mihaela",
                            "CÎRTIȚĂ-BUZOIANU Cristina",
                            "CĂLIMAN Radu",
                            "DANU Marcela-Cornelia",
                            "DEJU Mihai",
                            "DOBRECI Daniel-Lucian",
                            "DOBRESCU Tatiana",
                            "DRAGOMIRESCU Simona-Elena",
                            "DROB Cătălin",
                            "DRUGĂ Luminița",
                            "DRĂGOI Cristian-Corneliu",
                            "FERARU Andreea",
                            "FLORIA Florinela-Gianina",
                            "FURDU Iulian-Marius",
                            "FÎNARU Adriana-Luminiţa",
                            "GALERU Ovidiu",
                            "GALIȚA Raluca",
                            "GAVRILĂ Lucian-Gheorghe",
                            "GEORGESCU Ana-Maria",
                            "GHENADI Adrian-Stelian",
                            "GRAPĂ Florin",
                            "GRIGORAȘ Cosmin-Constantin",
                            "GRIGORAȘ Cristina-Gabriela",
                            "GRIGORE Roxana-Margareta",
                            "GROSU Luminiţa",
                            "GURĂU Milian",
                            "GÎRȚU Manuela",
                            "HARJA Eugenia",
                            "HAZI Aneta",
                            "HAZI Gheorghe",
                            "HERGHELEGIU Eugen",
                            "HORUBEȚ Mircea",
                            "HRIBAN Mihaela",
                            "IFRIM Irina-Loredana",
                            "IRIMIA Oana",
                            "JICU Adrian-Gelu",
                            "LIVINȚI Petru",
                            "LUNGU Otilia",
                            "LUPU Gabriel-Stanică",
                            "Lect. univ. dr. Mihaela ENACHI",
                            "MAFTEI Diana-Elena",
                            "MAREȘ Gabriel",
                            "MASTACAN Simina",
                            "MILON Alexandra-Gabriela",
                            "MIRONESCU Roxana",
                            "MOCANU Marcelina-Cristina",
                            "MORĂRAȘU Nadia-Nicoleta",
                            "MOŞNEGUŢU Emilian-Florin",
                            "MUNTIANU Gabriela",
                            "MÂRZA-DĂNILĂ Dănuţ-Nicu",
                            "MÂȚĂ Liliana",
                            "NECHITA Elena",
                            "NEDEFF Florin Marian",
                            "NEDEFF Valentin",
                            "NICHIFOR Bogdan-Vasile",
                            "NICUȚĂ Daniela",
                            "NIMINEŢ Liviana-Andreea",
                            "NIMINEȚ Valer",
                            "NISTOR Ileana-Denisa",
                            "OCHIANĂ Gabriela",
                            "OCHIANĂ Nicolae",
                            "OLARIU Ioana",
                            "OLARU Ionel",
                            "PANAINTE-LEHĂDUȘ Mirela",
                            "PATRICIU Oana-Irina",
                            "PAVEL Silviu-Ioan",
                            "PLATON Nicoleta",
                            "POPA Carmen-Nicoleta",
                            "POPA Dan",
                            "POPA Elena-Violeta",
                            "POPA Sorin-Eugen",
                            "POPESCU Carmen-Violeta",
                            "POPOVICI Nicoleta",
                            "POSTOLICĂ Vasile",
                            "PRICOPE Ferdinant",
                            "PRIHOANCĂ Diana-Magdalena",
                            "PRISECARU Maria",
                            "PRUTEANU Eusebiu",
                            "PUIU Petru-Gabriel",
                            "PUIU Vasile",
                            "PUIU-BERIZINTU Mihai",
                            "PĂTRUȚ Monica-Paulina",
                            "RADU Maria-Crina",
                            "RAVEICA Gabriela",
                            "RAVEICA Ionel-Crinel",
                            "RAȚI Ioan-Viorel",
                            "RAȚĂ Bogdan-Constantin",
                            "RAȚĂ Gloria",
                            "RAȚĂ Marinela",
                            "ROBU Viorel",
                            "ROMEDEA Adriana-Gertruda",
                            "ROTAR Dan",
                            "ROTILĂ Aristiţa",
                            "ROŞU Ana-Maria",
                            "ROȘU Cristina",
                            "RUSU Dragoş-Ioan",
                            "RUSU Lăcrămioara",
                            "RĂDUCANU Dumitra",
                            "SANDOVICI Anișoara",
                            "SAVA Mihai-Adrian",
                            "SAVIN Petronela",
                            "SCHNAKOVSZKY Carol",
                            "SIMION Andrei-Ionuţ",
                            "SLICARU Adina-Camelia",
                            "SOLOMON Daniela-Cristina",
                            "SPIRIDON Vasile",
                            "STAN Gheorghe",
                            "STOICA Cristina-Elena",
                            "STOICA Ionuț-Viorel",
                            "STRUGARIU Maricela",
                            "STÂNGACIU Oana-Ancuţa",
                            "SUCEVEANU Elena-Mirela",
                            "SUCIU Andreia-Irina",
                            "TALMACIU Mihai",
                            "TOMOZEI Claudia-Manuela",
                            "TOMOZEI Cosmin Ion",
                            "TOPLICEANU Liliana",
                            "TURCU Ovidiu-Leonard",
                            "TÂMPU Nicolae-Cătălin",
                            "TÂMPU Raluca-Ioana",
                            "TÎRNĂUCEANU Mariana",
                            "URECHE Camelia",
                            "URECHE Dorel",
                            "VERNICA Sorin-Gabriel",
                            "VOICU Roxana-Elena",
                            "VOROVENCI Carmina-Mihaela",
                            "VULPE Ana-Maria",
                            "ZAIŢ Luminiţa-Iulia",
                            "ZICHIL Valentin",
                            "ŞALGĂU Silviu",
                            "ŞUFARU Constantin",
                            "ŢIMIRAŞ Laura-Cătălina",
                            "ȘTEFĂNESCU Ioana-Adriana"
                        ];


        let container = document.getElementById('profesori');
    
        for (let i = 2; i < professors.length; i++) {
            let checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'profesori[]';
            checkbox.id = 'profesor' + (i + 1);
            checkbox.value = professors[i];

            let label = document.createElement('label');
            label.htmlFor = 'profesor' + (i + 1);
            label.appendChild(document.createTextNode(professors[i]));

            let br = document.createElement('br');

            container.appendChild(checkbox);
            container.appendChild(label);
            container.appendChild(br);
        }
    </script>
<!-- script pentru actualizarea profesorilor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#save_profesorii').on('click', function(){
        var profesori = [];
        $("#profesori input[type='checkbox']:checked").each(function(){
            profesori.push($(this).val());
        });

        $.ajax({
            url: "settings.php",
            type: "POST",
            data: {
                'update_profesorii': true,
                'profesori': profesori
            },
            success: function(data){
                $('#profesori_message').text(data);
            },
            error: function(error){
                console.log(error);
            }
        });
    });
});
</script>
</body>
