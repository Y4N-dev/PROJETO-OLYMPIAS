<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

// Campos do formulário
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';

// Verifica se os campos estão preenchidos
if (empty($email) || empty($senha)) {
    $_SESSION['login_erro'] = 'Preencha todos os campos.';
    header('Location: ../login.php');
    exit;
}

// Busca o usuário no banco
$stmt = $pdo->prepare("
    SELECT 
        id, nome, sobrenome, email, senha_hash,
        cpf, telefone, data_nascimento, genero,
        cep, endereco, unidade, plano,
        numero, bairro, cidade, estado, complemento
    FROM usuarios
    WHERE email = ?
    LIMIT 1
");

$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($senha, $user['senha_hash'])) {

    // Dados mínimos obrigatórios
    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['usuario_nome'] = $user['nome'];
    $_SESSION['usuario_email'] = $user['email'];

    // Dados adicionais
    $_SESSION['cpf'] = $user['cpf'];
    $_SESSION['telefone'] = $user['telefone'];
    $_SESSION['data_nascimento'] = $user['data_nascimento'];
    $_SESSION['genero'] = $user['genero'];

    $_SESSION['cep'] = $user['cep'];
    $_SESSION['endereco'] = $user['endereco'];
    $_SESSION['unidade'] = $user['unidade'];
    $_SESSION['plano_us'] = $user['plano'];

    $_SESSION['numero'] = $user['numero'];
    $_SESSION['bairro'] = $user['bairro'];
    $_SESSION['cidade'] = $user['cidade'];
    $_SESSION['estado'] = $user['estado'];
    $_SESSION['complemento'] = $user['complemento'];

    require_once __DIR__ . '/../includes/parcelas_utils.php';
    $res = manterParcelasUsuario($pdo, $user['id']);
    if (!empty($res['deleted']) && $res['deleted'] === true) {
        // usuário removido por 3 meses em atraso; redirecionar para tela informando
        session_destroy();
        header('Location: ../index.php?msg=expulso');
        exit;
    }

    // Redirecionar após login
    header('Location: ../painel.php');
    exit;

} else {
    $_SESSION['login_erro'] = 'E-mail ou senha incorretos.';
    header('Location: ../login.php');
    exit;
}
