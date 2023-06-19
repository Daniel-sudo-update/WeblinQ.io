<?php  
require '../config/config.php';
include("../includes/user.php");
include("../includes/Post.php");
include("../includes/Notification.php");

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


if(isset($_POST['post_body'])) {
    $post_body = $_POST['post_body'];

    // // Listează cuvintele pe care nu le permiți
    // $cuvinte_improprii = ["curva", "curvă", "muie", "muist", "muistă", "futu-i",
	// 					  "găoază", "găozar", "gaoaza", "gaozar", "muista", "poponar",
	// 					  "pulă", "pula", "pula bleaga", "pulă bleagă", "sloboz",
	// 					  "pupincurist", "căcănar", "cacanar", "puleasca", "puleașcă",
	// 					  "prost", "prostănac", "proastă", "curvar", "curist", "curista",
	// 					  "sugaci", "pupincurist", "labagiu", "labajiu",];
    
    // // Verifică dacă postarea conține cuvinte improprii
    // foreach($cuvinte_improprii as $cuvant) {
    //     if (mb_stripos($post_body, $cuvant) !== false) {
    //         // În cazul în care postarea conține un cuvânt nepotrivit, oprește procesarea și trimite un mesaj de eroare
    //         http_response_code(400);
    //         echo "Postarea conține conținut nepotrivit.";
    //         exit();
    //     }
    // }

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