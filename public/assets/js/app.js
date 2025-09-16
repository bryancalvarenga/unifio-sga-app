document.addEventListener("DOMContentLoaded", () => {
  const botoes = document.querySelectorAll(".ver-evento");
  const modalBody = document.querySelector("#modalEvento .modal-body");

  botoes.forEach(botao => {
    botao.addEventListener("click", async () => {
      const id = botao.dataset.id;
      modalBody.innerHTML = `<p class="text-center text-muted">Carregando...</p>`;
      
      try {
        const resposta = await fetch(`/eventos/ver?id=${id}&modal=1`);
        const html = await resposta.text();
        modalBody.innerHTML = html;
      } catch (e) {
        modalBody.innerHTML = `<div class="alert alert-danger">Erro ao carregar evento.</div>`;
      }
    });
  });

  /* ===========================
     CALENDÁRIO: Seleção de slots
     =========================== */
  const slots = document.querySelectorAll('.slot');
  const dataInput = document.getElementById('data_evento');
  const perInput  = document.getElementById('periodo');
  const labelSel  = document.getElementById('selecionado');
  const salvar    = document.getElementById('btnSalvar');

  if (slots.length > 0) {
    slots.forEach(btn => {
      btn.addEventListener('click', () => {
        if (btn.disabled || btn.classList.contains('disabled')) return;

        // limpa seleção anterior
        document.querySelectorAll('.slot.selected').forEach(b => b.classList.remove('selected'));

        // marca o clicado
        btn.classList.add('selected');

        // preenche os hidden inputs
        if (dataInput) dataInput.value = btn.dataset.date;
        if (perInput) perInput.value  = btn.dataset.periodo;

        // mostra no label
        if (labelSel) {
          labelSel.textContent = new Date(btn.dataset.date).toLocaleDateString('pt-BR') +
            ' • ' + (btn.dataset.periodo === 'P1' ? '19:15' : '21:10');
        }

        // libera botão salvar
        if (salvar) salvar.disabled = false;
      });
    });
  }

  /* ==================================
     CHECKBOX DE MATERIAIS -> Hidden
     ================================== */
  const mats = document.querySelectorAll('.materiais-check');
  const hidden = document.getElementById('materiais_necessarios');
  if (mats.length > 0 && hidden) {
    function syncMats(){
      const items = Array.from(mats).filter(m => m.checked).map(m => m.value);
      hidden.value = items.join(', ');
    }
    mats.forEach(m => m.addEventListener('change', syncMats));
    syncMats();
  }
});
