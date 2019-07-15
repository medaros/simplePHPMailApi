<?php
// header("Access-Control-Allow-Origin: http://localhost/");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// les parametres autorises
$required = array("firstName", "lastName", "phone", "email", "message");

if(isset($_POST)) {
    // pour savoir si les champs valides
    $valid = array();

    // pour chaque parametre
    // var_dump($_POST);
    foreach ($required as $key => $value) {
        
        // le key de chaque parametre
        $postKey = $required[$key]; 
        // si le parametre exisite dans $_POST
        if(isset($_POST[$required[$key]])) {
            // le champ aka parametre de post
            $post = $_POST[$required[$key]];
            // securite
            $post = htmlspecialchars($post);
            // verifications
            if($postKey == "firstName" && strlen($post) < 20 && strlen($post) > 2) {
                $valid["firstName"] = "valid";
            }
            elseif($postKey == "lastName" && strlen($post) < 20 && strlen($post) > 2) {
                $valid["lastName"] = "valid";
            }
            elseif($postKey == "message" && strlen($post) > 50 && strlen($post) < 500) {
                $valid["message"] = "valid";   
            }
            elseif($postKey == "phone" && strlen($post) == 10 && is_numeric($post)) {
                $valid["phone"] = "valid";
            }
            elseif($postKey == "email" && strlen($post) > 10 && strlen($post) < 50 && preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $post)) {
                $valid["email"] = "valid"; 
            }
            else {
                // si ca matche pas avec les patterns de chaque if
                // if(!array_keys($valid, $postKey))
                    $valid[$postKey] = "notvalid";
            }
        } else {
            // si le parametre n'est pas initialise
            $valid[$postKey] = "notvalid";
        }
    }

    // var_dump($valid);

    if(!in_array("notvalid", $valid)) {
        
        
        $message = $_POST['message'] . "<br>" . $_POST['email'];
        if(isset($_POST['phone'])) {
            $message .= "<br>" . $_POST['phone'];
        }
        // if(mail("arosmed3@gmail.com", "Declicnutrition : email de $_POST[firstName] $_POST[lastName]", $message))
        //     echo json_encode(array("status" => "sent"));
        // else {
        //     echo json_encode(array("status" => "error"));
        // }
        
        echo json_encode(array("status" => "sent", "email" => strval($message)));

    } else {
        echo json_encode(array("status" => "notvalid"));        
    }
} else {
    echo json_encode(array("status" => "notvalid"));
}

// echo json_encode(array("res" => strval(var_dump($_POST))));