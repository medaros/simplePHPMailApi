<?php
// <!> if you want to restrict the use of this api for your website only
//     change (*) by the address of you website (https://yourwebsite.com) in ligne 7, like :
//     header("Access-Control-Allow-Origin: https://yourwebsite.com");
// <!> 

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// les parametres requis autorisés - required and authorized parameteres
$required = array("firstName", "lastName", "phone", "email", "message");

if(isset($_POST)) {
    // pour savoir si les champs valides - array that checks if the inputs are valid
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
                // si ca matche pas avec les patterns de chaque if - if the current input does not match with it corresponding regex
                // if(!array_keys($valid, $postKey))
                    $valid[$postKey] = "notvalid";
            }
        } else {
            // si le parametre n'est pas initialise - if $key not found
            $valid[$postKey] = "notvalid";
        }
    }

    // var_dump($valid);

    if(!in_array("notvalid", $valid)) {
        
        // mail body =
        //  -------------
        //  |  textarea |
        //  |           |
        //  |  email    |
        //  |  phone    |
        //  -------------

        $message = $_POST['message'] . "<br>" . $_POST['email'];

        if(isset($_POST['phone'])) {
            $message .= "<br>" . $_POST['phone'];
        }

        // replace mywebsite with the name of your website and contact@yourwebsite.fr with your server email address
        $headers  = "From: mywebiste < contact@yourwebsite.fr >\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "X-Priority: 3\n";
        $headers .= "Return-Path: contact@yourwebsite.fr\n"; // Return path for errors
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\n";

        // email title
        if(mail("arosmed3@gmail.com", "mywebiste : email from $_POST[firstName] $_POST[lastName]", $message, $headers)) {
            echo json_encode(array("status" => "sent"));
        }
        else {
            echo json_encode(array("status" => "error"));
        }
        
    } else {
        echo json_encode(array("status" => "notvalid"));        
    }
} else {
    echo json_encode(array("status" => "notvalid"));
}