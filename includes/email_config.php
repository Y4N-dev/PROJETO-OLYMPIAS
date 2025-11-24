<?php
// includes/email_config.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ======= Tenta carregar o PHPMailer manualmente =======
$phpmailerPath = __DIR__ . '/phpmailer/PHPMailer-master/src/';
if (file_exists($phpmailerPath . 'PHPMailer.php')) {
    require_once $phpmailerPath . 'PHPMailer.php';
    require_once $phpmailerPath . 'SMTP.php';
    require_once $phpmailerPath . 'Exception.php';
}

function enviarEmail($destinatario, $assunto, $mensagemHTML) {
    // Caso PHPMailer não esteja carregado, evita erro fatal
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        error_log("PHPMailer não encontrado — ignorando envio de e-mail.");
        return false;
    }

    try {
        $mail = new PHPMailer(true);

        // CONFIGURAÇÕES DO SERVIDOR SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'olympiasacademia@gmail.com';
        $mail->Password = 'pdfe gonq pwej iqwt'; // senha de app (APAGAR QUANDO ENVIAR AO GIT)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // REMETENTE E DESTINATÁRIO
        $mail->setFrom('olympiasacademia@gmail.com', "OLYMPIA'S Academia");
        $mail->addAddress($destinatario);

        // CONTEÚDO
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $mensagemHTML;

        $mail->send();
        return true;

    } catch (Exception $e) {
        // Apenas registra o erro no log — não quebra o cadastro
        error_log("Erro ao enviar e-mail: {$e->getMessage()}");
        return false;
    }
}
