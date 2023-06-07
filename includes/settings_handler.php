<?php
if (isset($_POST['update_details'])) {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $stmt = $con->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $matched_user = $row['username'];

    if ($matched_user == "" || $matched_user == $userLoggedIn) {
        $message = "Detalii actualizate cu succes!<br><br>";

        $stmt = $con->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE username=?");
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $userLoggedIn);
        $stmt->execute();
    } else {
        $message = "Email-ul este deja utilizat!<br><br>";
    }
} else {
    $message = "";
}
################################################################## despre
$about_message = "";

if(isset($_POST['update_about'])) {
    if(isset($_POST['about'])) {
        $about = $_POST['about'];

        $update_query = $con->prepare("UPDATE users SET about=? WHERE username=?");
        $update_query->bind_param("ss", $about, $userLoggedIn);

        if($update_query->execute()){
            $about_message = "Despre actualizat cu succes!";
        } else {
            $about_message = "Error: " . $update_query->error;
        }
    } else {
        $about_message = "Despre necompletat!";
    }
    
    echo $about_message;
}

################################################################## grupa
$grupa_message = "";

if(isset($_POST['update_grupa'])) {
    if(isset($_POST['grupa'])) {
        $grupa = $_POST['grupa'];

        $update_query = $con->prepare("UPDATE users SET grupa=? WHERE username=?");
        $update_query->bind_param("ss", $grupa, $userLoggedIn);

        if($update_query->execute()){
            $grupa_message = "Grupa actualizat cu succes!";
        } else {
            $grupa_message = "Error: " . $update_query->error;
        }
    } else {
        $grupa_message = "Grupa necompletat!";
    }
    
    echo $grupa_message;
}

################################################################## generatia
$generatia_message = "";

if(isset($_POST['update_generatia'])) {
    if(isset($_POST['generatia'])) {
        $generatia = $_POST['generatia'];

        $update_query = $con->prepare("UPDATE users SET generatia=? WHERE username=?");
        $update_query->bind_param("ss", $generatia, $userLoggedIn);

        if($update_query->execute()){
            $generatia_message = "Generatia actualizat cu succes!";
        } else {
            $generatia_message = "Error: " . $update_query->error;
        }
    } else {
        $generatia_message = "Generatia necompletat!";
    }
    
    echo $generatia_message;
}

################################################################## studii
$studies_message = "";

if(isset($_POST['update_studies'])) {
    $studies = implode(",", $_POST['studies']);

    $update_query = $con->prepare("UPDATE users SET studii=? WHERE username=?");
    $update_query->bind_param("ss", $studies, $userLoggedIn);

    if($update_query->execute()){
        $studies_message = "Studii actualizate cu succes!";
    } else {
        $studies_message = "Eroare: " . $update_query->error;
    }

    echo $studies_message;
}



######################################################################### Profesori
$profesori_message = "";

if(isset($_POST['update_profesorii'])) {
    $profesori = implode(",", $_POST['profesori']);

    $update_query = $con->prepare("UPDATE users SET profesori=? WHERE username=?");
    $update_query->bind_param("ss", $profesori, $userLoggedIn);

    if($update_query->execute()){
        $profesori_message = "Profesori actualizat cu succes!";
    } else {
        $profesori_message = "Error: " . $update_query->error;
    }

    echo $profesori_message;
}




############################################################################ JOB
if(isset($_POST['update_detailss'])) {
    $job = $_POST['job'];

    $update_query = $con->prepare("UPDATE users SET job=? WHERE username=?");
    $update_query->bind_param("ss", $job, $userLoggedIn);

    if($update_query->execute()){
        $job_message = "Job actualizat cu succes!";
    } else {
        $job_message = "Error: " . $update_query->error;
    }
}

############################################################################ PASSWORD

if (isset($_POST['update_password'])) {

    $old_password = strip_tags($_POST['old_password']);
    $new_password_1 = strip_tags($_POST['new_password_1']);
    $new_password_2 = strip_tags($_POST['new_password_2']);

    $stmt = $con->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s", $userLoggedIn);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $db_password = $row['password'];

    if (md5($old_password) == $db_password) {

        if ($new_password_1 == $new_password_2) {

            if (strlen($new_password_1) <= 4) {
                $password_message = "Parola trebuie sa conțină minim 4 caractere<br><br>";
            } else {
                $new_password_md5 = md5($new_password_1);
                $stmt = $con->prepare("UPDATE users SET password=? WHERE username=?");
                $stmt->bind_param("ss", $new_password_md5, $userLoggedIn);
                $stmt->execute();
                $password_message = "Parola a fost schimbată!<br><br>";
            }

        } else {
            $password_message = "Parolele nu se potrivesc!<br><br>";
        }

    } else {
        $password_message = "Parola veche este incorectă! <br><br>";
    }

} else {
    $password_message = "";
}

if (isset($_POST['close_account'])) {
    header("Location: ../templates/close_account.php");
}



?>
