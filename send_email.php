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
    $mail->CharSet = 'UTF-8';

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sas.dallies@gmail.com'; 
        $mail->Password = 'oldm vhst tjue erma';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Debug complet pour voir les erreurs SMTP
        $mail->SMTPDebug = 0; // Désactive l'affichage debug pour permettre la redirection
        $mail->Debugoutput = 'html';

        // Expéditeur = formulaire, Destinataire = sas.dallies@gmail.com
        $mail->setFrom($email, $name);
        $mail->addAddress('sas.dallies@gmail.com', 'SAS Dallies');
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($email, $name);
        }

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle demande de devis';
        $mail->Body = '
        <div style="background:#f6f6f6;padding:30px 0;">
          <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:auto;background:#fff;border-radius:10px;box-shadow:0 2px 8px #eee;">
            <tr>
              <td style="padding:32px 32px 0 32px;text-align:center;">
                <!-- Logo ou nom -->
                <div style="margin-bottom:24px;">
                  <span style="font-size:28px;font-weight:bold;color:#e0a800;letter-spacing:2px;">SAS Dallies</span>
                </div>
                <h1 style="font-size:22px;color:#222;margin-bottom:12px;">Nouvelle demande de devis reçue</h1>
                <p style="font-size:16px;color:#444;margin-bottom:28px;">Merci pour intérêt.<br>Voici le récapitulatif de la demande :</p>
                <a href="mailto:' . htmlspecialchars($email) . '" style="display:inline-block;padding:12px 32px;background:#222;color:#fff;font-weight:bold;border-radius:6px;text-decoration:none;font-size:16px;margin-bottom:24px;">Répondre au client</a>
              </td>
            </tr>
            <tr>
              <td style="padding:0 32px 32px 32px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;color:#222;">
                  <tr>
                    <td style="padding:8px 0;width:160px;font-weight:bold;">Nom :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($name) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Email :</td>
                    <td style="padding:8px 0;"><a href="mailto:' . htmlspecialchars($email) . '" style="color:#e0a800;">' . htmlspecialchars($email) . '</a></td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Téléphone :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($phone) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Adresse :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($adresse) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Surface :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($surface) . ' m²</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Type de bâtiment :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($buildingType) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Pente :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($pente) . ' %</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Accessibilité :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($accessibilite) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;vertical-align:top;">Description :</td>
                    <td style="padding:8px 0;white-space:pre-line;">' . nl2br(htmlspecialchars($message)) . '</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="background:#f6f6f6;color:#888;font-size:13px;text-align:center;padding:16px 32px;border-radius:0 0 10px 10px;">
                <div style="margin-bottom:6px;">
                  <strong>Contact SAS Dallies</strong> &bull; 06 12 71 72 34 &bull; sas.dallies@gmail.com
                </div>
                <div style="font-size:12px;">Lieu-dit Largenté, 32450 Lartigue</div>
              </td>
            </tr>
          </table>
        </div>
        ';
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
