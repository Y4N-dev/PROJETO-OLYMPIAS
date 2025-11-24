<?php
// Página de Contatos
?>

<link rel="stylesheet" href="assets/css/contato/style.css">
<script src="assets/js/contatos.js" defer></script>

<section class="contatos-container">

    <div class="contatos-card card-anim">
        <h1>Fale Conosco</h1>
        <p>Estamos sempre prontos para ajudar! envie uma mensagem pelo formulário.</p>

        <div class="contatos-info">
            <p><strong>Email:</strong> olympiasacademia@gmail.com</p>
            <p><strong>Telefone:</strong> (83) 99999-0000</p>
            <p><strong>WhatsApp:</strong> (83) 98888-7777</p>
            <p><strong>Instagram:</strong> @olympiasfit</p>
        </div>
    </div>

    <div class="contatos-card card-anim">
        <h1>Envie sua Mensagem</h1>

        <form id="contatoForm">
            <input type="text" name="nome" placeholder="Seu nome" required>
            <input type="email" name="email" placeholder="Seu e-mail" required>
            <textarea name="mensagem" placeholder="Escreva sua mensagem..." required></textarea>

            <button class="btn-enviar">Enviar</button>
        </form>
    </div>

</section>

<!-- POP-IN DE CONFIRMAÇÃO -->
<div id="contatoModal" class="modal-overlay">
    <div class="modal-box">
        <button id="fecharContato" class="modal-close">&times;</button>

        <h3 class="modal-title">Mensagem enviada!</h3>
        <p class="modal-msg">Nossa equipe lhe retornará em breve 😊</p>

        <button id="contatoOk" class="modal-btn">Fechar</button>
    </div>
</div>
