/**
 * Gestion du panier client : ajout via bouton .add-to-cart-btn
 */
(function () {
    'use strict';

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn) return;

        e.preventDefault();

        const variantId  = btn.dataset.productVariantId;   // ✅ corrigé
        const qteInputId = btn.dataset.qteInput;
        const qte        = qteInputId
            ? (parseInt(document.getElementById(qteInputId)?.value) || 1)
            : 1;

        if (!variantId) {
            Swal.fire({ icon: 'error', title: 'Erreur', text: 'Variante introuvable.' });
            return;
        }

        const cartUrl    = btn.dataset.cartUrl || '/client/panier';
        const csrfToken  = document.querySelector('meta[name="csrf-token"]').content;

        const originalText = btn.innerHTML;
        btn.disabled  = true;
        btn.innerHTML = '...';

        try {
            const response = await fetch(cartUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    product_variant_id: variantId,   // ✅ corrigé
                    qte: qte,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oups',
                    text: data.message || 'Erreur lors de l\'ajout au panier.',
                    confirmButtonColor: '#dc3545',
                });
                btn.disabled  = false;
                btn.innerHTML = originalText;
                return;
            }

            document.querySelectorAll('.cart-badge').forEach(badge => {
                badge.textContent = data.cart_count;
            });

            Swal.fire({
                icon: 'success',
                title: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
            });

            btn.innerHTML = '✓ Ajouté';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled  = false;
            }, 1200);

        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Erreur réseau', confirmButtonColor: '#dc3545' });
            btn.innerHTML = originalText;
            btn.disabled  = false;
        }
    });
})();