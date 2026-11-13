/**
 * Gestion du panier client : ajout via bouton .add-to-cart-btn
 * Protégé par reCAPTCHA v3 (header X-Recaptcha-Token)
 */
(function () {
    'use strict';

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn) return;

        e.preventDefault();

        const variantId  = btn.dataset.productVariantId;
        const qteInputId = btn.dataset.qteInput;
        const qte        = qteInputId
            ? (parseInt(document.getElementById(qteInputId)?.value) || 1)
            : 1;

        if (!variantId) {
            Swal.fire({ icon: 'error', title: 'Erreur', text: 'Variante introuvable.' });
            return;
        }

        const cartUrl   = btn.dataset.cartUrl || '/client/panier';
        const action    = btn.dataset.recaptchaAction || 'cart_add';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const originalText = btn.innerHTML;
        btn.disabled  = true;
        btn.innerHTML = '...';

        const reset = () => {
            btn.disabled  = false;
            btn.innerHTML = originalText;
        };

        try {
            const recaptchaToken = await window.getRecaptchaToken(action);

            const response = await fetch(cartUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Recaptcha-Token': recaptchaToken,
                },
                body: JSON.stringify({
                    product_variant_id: variantId,
                    qte,
                }),
            });

            // Évite le crash si la réponse n'est pas du JSON (419, 500 HTML...)
            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                Swal.fire({
                    icon: 'error',
                    title: response.status === 403 ? 'Vérification échouée' : 'Oups',
                    text: data.message || data.error || 'Erreur lors de l\'ajout au panier.',
                    confirmButtonColor: '#dc3545',
                });
                reset();
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
            setTimeout(reset, 1200);

        } catch (err) {
            const isCaptcha = err.message === 'recaptcha_unavailable';
            Swal.fire({
                icon: 'error',
                title: isCaptcha ? 'reCAPTCHA indisponible' : 'Erreur réseau',
                text: isCaptcha
                    ? 'Désactivez votre bloqueur de publicités ou rechargez la page.'
                    : undefined,
                confirmButtonColor: '#dc3545',
            });
            reset();
        }
    });
})();