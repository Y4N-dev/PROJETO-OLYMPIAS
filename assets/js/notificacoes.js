// assets/js/notificacoes.js
// Regras:
// - Usa localStorage para marcação de lido/não-lido
// - Integra serverNotifications (se fornecido pelo PHP) e notificações simuladas do cliente

(function () {
  const storageKey = "app_notifs_map_v1"; // guarda {id: {read:bool, persisted:true}}
  const localSimKey = "app_notifs_local_v1"; // lista de notificações geradas localmente
  const listEl = document.getElementById("notificacoesList");
  const emptyEl = document.getElementById("notifsEmpty");
  const countEl = document.getElementById("notifsCount");
  const markAllBtn = document.getElementById("notifsMarkAllRead");

  if (!listEl) return; // nada a fazer

  // Leitura do mapa de estado
  function readState() {
    try {
      return JSON.parse(localStorage.getItem(storageKey) || "{}");
    } catch (e) { return {}; }
  }
  function writeState(s) {
    localStorage.setItem(storageKey, JSON.stringify(s));
  }

  // read local notifications (simulated events) - keep unique via id
  function readLocalSims() {
    try {
      return JSON.parse(localStorage.getItem(localSimKey) || "[]");
    } catch (e) { return []; }
  }
  function saveLocalSims(arr) {
    localStorage.setItem(localSimKey, JSON.stringify(arr));
  }

  // Gera simulações leves: treino concluído (checa treinos_concluidos_7dias), pagamento pendente (checagem de parcelas em localStorage),
  // agendamento pseudo (apenas demo). Essas notificações são criadas apenas uma vez (persistidas no localSimKey).
  function generateSimulations() {
    const sims = readLocalSims();
    const ids = new Set(sims.map(s => s.id));

    // 1) Treinos concluídos -> para cada dia marcado como concluído crie uma notificação única
    try {
      const concluidos = JSON.parse(localStorage.getItem("treinos_concluidos_7dias") || "{}");
      Object.keys(concluidos).forEach(d => {
        if (!concluidos[d]) return;
        const id = `treino_${d}_${concluidos[d]}`;
        if (!ids.has(id)) {
          sims.unshift({
            id,
            type: "treino_concluido",
            title: "Parabéns!",
            body: `Você concluiu o treino de ${d}. Continue assim!`,
            date: new Date().toISOString().slice(0,10),
            read: false
          });
          ids.add(id);
        }
      });
    } catch(e){}

    // 2) Pagamento: se existe localStorage 'parcelas_sim' com pendentes (simulação local), cria notificação 'pendencia'
    try {
      const parcelas = JSON.parse(localStorage.getItem("parcelas_sim") || "[]");
      parcelas.forEach(p => {
        if (p.status === "pendente") {
          const id = `parcela_${p.id}`;
          if (!ids.has(id)) {
            sims.unshift({
              id,
              type: "pagamento_pendente",
              title: "Pagamento pendente",
              body: `Parcela vencendo em ${p.vencimento} no valor R$ ${p.valor}`,
              date: (p.vencimento || '').slice(0,10),
              read: false
            });
            ids.add(id);
          }
        } else if (p.status === "pago") {
          // notificação de pagamento realizado (gera apenas uma vez)
          const id = `parcela_pago_${p.id}`;
          if (!ids.has(id) && p.pago_em) {
            sims.unshift({
              id,
              type: "pagamento_realizado",
              title: "Pagamento realizado",
              body: `Pagamento da parcela ${p.vencimento} confirmado.`,
              date: (p.pago_em || '').slice(0,10),
              read: false
            });
            ids.add(id);
          }
        }
      });
    } catch(e){}

    // 3) Agendamento — se existe flag local 'ultimo_agendamento' cria notificação
    try {
      const ag = JSON.parse(localStorage.getItem("ultimo_agendamento") || "null");
      if (ag && !ids.has(`ag_${ag.date}`)) {
        sims.unshift({
          id: `ag_${ag.date}`,
          type: "agendamento",
          title: "Agendamento realizado",
          body: `Agendamento em ${ag.date} confirmado.`,
          date: ag.date,
          read: false
        });
      }
    } catch(e){}

    // Mantém tamanho razoável
    const trimmed = sims.slice(0, 200);
    saveLocalSims(trimmed);
    return trimmed;
  }

  // Mescla server notifications (window.serverNotifications) com locais
  function collectAll() {
    const server = Array.isArray(window.serverNotifications) ? window.serverNotifications : [];
    const local = generateSimulations();
    // server items might not have stable string id -> we will prefix them
    const serverMapped = server.map(s => {
      return {
        id: `srv_${s.id || Math.random().toString(36).slice(2,9)}`,
        type: s.type || 'mensagem',
        title: s.title || 'Notificação',
        body: s.body || '',
        date: s.date || new Date().toISOString().slice(0,10),
        read: !!s.read
      };
    });

    // Combine, most recent first (we assume inputs are ordered)
    const merged = [...serverMapped, ...local];

    // De-duplicate by id, preserving first occurrence (server first)
    const seen = new Set();
    const uniq = [];
    for (const n of merged) {
      if (seen.has(n.id)) continue;
      seen.add(n.id);
      uniq.push(n);
    }
    return uniq;
  }

  // Render single card
  function renderCard(n) {
    const div = document.createElement("article");
    div.className = "notif-card" + (n.read ? " read" : " unread");
    div.dataset.id = n.id;

    // date friendly
    const dateFmt = (d) => {
      if (!d) return "";
      try {
        const dt = new Date(d);
        if (isNaN(dt)) return d;
        return dt.toLocaleDateString();
      } catch(e){ return d; }
    };

    div.innerHTML = `
      <header class="notif-head">
        <strong class="notif-title">${escapeHtml(n.title)}</strong>
        <time class="notif-date">${dateFmt(n.date)}</time>
      </header>
      <div class="notif-body">${escapeHtml(n.body)}</div>
      <footer class="notif-actions">
        <button class="btn small btn-toggle-read">${n.read ? "Marcar não lida" : "Marcar lida"}</button>
      </footer>
    `;

    // handlers
    div.querySelector(".btn-toggle-read").addEventListener("click", () => {
      toggleRead(n.id, div);
    });

    return div;
  }

  // Escape simples
  function escapeHtml(s) {
    if (!s) return "";
    return String(s)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }

  // Toggle read state
  function toggleRead(id, cardEl) {
    const state = readState();
    state[id] = state[id] || {};
    state[id].read = !state[id].read;
    writeState(state);
    // update UI
    if (cardEl) {
      cardEl.classList.toggle("read", state[id].read);
      cardEl.classList.toggle("unread", !state[id].read);
      const btn = cardEl.querySelector(".btn-toggle-read");
      if (btn) btn.textContent = state[id].read ? "Marcar não lida" : "Marcar lida";
    }
    updateBadge();
  }

  function markAllRead() {
    const state = readState();
    // mark every rendered notification as read
    document.querySelectorAll(".notif-card").forEach(el => {
      const id = el.dataset.id;
      state[id] = state[id] || {};
      state[id].read = true;
    });
    writeState(state);
    // update DOM
    document.querySelectorAll(".notif-card").forEach(el => {
      el.classList.remove("unread");
      el.classList.add("read");
      const btn = el.querySelector(".btn-toggle-read");
      if (btn) btn.textContent = "Marcar não lida";
    });
    updateBadge();
  }

  // Atualiza badge na sidebar
  function updateBadge() {
    const state = readState();
    const all = collectAll();
    let unread = 0;
    all.forEach(n => {
      const s = state[n.id];
      if (!s || !s.read) unread++;
    });

    countEl.textContent = unread ? `${unread} novas` : "";
    // badge na sidebar
    const li = document.querySelector('.sidebar li[data-section="notificacoes"]');
    if (li) {
      let badge = li.querySelector(".notif-badge");
      if (!badge && unread) {
        badge = document.createElement("span");
        badge.className = "notif-badge";
        li.appendChild(badge);
      }
      if (badge) {
        badge.textContent = unread > 9 ? "9+" : (unread || "");
        badge.style.display = unread ? "inline-block" : "none";
      }
    }
  }

  // Render all
  function renderAll() {
    const all = collectAll();
    const state = readState();
    listEl.innerHTML = "";
    if (!all.length) {
      emptyEl.style.display = "block";
      return;
    }
    emptyEl.style.display = "none";

    all.forEach(n => {
      // apply state
      if (state[n.id]) n.read = !!state[n.id].read;
      const card = renderCard(n);
      listEl.appendChild(card);
    });

    updateBadge();
  }

  // Initialize
  document.addEventListener("DOMContentLoaded", () => {
    // markAllBtn handler
    if (markAllBtn) {
      markAllBtn.addEventListener("click", () => {
        markAllRead();
      });
    }

    // First render
    renderAll();
  });

  // Expose simple API for other scripts to push a notification
  window.appNotifs = {
    push: function (n) {
      // minimal validation; n must have id, type, title, body, date
      if (!n || !n.id) return;
      const sims = readLocalSims();
      // avoid duplicates
      if (!sims.find(x => x.id === n.id)) {
        sims.unshift(n);
        saveLocalSims(sims);
        renderAll();
      }
    }
  };

})();
