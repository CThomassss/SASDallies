<?php
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des champs du formulaire
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $buildingType = trim($_POST['Building-type'] ?? '');
    $rdvDate = trim($_POST['rdv-date'] ?? '');
    $heure = trim($_POST['heure'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validation basique
    if (
        empty($name) ||
        empty($phone) ||
        empty($buildingType) ||
        empty($rdvDate) ||
        empty($heure) ||
        empty($email) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "Erreur : Tous les champs obligatoires doivent être remplis avec un email valide.";
        exit;
    }

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tceolin1710@gmail.com'; // Remplacez par le sasdallies
        $mail->Password = 'htuf gtvi utdi qykk'; // Remplacez par le mot de passe correct
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur = formulaire, Destinataire = SAS Dallies
        $mail->setFrom($email, $name);
        $mail->addAddress('sas.dallies@gmail.com', 'SAS Dallies');
        $mail->addReplyTo($email, $name);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Nouvelle demande de rendez-vous';
        $mail->Body = '
        <div style="background:#f6f6f6;padding:30px 0;">
          <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:auto;background:#fff;border-radius:10px;box-shadow:0 2px 8px #eee;">
            <tr>
              <td style="padding:32px 32px 0 32px;text-align:center;">
                <!-- Logo ou nom -->
                <div style="margin-bottom:24px;">
                  <span style="font-size:28px;font-weight:bold;color:#e0a800;letter-spacing:2px;">SAS Dallies</span>
                </div>
                <h1 style="font-size:22px;color:#222;margin-bottom:12px;">Nouvelle demande de rendez-vous reçue</h1>
                <p style="font-size:16px;color:#444;margin-bottom:28px;">Voici les détails de la demande :</p>
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
                    <td style="padding:8px 0;font-weight:bold;">bâtiment :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($buildingType) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Date :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($rdvDate) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;">Heure :</td>
                    <td style="padding:8px 0;">' . htmlspecialchars($heure) . '</td>
                  </tr>
                  <tr>
                    <td style="padding:8px 0;font-weight:bold;vertical-align:top;">Message :</td>
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
        $mail->AltBody = "Nom : $name\nEmail : $email\nTéléphone : $phone\nType de bâtiment : $buildingType\nDate : $rdvDate\nHeure : $heure\nMessage : $message";

        // Envoi
        $mail->send();
        header('Location: index.html'); // Redirection après succès
        exit;
    } catch (Exception $e) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}\n";
        echo "Exception : " . $e->getMessage();
    }
} else {
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Méthode non autorisée.';
}
?>
