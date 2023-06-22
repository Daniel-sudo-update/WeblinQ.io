<?php
require '../config/config.php';
include("../includes/user.php");
include("../includes/Post.php");
include("../includes/Notification.php");

if (isset($_POST['post_body'])) {
    $post_body = $_POST['post_body'];

    $apiKey = getenv('39e9601b9e5e435c91af492bfba7a272'); // cheia API a resursei Content Moderator
    $endpoint = "https://weblinqmoderator.cognitiveservices.azure.com/contentmoderator/moderate/v1.0/ProcessText/Screen?classify=True"; // punctul de acces al resursei Content Moderator

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: text/plain",
        "Ocp-Apim-Subscription-Key: $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $result = json_decode($response, true);

        if ($result["Classification"]["ReviewRecommended"] == true) {
            http_response_code(400);
            echo "Postarea conține conținut nepotrivit.";
            exit();
        }
    }

    $post = new Post($con, $_POST['user_from']);
    $post->submitPost($post_body, $_POST['user_to']);
}
?>
