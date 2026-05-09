(() => {
  const returnFocusMap = new WeakMap();

  const setBodyModalOpen = () => {
    document.body.classList.add("modal-open");
  };

  const clearBodyModalOpenIfNeeded = () => {
    if (document.querySelector(".modal.is-open")) return;
    document.body.classList.remove("modal-open");
  };

  const focusFirstField = (modal) => {
    const dialog = modal?.querySelector(".modal__dialog");
    if (!(dialog instanceof HTMLElement)) return;

    const focusable = dialog.querySelector(
      'input:not([type="hidden"]), select, textarea, button, a[href], [tabindex]:not([tabindex="-1"])',
    );

    if (focusable instanceof HTMLElement) {
      focusable.focus({ preventScroll: true });
    }
  };

  const openModal = (modal, openerEl) => {
    if (!modal) return;

    if (openerEl instanceof HTMLElement) {
      returnFocusMap.set(modal, openerEl);
    }

    setBodyModalOpen();
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");

    requestAnimationFrame(() => focusFirstField(modal));
  };

  const closeModal = (modal) => {
    if (!modal) return;
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");

    clearBodyModalOpenIfNeeded();

    const opener = returnFocusMap.get(modal);
    if (opener instanceof HTMLElement) {
      requestAnimationFrame(() => opener.focus({ preventScroll: true }));
    }
  };

  const fillEditStockModal = (triggerEl) => {
    const modal = document.getElementById("editStockModal");
    if (!modal) return;

    const id = triggerEl.getAttribute("data-stock-id") || "";
    const nama = triggerEl.getAttribute("data-stock-nama") || "";
    const kode = triggerEl.getAttribute("data-stock-kode") || "";
    const stok = triggerEl.getAttribute("data-stock-stok") || "0";
    const kondisi = triggerEl.getAttribute("data-stock-kondisi") || "Baik";

    const idInput = modal.querySelector('input[name="id_barang"]');
    const namaInput = modal.querySelector('input[name="nama_barang"]');
    const kodeInput = modal.querySelector('input[name="kode_aset"]');
    const stokInput = modal.querySelector('input[name="stok"]');
    const kondisiSelect = modal.querySelector('select[name="kondisi"]');

    if (idInput instanceof HTMLInputElement) idInput.value = id;
    if (namaInput instanceof HTMLInputElement) namaInput.value = nama;
    if (kodeInput instanceof HTMLInputElement) kodeInput.value = kode;
    if (stokInput instanceof HTMLInputElement) stokInput.value = stok;
    if (kondisiSelect instanceof HTMLSelectElement)
      kondisiSelect.value = kondisi;
  };

  document.addEventListener("click", (e) => {
    const t = e.target;
    if (!(t instanceof HTMLElement)) return;

    const closeEl = t.closest("[data-modal-close]");
    if (closeEl) {
      const modal = closeEl.closest(".modal");
      closeModal(modal);
      return;
    }

    const openEl = t.closest("[data-modal-open]");
    if (!openEl) return;

    const openKey = openEl.getAttribute("data-modal-open");
    if (!openKey) return;

    if (openKey === "editStockModal") {
      fillEditStockModal(openEl);
    }

    const modal = document.getElementById(openKey);
    openModal(modal, openEl);
  });

  document.addEventListener("keydown", (e) => {
    if (e.key !== "Escape") return;
    document.querySelectorAll(".modal.is-open").forEach((m) => closeModal(m));
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
    const opener = document.activeElement;
    openModal(confirmModal, opener instanceof HTMLElement ? opener : null);
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
