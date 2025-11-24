<?php

require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cadastro.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';
$confirm_senha = $_POST['confirmar_senha'] ?? ''; // ✅ corrigido
$cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$data_nasc = $_POST['data_nascimento'] ?? '';
$cep = trim($_POST['cep'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$genero = $_POST['genero'] ?? '';
$genero_outro = trim($_POST['genero_outro'] ?? '');
$unidade = trim($_POST['unidade'] ?? '');
$plano = trim($_POST['plano'] ?? '');

$errors = [];

// validações básicas
if (empty($nome)) $errors[] = "Preencha seu nome.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "E-mail inválido.";
if (strlen($senha) < 6) $errors[] = "A senha deve ter ao menos 8 caracteres.";
if ($senha !== $confirm_senha) $errors[] = "Senhas não coincidem."; // ✅ agora funciona
if (!empty($cpf) && !validar_cpf($cpf)) $errors[] = "CPF inválido.";
if (!empty($data_nasc) && !DateTime::createFromFormat('Y-m-d', $data_nasc)) $errors[] = "Data de nascimento inválida.";
if (empty($genero)) $errors[] = "Selecione seu gênero.";
if (empty($unidade)) $errors[] = "Selecione uma unidade.";
if (empty($plano)) $errors[] = "Selecione um plano.";
// Tratamento do gênero
if ($genero === 'outro') {
    $genero = $genero_outro ?: 'outro';
}

// checa duplicidades
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->fetch()) $errors[] = "E-mail já cadastrado.";

if (!empty($cpf)) {
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE cpf = ? LIMIT 1");
    $stmt->execute([$cpf]);
    if ($stmt->fetch()) $errors[] = "CPF já cadastrado.";
}

if (!empty($errors)) {
    session_start();
    $_SESSION['reg_errors'] = $errors;
    $_SESSION['reg_old'] = $_POST;
    header('Location: ../cadastro.php');
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);
$token = gerar_token(24);
$expira = (new DateTime('+2 days'))->format('Y-m-d H:i:s');

$insert = $pdo->prepare("
INSERT INTO usuarios (
    tipo,               -- aluno
    nome,              
    sobrenome,          -- NULL
    email,              
    senha_hash,         
    cpf,                
    telefone,           
    card_last4,         -- NULL
    payment_status,     -- pending
    data_nascimento,    
    genero,             
    cep,                
    endereco,           
    unidade,            
    plano,              
    numero,             -- NULL
    bairro,             -- NULL
    cidade,             
    estado,             -- NULL
    complemento,        -- NULL
    foto_perfil,        -- NULL
    ativo,              -- 1
    data_cadastro,      
    email_confirmado,   -- 0
    confirm_token,      
    token_expira        
)
VALUES (
    'aluno',                -- tipo fixo
    ?,                      -- nome
    NULL,                   -- sobrenome
    ?,                      -- email
    ?,                      -- senha_hash
    ?,                      -- cpf
    ?,                      -- telefone
    NULL,                   -- card_last4
    'pending',              -- payment_status
    ?,                      -- data_nascimento
    ?,                      -- genero
    ?,                      -- cep
    ?,                      -- endereco
    ?,                      -- unidade
    ?,                      -- plano
    NULL,                   -- numero
    NULL,                   -- bairro
    ?,                      -- cidade
    NULL,                   -- estado
    NULL,                   -- complemento
    NULL,                   -- foto_perfil
    1,                      -- ativo
    NOW(),                  -- data_cadastro
    0,                      -- email_confirmado
    ?,                      -- confirm_token
    ?                       -- token_expira
)
");

$ok = $insert->execute([
    $nome,
    $email,
    $senha_hash,
    $cpf,
    $telefone,
    $data_nasc,
    $genero,
    $cep,
    $endereco,
    $unidade,
    $plano,
    $cidade,
    $token,
    $expira
]);



if (!$ok) {
    die("Erro ao registrar. Tente novamente mais tarde.");
}

require_once __DIR__ . '/../includes/email_config.php'; // ✅ caminho corrigido

$mensagem = "
<h2>Bem-vindo à OLYMPIA'S!</h2>
<p>Olá, <b>{$nome}</b>! Seu cadastro em nossa academia virtual foi realizado com sucesso.</p>
<p>Você pode acessar sua conta <a href='http://localhost/PROJETO-OLYMPIAS/login.php'>clicando aqui</a>.</p>
<p style='font-size:0.9em;color:#666;'>OLYMPIA'S Academia Virtual</p>
";

enviarEmail($email, "Cadastro Realizado com Sucesso! - OLYMPIA'S", $mensagem);

// Assumimos $pdo disponível, $ok = cadastro realizado e $insert executado
// Recupera ID do usuário recém-criado
$userId = $pdo->lastInsertId();

// Data-base para cobrança (use data_inicio ou data_cadastro)
$dataCadastro = new DateTime($expira); // não, não usar $expira; em vez disso, recupere data_cadastro do DB
// Melhor: buscar data_cadastro do próprio usuário para garantir consistência:
$stmt = $pdo->prepare("SELECT DATE(data_cadastro) AS data_inicio FROM usuarios WHERE id = ?");
$stmt->execute([$userId]);
$row = $stmt->fetch();
$dataInicio = $row ? new DateTime($row['data_inicio']) : new DateTime();

$dia = (int)$dataInicio->format('d'); // dia do mês que será cobrado

// valor padrão conforme plano
$valor_mensal = 99.90;
$valor_anual = 958.80;

$plano = $_POST['plano'] ?? 'mensal'; // ou pegue do banco
if ($plano === 'anual') {
    // gerar apenas 1 parcela do próximo ano e marcar a atual como paga
    $v = (clone $dataInicio)->modify('+1 year');
    $sql = $pdo->prepare("INSERT INTO parcelas (usuario_id, vencimento, valor, status) VALUES (?, ?, ?, ?)");
    // marcar a do ano atual como pago (assumimos que pago no cadastro)
    $sql->execute([$userId, $dataInicio->format('Y-m-d'), $valor_anual, 'pago']);
    $sql->execute([$userId, $v->format('Y-m-d'), $valor_anual, 'pendente']);
} else {
    // mensal: gerar 12 meses. Marca o mês corrente como pago.
    $base = clone $dataInicio;
    for ($i = 0; $i < 12; $i++) {
        $parcelaData = (clone $base)->modify("+{$i} months");
        // corrige dia para meses curtos (ex: 31 -> 28/29)
        $d = (int)$parcelaData->format('d');
        $m = (int)$parcelaData->format('m');
        $y = (int)$parcelaData->format('Y');
        // cria DateTime forceando o dia (se inválido, DateTime ajusta automaticamente)
        $vencDate = DateTime::createFromFormat('Y-n-j', "{$y}-{$m}-{$dia}");
        if (!$vencDate) { $vencDate = clone $parcelaData; }
        // status: se i == 0, é mês do cadastro -> pago
        $status = ($i === 0) ? 'pago' : 'pendente';
        $stmtParcel = $pdo->prepare("INSERT INTO parcelas (usuario_id, vencimento, valor, status) VALUES (?, ?, ?, ?)");
        $stmtParcel->execute([$userId, $vencDate->format('Y-m-d'), $valor_mensal, $status]);
    }
}

// ✅ redirecionamento garantido
header('Location: ../login.php?success=cadastro_ok');
exit;
?>