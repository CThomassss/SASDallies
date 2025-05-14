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
        $mail->Password = 'hghg qyho kijs lojp 7'; // Remplacer par ton mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Pour debug
        $mail->SMTPDebug = 0; // 2 si tu veux déboguer en détail
        $mail->Debugoutput = 'html';

        // Expéditeur et destinataire
        $mail->setFrom('tceolin1710@gmail.com', 'SAS Dallies');
        $mail->addAddress('sas.dallies@gmail.com', 'SAS Dallies');
        $mail->addReplyTo($email, $name);

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
        echo 'Votre demande a été envoyée avec succès.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }
} else {
    echo 'Méthode non autorisée.';
}
?>
