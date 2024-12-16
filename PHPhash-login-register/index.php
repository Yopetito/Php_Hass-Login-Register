<?php 
session_start();

// algorithme de hachage faible: sha256, md5
// algorithme de hachage fort : argon2i, Bcrypt.

// Mot de passe haché != Empreinte numérique.
// empreinte numérique : Algorithme + cost + salt + mot de passe haché.

$password = "monMotdePasse1234";
$password2 = "monMotdePasse1234";

// algorithme de hachage FAIBLE

$md5 = hash('md5', $password);
$md5_2 = hash('md5', $password2);
echo $md5."<br>";
echo $md5_2."<br>";

$sha256 = hash('sha256', $password);
$sha256_2 = hash('sha256', $password2);
echo $sha256."<br>";
echo $sha256_2."<br>";

// algorithme de hachage FORT (bcrypt)
$hash = password_hash($password, PASSWORD_DEFAULT);
$hash2 = password_hash($password2, PASSWORD_DEFAULT);
echo $hash."<br>";
echo $hash2."<br>";

// saisie dans le formulaire de login

$saisie = "monMotdePasse1234";

$check = password_verify($saisie, $hash);
$user = "Yofer";

if(password_verify($saisie, $hash)) {
    $_SESSION['user'] = $user;
    echo $user." est connecté"; 
} else {
    echo "ca correspond pas! ";
}

// SecurityController

// POUR LE REGISTER:
// -on filtre les champs du formulaire

// -si les filtres sont valides, on vérifie que le mail n'existe pas déjà (sinon message d'erreur)

// -on vérifie que le pseudo n'existe pas non plus (sinon msg derreur)

// -on vériei que les 2 mot de passe du formulaire soient identiques

// -on ajoute l'utilisateur en base de données


// POUR LE LOGIN:
// -on filtre leschamps du formulaire

// -si les filtre spassent, on retoruve le password correspondant au mail entré dans le formulaire

// -si on le trouve, on recupere le hash de la base de données

// -on retrouve l'utilisateur correspondant

// -on vérifie le mot de passe password_verify

// -si on arrive a se connecte, on fait passer le user en session

// -si aucune des conditions ne passent (mauvais mot de passe, utilisateur inexistant, etc) -> message derreur