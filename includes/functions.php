<?php
// includes/functions.php

// gera token seguro (hex)
function gerar_token($bytes = 32) {
    return bin2hex(random_bytes($bytes));
}

// valida CPF (algoritmo padrão)
function validar_cpf($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) return false;
    if (preg_match('/^(.)\1{10}$/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

// envia e-mail usando PHPMailer (recomendado) OU fallback mail()
function enviar_email_confirmacao($to_email, $to_name, $token) {
    // link de confirmação — ajuste se subpasta diferente
    $link = sprintf(
        "%s://%s%s/confirm_email.php?token=%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http',
        $_SERVER['HTTP_HOST'],
        rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'),
        urlencode($token)
    );

    $subject = "Confirme seu cadastro — OLYMPIA'S";
    $body = "
        <p>Olá " . htmlspecialchars($to_name) . ",</p>
        <p>Obrigado por se cadastrar na OLYMPIA'S. Clique no link abaixo para confirmar seu e-mail:</p>
        <p><a href=\"" . htmlspecialchars($link) . "\">Confirmar meu e-mail</a></p>
        <p>Ou copie/cole este link no navegador:<br><code>$link</code></p>
        <p>Se você não se cadastrou, ignore esta mensagem.</p>
    ";

    // Fallback rápido — mail() (pode não funcionar em muitos hosts)
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: OLYMPIA'S <no-reply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
    return mail($to_email, $subject, $body, $headers);
}
