document.addEventListener('DOMContentLoaded', function () {

    // =====================
    // MODALE DE CONTACT
    // =====================
    const overlay  = document.getElementById('modal-overlay');
    const closeBtn = document.getElementById('modal-close');
    const openBtns = document.querySelectorAll('.open-modal');
    const burger   = document.querySelector('.burger');
    const nav      = document.querySelector('.header-nav');

    openBtns.forEach(function(btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const reference = this.getAttribute('data-reference');
            if (reference) {
                const refField = document.querySelector('.wpcf7-form input[name="your-subject"]');
                if (refField) refField.value = reference;
            }
            overlay.classList.add('is-active');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        });
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    if (burger && nav) {
        burger.addEventListener('click', function () {
            nav.classList.toggle('is-open');
            burger.setAttribute('aria-expanded', nav.classList.contains('is-open'));
        });
    }

    function closeModal() {
        overlay.classList.remove('is-active');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // =====================
    // FILTRES + CHARGER PLUS
    // =====================
    const grid        = document.getElementById('photos-grid');
    const btnMore     = document.getElementById('charger-plus');
    const selectCat   = document.getElementById('filtre-categorie');
    const selectFmt   = document.getElementById('filtre-format');
    const selectTri   = document.getElementById('filtre-tri');

    // Si on est pas sur la home, on arrête
    if (!grid) return;

    let currentPage = 1;

    // Fonction principale : appel Ajax vers l'API WordPress
   function fetchPhotos(page, append) {
    const categorie = selectCat ? selectCat.value : '';
    const format    = selectFmt ? selectFmt.value : '';
    const order     = selectTri ? selectTri.value : 'DESC';

    // On crée un objet FormData pour envoyer en POST
    const formData = new FormData();
    formData.append('action', 'get_photos'); // nom du hook dans functions.php
    formData.append('nonce',  motaAjax.nonce);
    formData.append('page',   page);
    formData.append('order',  order);
    if (categorie) formData.append('categorie', categorie);
    if (format)    formData.append('format',    format);

    // On envoie la requête à admin-ajax.php
    // motaAjax.url contient l'URL envoyée par wp_localize_script
    fetch(motaAjax.url, {
        method: 'POST',
        body:   formData,
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (!append) grid.innerHTML = '';

        if (!data.photos || !data.photos.length) {
            if (!append) grid.innerHTML = '<p style="padding:20px;font-family:var(--font-titre)">Aucune photo trouvée.</p>';
            if (btnMore) btnMore.style.display = 'none';
            return;
        }

        data.photos.forEach(function(photo) {
            const article = document.createElement('article');
            article.classList.add('photo-block');
            article.innerHTML = `
                <div class="photo-block-inner">
                    <a href="${photo.lien}" class="photo-block-img-wrap">
                        <img src="${photo.img}" alt="${photo.titre}" class="photo-block-img">
                        <div class="photo-block-overlay">
                            <a href="${photo.lien}" class="photo-icon" title="Voir les infos">👁</a>
                            <a href="${photo.img}" class="photo-icon" title="Plein écran">⛶</a>
                        </div>
                    </a>
                </div>
            `;
            grid.appendChild(article);
        });

        if (btnMore) {
            btnMore.style.display = page >= data.total_pages ? 'none' : 'block';
        }
    })
    .catch(function(err) {
        console.error('Erreur Ajax:', err);
    });
}

    // Charger plus
    if (btnMore) {
        btnMore.addEventListener('click', function() {
            currentPage++;
            fetchPhotos(currentPage, true); // true = ajouter à la suite
        });
    }

    // Filtres — recharge depuis la page 1 sans recharger la page
    function onFilterChange() {
        currentPage = 1;
        fetchPhotos(1, false); // false = remplace la grille
    }

    if (selectCat) selectCat.addEventListener('change', onFilterChange);
    if (selectFmt) selectFmt.addEventListener('change', onFilterChange);
    if (selectTri) selectTri.addEventListener('change', onFilterChange);

});