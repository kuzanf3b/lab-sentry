            </main>
            </div>
            </div>

            <div class="modal" id="confirmModal" aria-hidden="true">
                <div class="modal__backdrop" data-modal-close></div>
                <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="confirmModalTitle">
                    <div class="modal__header">
                        <div class="modal__title" id="confirmModalTitle">Konfirmasi</div>
                        <button class="modal__close" type="button" data-modal-close aria-label="Tutup">✕</button>
                    </div>
                    <div class="modal__body">
                        <div id="confirmModalMessage">Yakin?</div>
                    </div>
                    <div class="modal__footer">
                        <button class="btn btn--ghost" type="button" data-confirm-cancel>Batal</button>
                        <button class="btn btn--pink" type="button" data-confirm-ok>Hapus</button>
                    </div>
                </div>
            </div>

            <script src="<?php echo htmlspecialchars(url_for('assets/js/app.js'), ENT_QUOTES); ?>"></script>
            </body>

            </html>