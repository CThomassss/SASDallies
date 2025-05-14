<?php
// Inclure les fichiers PHPMailer
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécurisation des données
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tceolin1710@gmail.com'; // Ton email Gmail
        $mail->Password   = 'hghgqyhokijslojp';       // Ton mot de passe d'application sans espaces
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Expéditeur / Destinataires
        $mail->setFrom('tceolin1710@gmail.com', 'SAS Dallies'); // doit correspondre à $mail->Username
        $mail->addAddress('sas.dallies@gmail.com', 'SAS Dallies');
        $mail->addReplyTo($email, $name);

        // Contenu de l’email
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle demande de devis';
        $mail->Body    = "
            <h1>Nouvelle demande de devis</h1>
            <p><strong>Nom :</strong> $name</p>
            <p><strong>Email :</strong> $email</p>
            <p><strong>Téléphone :</strong> $phone</p>
            <p><strong>Message :</strong> $message</p>
        ";
        $mail->AltBody = "Nom : $name\nEmail : $email\nTéléphone : $phone\nMessage : $message";

        // Envoi
        $mail->send();
        echo 'Votre demande a été envoyée avec succès.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }
} else {
    echo 'Méthode non autorisée.';
}
?>
