<?php
// Inclure les fichiers PHPMailer
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'tceolin1710@gmail.com'; // Remplacez par votre email Gmail
        $mail->Password = 'hghg qyho kijs lojp 7'; // Remplace par ton mot de passe d'application généré
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Sécurisation TLS
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('votre_email@gmail.com', 'SAS Dallies'); // Expéditeur
        $mail->addAddress('sas.dallies@gmail.com', 'SAS Dallies'); // Destinataire principal
        $mail->addReplyTo($email, $name); // Répondre à l'expéditeur

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle demande de devis';
        $mail->Body = "
            <h1>Nouvelle demande de devis</h1>
            <p><strong>Nom :</strong> $name</p>
            <p><strong>Email :</strong> $email</p>
            <p><strong>Téléphone :</strong> $phone</p>
            <p><strong>Message :</strong> $message</p>
        ";
        $mail->AltBody = "Nom : $name\nEmail : $email\nTéléphone : $phone\nMessage : $message";

        // Envoyer l'email
        $mail->send();
        echo 'Votre demande a été envoyée avec succès.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }
} else {
    echo 'Méthode non autorisée.';
}
?>
