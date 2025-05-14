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
    $surface = $_POST['surface'] ?? '';
    $buildingType = $_POST['building-type'] ?? '';
    $buildingTypeOther = $_POST['building-type-other'] ?? '';
    if ($buildingType === 'Autre' && !empty($buildingTypeOther)) {
        $buildingType = 'Autre : ' . $buildingTypeOther;
    }
    $message = $_POST['message'] ?? '';
    $pente = $_POST['pente'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $accessibilite = $_POST['accessibilite'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tceolin1710@gmail.com';
        $mail->Password = 'htuf gtvi utdi qykk'; // changer par sas.dallies
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Debug complet pour voir les erreurs SMTP
        $mail->SMTPDebug = 0; // Désactive l'affichage debug pour permettre la redirection
        $mail->Debugoutput = 'html';

        // Expéditeur = formulaire, Destinataire = tceolin1710@gmail.com
        $mail->setFrom($email, $name);
        $mail->addAddress('sas.dallies@gmail.com', 'SAS Dallies'); // a changer l'adresse email de destination par sas.dallies@gmail.com
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
            <p><strong>Pente :</strong> $pente %</p>
            <p><strong>Adresse exacte :</strong> $adresse</p>
            <p><strong>Surface :</strong> $surface m²</p>
            <p><strong>Type de bâtiment :</strong> $buildingType</p>
            <p><strong>Accessibilité :</strong> $accessibilite</p>
            <p><strong>Description :</strong><br>$message</p>
        ";
        $mail->AltBody = "Nom : $name\nEmail : $email\nTéléphone : $phone\nPente : $pente\nAdresse : $adresse\nSurface : $surface\nType de bâtiment : $buildingType\nAccessibilité : $accessibilite\nMessage : $message";

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
