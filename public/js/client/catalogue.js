/**
 * Catalogue : scripts partagés par la boutique (catalogue.blade.php)
 * et la page catégorie (category.blade.php).
 * Gère : modale variantes, contrôles quantité, bannières, slider de
 * catégories, bandeau de catégories (page catégorie) et horloge.
 * Chaque module ne s'initialise que si ses éléments sont présents.
 */
document.addEventListener('DOMContentLoaded', () => {

    const reduceMotion   = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const scrollBehavior = reduceMotion ? 'auto' : 'smooth';

    initVariantModals();
    initBannerSlider();
    initCategoryRail();
    initCategoryStrip();
    initClock();

    /* ============================================
       MODALE VARIANTES : ouverture / fermeture
    ============================================ */
    function initVariantModals() {
        let opener = null;

        const open = (modal, trigger) => {
            opener = trigger;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            initQtyControls(modal);
            modal.querySelector('.variants-modal-close')?.focus();
        };

        const close = (modal) => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            opener?.focus();
            opener = null;
        };

        // Délégation : un seul écouteur pour toutes les modales
        document.addEventListener('click', (e) => {
            const openBtn = e.target.closest('.open-variants-modal-btn');
            if (openBtn) {
                const modal = document.getElementById('variants-modal-' + openBtn.dataset.productId);
                if (modal) open(modal, openBtn);
                return;
            }

            if (e.target.closest('.variants-modal-overlay, .variants-modal-close, .btn-modal-close')) {
                const modal = e.target.closest('.variants-modal');
                if (modal) close(modal);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            const modal = [...document.querySelectorAll('.variants-modal')]
                .find(m => m.style.display !== 'none');
            if (modal) close(modal);
        });
    }

    /* ============================================
       CONTRÔLES QUANTITÉ (+ / −)
    ============================================ */
    function initQtyControls(container) {
        container.querySelectorAll('.variant-row').forEach(row => {
            const input = row.querySelector('.qty-input');
            if (!input || input.dataset.bound === '1') return;

            input.dataset.bound = '1';
            input.id = 'qty-input-' + input.dataset.variantId; // référencé par data-qte-input

            const max     = () => parseInt(input.max, 10) || 999;
            const current = () => parseInt(input.value, 10);
            const clamp   = (v) => Math.min(Math.max(Number.isNaN(v) ? 1 : v, 1), max());

            row.querySelector('.qty-btn-minus')?.addEventListener('click', () => {
                input.value = clamp((current() || 1) - 1);
            });

            row.querySelector('.qty-btn-plus')?.addEventListener('click', () => {
                input.value = clamp((current() || 1) + 1);
            });

            // Saisie manuelle : borne entre 1 et le stock disponible
            input.addEventListener('change', () => {
                input.value = clamp(current());
            });
        });
    }

    /* ============================================
       BOUTIQUE : bannières (slider + points)
    ============================================ */
    function initBannerSlider() {
        const track   = document.getElementById('bqBannerTrack');
        const dotsBox = document.getElementById('bqBannerDots');
        if (!track || !dotsBox) return;

        const slides = [...track.children];
        let timer = null;

        const index = () => Math.round(track.scrollLeft / track.clientWidth);

        const go = (i) => {
            const n = (i + slides.length) % slides.length;
            track.scrollTo({ left: slides[n].offsetLeft, behavior: scrollBehavior });
        };

        const stop  = () => { clearInterval(timer); timer = null; };
        const start = () => {
            stop();
            if (reduceMotion || slides.length < 2) return;
            timer = setInterval(() => go(index() + 1), 5500);
        };

        const dots = slides.map((_, i) => {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'bq-dot';
            b.setAttribute('aria-label', 'Bannière ' + (i + 1));
            b.addEventListener('click', () => { go(i); start(); });
            dotsBox.appendChild(b);
            return b;
        });

        const sync = () => {
            const cur = index();
            dots.forEach((d, k) => d.classList.toggle('is-active', k === cur));
        };

        dotsBox.hidden = slides.length < 2;
        track.addEventListener('scroll', sync, { passive: true });
        ['mouseenter', 'focusin', 'touchstart'].forEach(ev =>
            track.addEventListener(ev, stop, { passive: true }));
        ['mouseleave', 'focusout'].forEach(ev => track.addEventListener(ev, start));
        document.addEventListener('visibilitychange', () => (document.hidden ? stop() : start()));

        sync();
        start();
    }

    /* ============================================
       BOUTIQUE : slider de catégories (flèches)
    ============================================ */
    function initCategoryRail() {
        const track = document.getElementById('bqCatsTrack');
        const prev  = document.getElementById('bqCatsPrev');
        const next  = document.getElementById('bqCatsNext');
        if (!track || !prev || !next) return;

        const step = () => Math.max(track.clientWidth * 0.8, 200);

        const update = () => {
            const max = track.scrollWidth - track.clientWidth;
            prev.hidden = next.hidden = max <= 2;   // rien à défiler : flèches masquées
            prev.disabled = track.scrollLeft <= 2;
            next.disabled = track.scrollLeft >= max - 2;
        };

        prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: scrollBehavior }));
        next.addEventListener('click', () => track.scrollBy({ left:  step(), behavior: scrollBehavior }));
        track.addEventListener('scroll', update, { passive: true });
        addEventListener('resize', update);

        update();
    }

    /* ============================================
       PAGE CATÉGORIE : bandeau de catégories (points)
    ============================================ */
    function initCategoryStrip() {
        const track = document.getElementById('cpStripTrack');
        const dots  = document.getElementById('cpStripDots');
        if (!track || !dots) return;

        // Centre la catégorie active
        const active = track.querySelector('.is-active');
        if (active) {
            track.scrollLeft = active.offsetLeft - (track.clientWidth - active.offsetWidth) / 2;
        }

        const sync = () => {
            const n = dots.children.length;
            if (!n) return;
            const max = track.scrollWidth - track.clientWidth;
            const i = max <= 0 ? 0 : Math.round((track.scrollLeft / max) * (n - 1));
            [...dots.children].forEach((d, k) => d.classList.toggle('is-active', k === i));
        };

        const build = () => {
            const pages = Math.max(1, Math.ceil(track.scrollWidth / track.clientWidth - 0.05));
            dots.innerHTML = '';
            dots.hidden = pages < 2;

            for (let i = 0; i < pages; i++) {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'cp-dot';
                b.setAttribute('aria-label', 'Page ' + (i + 1));
                b.addEventListener('click', () => {
                    const max = track.scrollWidth - track.clientWidth;
                    track.scrollTo({
                        left: pages < 2 ? 0 : (max * i) / (pages - 1),
                        behavior: scrollBehavior,
                    });
                });
                dots.appendChild(b);
            }
            sync();
        };

        track.addEventListener('scroll', sync, { passive: true });
        addEventListener('resize', build);
        build();
    }

    /* ============================================
       PAGE CATÉGORIE : horloge du fil d'Ariane
    ============================================ */
    function initClock() {
        const clock = document.getElementById('cpClock');
        if (!clock) return;

        const tick = () => {
            clock.textContent = new Date().toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
            });
        };

        tick();
        setInterval(tick, 15000);
    }

});