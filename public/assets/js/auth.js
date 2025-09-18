document.addEventListener("DOMContentLoaded", () => {
  // Renderiza ícones do Lucide uma vez
  if (window.lucide?.createIcons) lucide.createIcons();

  // Seleciona botões do novo padrão e do legado
  const toggleButtons = [
    ...document.querySelectorAll("[data-toggle='senha']"),
    ...document.querySelectorAll("#toggleSenha")
  ];

  toggleButtons.forEach((btn) => {
    // Resolve o input de senha alvo
    let input = null;

    // Preferência: data-target="idDoInput"
    const targetId = btn.getAttribute("data-target");
    if (targetId) {
      input = document.getElementById(targetId);
    }

    // Fallback: procura o input no mesmo input-group
    if (!input) {
      const group = btn.closest(".input-group");
      input = group ? group.querySelector("input[type='password'], input[type='text']") : null;
    }

    if (!input) return; // sem input, sem toggle

    // Ícones (classes permanecem após o Lucide trocar <i> -> <svg>)
    const eye    = btn.querySelector(".icon-eye");
    const eyeOff = btn.querySelector(".icon-eyeoff");

    // Click: alterna tipo do input e visibilidade dos ícones
    btn.addEventListener("click", () => {
      const isVisible = input.type === "text";
      input.type = isVisible ? "password" : "text";

      if (eye && eyeOff) {
        eye.classList.toggle("d-none", !isVisible); // mostra olho aberto quando visível
        eyeOff.classList.toggle("d-none", isVisible); // mostra olho fechado quando oculto
      }

      btn.setAttribute("aria-pressed", String(!isVisible));
      btn.setAttribute("title", isVisible ? "Mostrar senha" : "Ocultar senha");
    });
  });

  // (Opcional) Aviso de Caps Lock em todos os campos de senha
  document.querySelectorAll("input[type='password']").forEach((pwd) => {
    pwd.addEventListener("keyup", (e) => {
      let badge = pwd.parentElement.querySelector(".caps-warning");
      if (!badge) {
        badge = document.createElement("small");
        badge.className = "caps-warning text-danger mt-1 d-block";
        pwd.parentElement.appendChild(badge);
      }
      badge.textContent = e.getModifierState("CapsLock") ? "⚠️ Caps Lock ativado" : "";
    });
  });

  // (Opcional) Validação de confirmação de senha (register)
  const senha   = document.getElementById("senha");
  const confirm = document.getElementById("senha_confirm");
  if (senha && confirm) {
    confirm.addEventListener("input", () => {
      confirm.setCustomValidity(confirm.value !== senha.value ? "As senhas não coincidem" : "");
    });
  }
});
