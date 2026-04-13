document.addEventListener('DOMContentLoaded', function () {

    const overlay  = document.getElementById('modal-overlay');
    const closeBtn = document.getElementById('modal-close');
    const openBtns = document.querySelectorAll('.open-modal');
    const burger   = document.querySelector('.burger');
    const nav      = document.querySelector('.header-nav');

    // --- Ouvrir la modale ---
    openBtns.forEach(function(btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // empêche le lien de naviguer vers une autre page
            overlay.classList.add('is-active');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        });
    });

    // --- Fermer via le bouton ---
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // --- Fermer en cliquant sur l'overlay ---
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });
    }

    // --- Fermer avec Échap ---
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    // --- Menu burger mobile ---
    if (burger && nav) {
        burger.addEventListener('click', function () {
            nav.classList.toggle('is-open');
            const isOpen = nav.classList.contains('is-open');
            burger.setAttribute('aria-expanded', isOpen);
        });
    }

    function closeModal() {
        overlay.classList.remove('is-active');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

});
