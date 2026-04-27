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
    const grid      = document.getElementById('gallery-grid');
    const btnMore   = document.getElementById('load-more');
    const selectCat = document.getElementById('filter-category');
    const selectFmt = document.getElementById('filter-format');
    const selectTri = document.getElementById('filter-sort');

    if (grid) {

        let currentPage = 1;

        function fetchPhotos(page, append) {
            const categorie = selectCat ? selectCat.value : '';
            const format    = selectFmt ? selectFmt.value : '';
            const order     = selectTri ? selectTri.value : 'DESC';

            const formData = new FormData();
            formData.append('action', 'get_photos');
            formData.append('nonce',  motaAjax.nonce);
            formData.append('page',   page);
            formData.append('order',  order);
            if (categorie) formData.append('categorie', categorie);
            if (format)    formData.append('format',    format);

            fetch(motaAjax.url, { method: 'POST', body: formData })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (!append) grid.innerHTML = '';

                    if (!data.photos || !data.photos.length) {
                        if (!append) grid.innerHTML = '<p style="padding:20px;font-family:var(--font-title)">Aucune photo trouvée.</p>';
                        if (btnMore) btnMore.style.display = 'none';
                        return;
                    }

                    data.photos.forEach(function(photo) {
                        const article = document.createElement('article');
                        article.classList.add('photo-block');
                        article.innerHTML = `
                            <div class="photo-block-inner">
                                <a href="${photo.lien}" class="photo-block-link">
                                    <img src="${photo.img}" alt="${photo.titre}" class="photo-block-img">
                                    <div class="photo-block-overlay">
                                        <a href="${photo.lien}" class="photo-icon" title="Voir les infos">👁</a>
                                        <a href="${photo.img}"
                                           class="photo-icon photo-lightbox"
                                           data-ref="${photo.titre}"
                                           data-cat=""
                                           title="Plein écran">⛶</a>
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

        if (btnMore) {
            btnMore.addEventListener('click', function() {
                currentPage++;
                fetchPhotos(currentPage, true);
            });
        }

        function onFilterChange() {
            currentPage = 1;
            fetchPhotos(1, false);
        }

        if (selectCat) selectCat.addEventListener('change', onFilterChange);
        if (selectFmt) selectFmt.addEventListener('change', onFilterChange);
        if (selectTri) selectTri.addEventListener('change', onFilterChange);
    }

    // =====================
    // LIGHTBOX
    // =====================
    const lightbox      = document.getElementById('lightbox-overlay');
    const lightboxImg   = document.getElementById('lightbox-img');
    const lightboxRef   = document.getElementById('lightbox-ref');
    const lightboxCat   = document.getElementById('lightbox-cat');
    const lightboxClose = document.getElementById('lightbox-close');
    const lightboxPrev  = document.getElementById('lightbox-prev');
    const lightboxNext  = document.getElementById('lightbox-next');

    let lightboxPhotos = [];
    let lightboxIndex  = 0;

    function buildLightboxPhotos() {
        lightboxPhotos = Array.from(document.querySelectorAll('.photo-lightbox'));
    }

    function openLightbox(index) {
        if (!lightboxPhotos[index]) return;

        lightboxIndex = index;

        const btn = lightboxPhotos[index];
        const img  = btn.getAttribute('href') || '';
        const ref  = btn.getAttribute('data-ref') || 'Référence';
        const cat  = btn.getAttribute('data-cat') || 'Catégorie';

        lightboxImg.src             = img;
        lightboxImg.alt             = ref;
        lightboxRef.textContent     = ref;
        lightboxCat.textContent     = cat;

        lightbox.classList.add('is-active');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        if (lightboxPrev) lightboxPrev.style.display = index > 0 ? 'flex' : 'none';
        if (lightboxNext) lightboxNext.style.display = index < lightboxPhotos.length - 1 ? 'flex' : 'none';
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove('is-active');
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        lightboxImg.src = '';
    }

    // Délégation d'événement — capte aussi les photos chargées en Ajax
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.photo-lightbox');
        if (!btn) return;
        e.preventDefault();
        buildLightboxPhotos();
        const index = lightboxPhotos.indexOf(btn);
        openLightbox(index);
    });

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);

    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) closeLightbox();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (!lightbox || !lightbox.classList.contains('is-active')) return;
        if (e.key === 'Escape')     closeLightbox();
        if (e.key === 'ArrowLeft')  openLightbox(lightboxIndex - 1);
        if (e.key === 'ArrowRight') openLightbox(lightboxIndex + 1);
    });

    if (lightboxPrev) lightboxPrev.addEventListener('click', function() { openLightbox(lightboxIndex - 1); });
    if (lightboxNext) lightboxNext.addEventListener('click', function() { openLightbox(lightboxIndex + 1); });

}); // fin DOMContentLoaded