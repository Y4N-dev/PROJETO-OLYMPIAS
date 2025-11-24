/* ===========================================
   SISTEMA DE NOTIFICAÇÕES GLOBAL
   =========================================== */

window.appNotifs = {
    lista: [],

    push(data) {
        const notif = {
            id: Date.now() + Math.random(),
            titulo: data.titulo || "Nova notificação",
            texto: data.texto || "",
            data: data.data || new Date().toLocaleDateString(),
        };

        this.lista.unshift(notif);

        // Salva no localStorage
        localStorage.setItem("notifs", JSON.stringify(this.lista));

        // Atualiza bolinha vermelha
        this.updateBadge();

        // Atualiza UI imediata se a aba de notificações estiver aberta
        window.dispatchEvent(new Event("novaNotificacao"));
    },

    load() {
        this.lista = JSON.parse(localStorage.getItem("notifs") || "[]");
        this.updateBadge();
    },

    updateBadge() {
        const badge = document.querySelector(".notif-badge");
        if (!badge) return;

        badge.style.display = this.lista.length > 0 ? "block" : "none";
        badge.textContent = this.lista.length;
    }
};

window.appNotifs.load();
