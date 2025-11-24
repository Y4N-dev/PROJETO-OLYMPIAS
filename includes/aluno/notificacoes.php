<div class="section" data-section="notificacoes" style="display:none;">

    <h2 class="notif-title">ㅤㅤNotificações</h2>

    <div id="notifContainer" class="notificacoes-wrapper"></div>

    <script>
        function carregarNotifsUI() {
            const cont = document.getElementById("notifContainer");
            cont.innerHTML = "";

            const lista = window.appNotifs.lista;

            // ============================
            // LIMITE DE 20 NOTIFICAÇÕES
            // ============================
            let houveRemocao = false;

            if (lista.length > 20) {
                // Remove as mais antigas
                const remover = lista.length - 20;
                window.appNotifs.lista = lista.slice(remover);
                houveRemocao = true;
            }

            const listaFinal = window.appNotifs.lista;

            if (!listaFinal || listaFinal.length === 0) {
                cont.innerHTML = `<p class="notif-vazio">Nenhuma notificação por aqui...</p>`;
                return;
            }

            listaFinal.forEach(n => {
                cont.innerHTML += `
                    <div class="notificacao-card">
                        <div class="notificacao-content">
                            <div class="notificacao-titulo">${n.titulo}</div>
                            <div class="notificacao-divider"></div>
                            <div class="notificacao-texto">${n.texto}</div>
                            <div class="notificacao-data">${n.data}</div>
                        </div>
                    </div>
                `;
            });

            // ============================
            // AVISO DE NOTIFICAÇÕES REMOVIDAS
            // ============================
            if (houveRemocao) {
                cont.innerHTML += `
                    <div class="notif-removidas-msg">
                        Algumas notificações antigas foram removidas automaticamente.
                    </div>
                `;
            }
        }


        window.addEventListener("novaNotificacao", carregarNotifsUI);
        document.addEventListener("DOMContentLoaded", carregarNotifsUI);
    </script>

</div>
