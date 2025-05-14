<?php
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des champs du formulaire
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $budget = $_POST['budget'] ?? '';
    $surface = $_POST['surface'] ?? '';
    $buildingType = $_POST['building-type'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tceolin1710@gmail.com';
        $mail->Password = 'htuf gtvi utdi qykk'; // Attention : pas d'espace à la fin
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Debug complet pour voir les erreurs SMTP
        $mail->SMTPDebug = 0; // Désactive l'affichage debug pour permettre la redirection
        $mail->Debugoutput = 'html';

        // Expéditeur = formulaire, Destinataire = tceolin1710@gmail.com
        $mail->setFrom($email, $name);
        $mail->addAddress('tceolin1710@gmail.com', 'SAS Dallies');
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($email, $name);
        }

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle demande de devis';
        $mail->Body = "
            <h2>Nouvelle demande de devis</h2>
            <p><strong>Nom :</strong> $name</p>
            <p><strong>Email :</strong> $email</p>
            <p><strong>Téléphone :</strong> $phone</p>
            <p><strong>Budget estimé :</strong> $budget €</p>
            <p><strong>Surface :</strong> $surface m²</p>
            <p><strong>Type de bâtiment :</strong> $buildingType</p>
            <p><strong>Description :</strong><br>$message</p>
        ";
        $mail->AltBody = "Nom : $name\nEmail : $email\nTéléphone : $phone\nBudget : $budget\nSurface : $surface\nType de bâtiment : $buildingType\nMessage : $message";

        // Envoi
        $mail->send();
        // Redirection vers index.html après envoi
        header('Location: index.html');
        exit;
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}<br>";
        echo "Exception : " . $e->getMessage();
    }
} else {
    echo 'Méthode non autorisée.';
}
?>
