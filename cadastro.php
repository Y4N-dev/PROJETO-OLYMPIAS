<?php include 'includes/header.php'; ?>

<section class="cadastro-section">
  <div class="cadastro-container">
    <h2>Cadastre-se</h2>
<form action="actions/register_action.php" method="POST" class="cadastro-form">

  <div class="cadastro-grid">
    <!-- Nome -->
    <div class="cadastro-form-group">
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" maxlength="250" placeholder="Nome Completo" required>
    </div>

    <!-- Data de Nascimento -->
    <div class="cadastro-form-group">
      <label for="data_nascimento">Data de Nascimento</label>
      <input type="date" id="data_nascimento" name="data_nascimento" maxlength="8" required>
    </div>

    <!-- Gênero -->
    <div class="cadastro-form-group">
      <label for="genero">Gênero</label>
      <select id="genero" name="genero" required>
        <option value="">Selecione</option>
        <option value="masculino">Masculino</option>
        <option value="feminino">Feminino</option>
        <option value="outro">Outro</option>
      </select>
    </div>

    <!-- Campo mostrado apenas se "Outro" for escolhido -->
    <div class="cadastro-form-group" id="genero_outro_group" style="display: none;">
      <label for="genero_outro">Digite seu gênero</label>
      <input type="text" id="genero_outro" name="genero_outro" placeholder="Especifique seu gênero">
    </div>

    <!-- CPF -->
    <div class="cadastro-form-group">
      <label for="cpf">CPF</label>
      <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
    </div>

    <!-- Telefone -->
    <div class="cadastro-form-group">
      <label for="telefone">Telefone</label>
      <input type="tel" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
    </div>

    <!-- Cidade -->
    <div class="cadastro-form-group">
      <label for="cidade">Cidade</label>
      <input type="text" id="cidade" name="cidade" required>
    </div>

    <!-- CEP -->
    <div class="cadastro-form-group">
      <label for="cep">CEP</label>
      <input type="text" id="cep" name="cep" required>
    </div>

    <!-- Endereço -->
    <div class="cadastro-form-group">
      <label for="endereco">Endereço</label>
      <input type="text" id="endereco" name="endereco" placeholder="Rua, número, bairro" required>
    </div>

    <!-- E-mail -->
    <div class="cadastro-form-group">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" placeholder="Ex.: jeoftonfera@gmail.com" required>
    </div>

    <!-- Unidade -->
    <div class="cadastro-form-group">
      <label for="unidade">Unidade OLYMPIA'S</label>
      <select id="unidade" name="unidade" required>
        <option value="">Selecione uma unidade</option>
        <option value="manaira">OLYMPIA'S João Pessoa – Manaíra</option>
        <option value="tambau">OLYMPIA'S João Pessoa – Tambaú</option>
        <option value="campina">OLYMPIA'S Campina Grande – Centro</option>
        <option value="patos">OLYMPIA'S Patos – Centro</option>
        <option value="cabedelo">OLYMPIA'S Cabedelo – Praia do Jacaré</option>
        <option value="pb_joaopessoa">OLYMPIA'S João Pessoa – PB (Manaíra)</option>
      </select>
    </div>

    <!-- Plano -->
    <div class="cadastro-form-group">
      <label for="plano">Plano</label>
      <select id="plano" name="plano" required>
        <option value="mensal" selected>Mensal – R$ 99,90</option>
        <option value="anual">Anual – R$ 958,80 (R$ 79,90/mês)</option>
      </select>
    </div>

    <!-- Campos do cartão (um por coluna da grid para alinhamento horizontal) -->
    <div class="cadastro-form-group">
      <label for="numero_cartao">Número do cartão</label>
      <input type="text" id="numero_cartao" name="numero_cartao" placeholder="0000 0000 0000 0000" maxlength="19" required>
    </div>

    <div class="cadastro-form-group">
      <label for="validade_cartao">Data de validade</label>
      <input type="text" id="validade_cartao" name="validade_cartao" placeholder="MM/AA" maxlength="5" required>
    </div>

    <div class="cadastro-form-group">
      <label for="cvv_cartao">Código de segurança (CVV)</label>
      <input type="text" id="cvv_cartao" name="cvv_cartao" placeholder="123" maxlength="3" required>
    </div>

    <!-- Senha -->
    <div class="cadastro-form-group full-width">
      <label for="senha">Senha</label>
      <div class="password-field">
        <input type="password" id="senha" name="senha" placeholder="(Mínimo 8 caracteres)" required>
        <button type="button" class="password-toggle" aria-label="Mostrar/Ocultar senha">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>

    <!-- Confirmar Senha -->
    <div class="cadastro-form-group full-width">
      <label for="confirmar_senha">Confirmar Senha</label>
      <div class="password-field">
        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Copie aqui a mesma senha do campo acima" required>
        <button type="button" class="password-toggle" aria-label="Mostrar/Ocultar senha">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>
    
    </div>
    <input type="hidden" name="pagamento_confirmado" value="1">
    <button type="submit" class="cadastro-btn">Cadastrar</button>
    <p class="cadastro-alt">
      Já tem uma conta?
      <a href="login.php" class="link-login">Entre agora!</a>
    </p>
