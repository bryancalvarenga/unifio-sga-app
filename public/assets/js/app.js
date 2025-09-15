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
});