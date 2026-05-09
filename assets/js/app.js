(() => {
  const openModal = (modal) => {
    if (!modal) return;
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
  };

  const closeModal = (modal) => {
    if (!modal) return;
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
  };

  document.addEventListener("click", (e) => {
    const t = e.target;
    if (!(t instanceof HTMLElement)) return;

    const closeEl = t.closest("[data-modal-close]");
    if (closeEl) {
      const modal = t.closest(".modal");
      closeModal(modal);
      return;
    }

    const openKey = t.getAttribute("data-modal-open");
    if (openKey) {
      const modal = document.getElementById(openKey);
      openModal(modal);
    }
  });

  // Confirmation modal for forms with data-confirm
  const confirmModal = document.getElementById("confirmModal");
  const confirmMsg = document.getElementById("confirmModalMessage");
  const okBtn = confirmModal?.querySelector("[data-confirm-ok]");
  const cancelBtn = confirmModal?.querySelector("[data-confirm-cancel]");
  let pendingForm = null;

  document.addEventListener("submit", (e) => {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;

    const message = form.getAttribute("data-confirm");
    if (!message) return;

    if (form.dataset.confirmed === "1") {
      form.dataset.confirmed = "0";
      return;
    }

    e.preventDefault();
    pendingForm = form;
    if (confirmMsg) confirmMsg.textContent = message;
    openModal(confirmModal);
  });

  okBtn?.addEventListener("click", () => {
    if (!pendingForm) {
      closeModal(confirmModal);
      return;
    }

    pendingForm.dataset.confirmed = "1";
    pendingForm.submit();
    pendingForm = null;
    closeModal(confirmModal);
  });

  cancelBtn?.addEventListener("click", () => {
    pendingForm = null;
    closeModal(confirmModal);
  });
})();