</form>
<!-- Modal de pagamento (simulado) -->
<div id="payment-sim-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:20px;border-radius:8px;max-width:420px;width:92%;text-align:center;box-shadow:0 6px 30px rgba(0,0,0,.3);">
    <h2 style="margin:0 0 12px;font-size:18px;">Pagamento (simulado)</h2>
    <!-- QR falso (SVG embutido) -->
    <div style="display:flex;justify-content:center;margin-bottom:12px;">
      <svg width="220" height="220" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="QR falso">
        <rect width="220" height="220" fill="#fff"/>
        <!-- blocos pretos para simular QR -->
        <rect x="8" y="8" width="40" height="40" fill="#000"/>
        <rect x="8" y="72" width="40" height="40" fill="#000"/>
        <rect x="72" y="8" width="40" height="40" fill="#000"/>
        <rect x="140" y="8" width="12" height="12" fill="#000"/>
        <rect x="120" y="40" width="16" height="16" fill="#000"/>
        <rect x="160" y="120" width="12" height="12" fill="#000"/>
        <rect x="40" y="160" width="12" height="12" fill="#000"/>
        <!-- mais blocos para aparência -->
        <rect x="100" y="100" width="16" height="16" fill="#000"/>
        <rect x="124" y="124" width="10" height="10" fill="#000"/>
        <rect x="160" y="160" width="20" height="20" fill="#000"/>
      </svg>
    </div>

    <p style="margin:0 0 16px;color:#333;">Este é um pagamento simulado para a tela de cadastro.</p>

    <div style="display:flex;gap:8px;justify-content:center;">
      <button id="confirm-pay" type="button" style="padding:8px 14px;border-radius:6px;border:0;background:#28a745;color:#fff;cursor:pointer;">
        Confirmar pagamento
      </button>
      <button id="cancel-pay" type="button" style="padding:8px 14px;border-radius:6px;border:0;background:#6c757d;color:#fff;cursor:pointer;">
        Cancelar
      </button>
    </div>
  </div>
</div>

<script>
  (function(){
    // Ajuste: substitua pelo id real do seu formulário se for outro
    var form = document.getElementById('cadastro-form');
    var modal = document.getElementById('payment-sim-modal');
    var btnConfirm = document.getElementById('confirm-pay');
    var btnCancel = document.getElementById('cancel-pay');

    if(!form || !modal) return;

    form.addEventListener('submit', function(e){
      e.preventDefault(); // não enviar ainda
      // Abre o popin de pagamento SIMULADO (sempre)
      modal.style.display = 'flex';
      // opcional: focar botão confirmar
      btnConfirm.focus && btnConfirm.focus();
    });

    btnConfirm.addEventListener('click', function(){
      // Simulação concluída: redireciona para a tela de login
      window.location.href = 'login.php';
    });

    btnCancel.addEventListener('click', function(){
      // Fecha o modal e permite correção no formulário
      modal.style.display = 'none';
    });

    // Fecha ao clicar fora do conteúdo
    modal.addEventListener('click', function(ev){
      if(ev.target === modal) modal.style.display = 'none';
    });
  })();
</script>
</div>
</section>


<!-- insira no <head> ou antes do fechamento do body -->
<script>
// ===================== AUTO-SELECIONAR UNIDADE =====================
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const unidade = params.get("unidade");
  if (unidade) {
    const select = document.querySelector("#unidade");
    if (select) {
      const opt = Array.from(select.options).find(o => o.value.includes(unidade));
      if (opt) {
        opt.selected = true;
      }
    }
  }

  // ======= CPF MÁSCARA =======
  const cpfInput = document.querySelector('#cpf');
  if (cpfInput) {
    cpfInput.addEventListener('input', e => {
      let v = cpfInput.value.replace(/\D/g, '').slice(0,11);
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d)/, '$1.$2');
      v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      cpfInput.value = v;
    });
  }

  // ======= TELEFONE =======
  const telInput = document.querySelector('#telefone');
  if (telInput) {
    telInput.addEventListener('input', e => {
      let v = telInput.value.replace(/\D/g, '');
      v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
      v = v.replace(/(\d{5})(\d{4})$/, '$1-$2');
      telInput.value = v;
    });
  }

  // ======= CEP =======
  const cepInput = document.querySelector('#cep');
  if (cepInput) {
    cepInput.addEventListener('input', e => {
      let v = cepInput.value.replace(/\D/g, '').slice(0,8);
      v = v.replace(/(\d{5})(\d)/, '$1-$2');
      cepInput.value = v;
    });
  }

    // Formatação simples para número do cartão e validade
  const numCartao = document.querySelector('#numero_cartao');
  const validade = document.querySelector('#validade_cartao');

  if (numCartao) {
    numCartao.addEventListener('input', () => {
      let v = numCartao.value.replace(/\D/g, '').slice(0, 16);
      v = v.replace(/(\d{4})(?=\d)/g, '$1 ');
      numCartao.value = v;
    });
  }

  if (validade) {
    validade.addEventListener('input', () => {
      let v = validade.value.replace(/\D/g, '').slice(0, 4);
      if (v.length >= 3) v = v.replace(/(\d{2})(\d{2})/, '$1/$2');
      validade.value = v;
    });
  }

  // Funcionalidade de mostrar/ocultar senha
  const toggleButtons = document.querySelectorAll('.login-password-toggle');
  toggleButtons.forEach(button => {
    button.addEventListener('click', () => {
      const input = button.parentElement.querySelector('input');
      const icon = button.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
        button.classList.add('visible');
      } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
        button.classList.remove('visible');
      }
    });
  });
  // ======= GÊNERO: Exibir campo "Outro" =======
    const generoSelect = document.querySelector("#genero");
    const generoOutroGroup = document.querySelector("#genero_outro_group");
    const generoOutroInput = document.querySelector("#genero_outro");

    if (generoSelect) {
      generoSelect.addEventListener("change", () => {
        if (generoSelect.value === "outro") {
          generoOutroGroup.style.display = "block";
          generoOutroInput.required = true;
        } else {
          generoOutroGroup.style.display = "none";
          generoOutroInput.required = false;
          generoOutroInput.value = "";
        }
      });
    }

});
</script>
<script src="assets/js/pagamento_simulado.js"></script>

<?php include 'includes/footer.php'; ?>
