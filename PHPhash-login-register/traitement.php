<?php

session_start();

if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "register":
            // si le formulaire est soumis 
            if($_POST['submit']) {
                // connexion a la base de données
                $pdo = new \PDO("mysql:host=localhost;dbname=php_hash_yofer;charset=utf8", "root", "");
                
                // filtrer la saisie des champs du formulaire d'inscription
                $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
                $pass1 = filter_input(INPUT_POST, "pass1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pass2 = filter_input(INPUT_POST, "pass2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                if($pseudo && $email && $pass1 && $pass2) {
                    $requete = $pdo->prepare("
                    SELECT *
                    FROM user

                    WHERE email = :email
                    ");
                    $requete->execute(["email" => $email]);
                    $user = $requete->fetch();
                    // si l'utilisateur existe
                    if($user) {
                        header("Location: register.php"); exit;
                    } else {
                        // var_dump("utilisateur inexistant");die;
                        // insertion de l'utilisateur en BDD
                        if($pass1 == $pass2 && strlen($pass1) >= 5) {
                            $insertUser = $pdo->prepare("
                                INSERT INTO user (pseudo, email, password)
                                VALUES (:pseudo, :email, :password)
                            ");
                            $insertUser->execute([
                                "pseudo" => $pseudo,
                                "email" => $email,
                                "password" => password_hash($pass1, PASSWORD_DEFAULT)
                            ]);
                            header("Location: login.php"); exit;
                        } else {
                            // mesage "les mot de passe ne sont pas identique ou mot de passe trop court
                        }
                    }
                } else {
                    //probleme de saisie dans les champs du formulaire
                }
            }  
            //par défaut j'affiche le formulaire d'inscription
            header("Location: register.php"); exit;
        break;
        
        case "login":
            
            if($_POST['submit']) {
                // connexion a la base de données
                $pdo = new \PDO("mysql:host=localhost;dbname=php_hash_yofer;charset=utf8", "root", "");

                // filtrer les champs (faille XSS)
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // si les filtres sont valides
                if($email && $password) {
                    $requete = $pdo->prepare("
                        SELECT * 
                        FROM user

                        WHERE email = :email
                    ");
                    $requete->execute(["email" => $email]);
                    $user = $requete->fetch();
                    //var_dump($user);die;
                    // est ce que l'utilisateur existe
                    if($user) { //s'il existe (true)
                        $hash = $user["password"];
                        if(password_verify($password, $hash)) { // comparer password normal au password hashé
                            $_SESSION["user"] = $user;
                            header("Location: home.php"); exit;
                        } else {
                            header("Location: login.php"); exit;
                            //message utilisateur inconnu ou mot de passe incorrecte
                        }
                    } else {
                        //message utilisateur inconnu ou mot de passe incorrecte
                        header("Location: login.php"); exit; 
                    }
                }
            }
            header("Location: login.php"); exit;
        break;

        case "profile":
            header("Location: profile.php");
        break;

        case "logout":
            unset($_SESSION["user"]);
            header("Location: home.php"); exit;
        break;
        }

}