document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.site-header');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileNav = document.querySelector('.mobile-nav');

    const closeMobileNav = () => {
        if (!mobileNav || !mobileMenuBtn) return;
        mobileNav.classList.remove('active');
        mobileMenuBtn.setAttribute('aria-expanded', 'false');
        mobileNav.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('no-scroll');
    };

    if (mobileMenuBtn && mobileNav) {
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = mobileNav.classList.toggle('active');
            mobileMenuBtn.setAttribute('aria-expanded', String(isOpen));
            mobileNav.setAttribute('aria-hidden', String(!isOpen));
            document.body.classList.toggle('no-scroll', isOpen);
        });

        mobileNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeMobileNav);
        });
    }

    window.addEventListener('scroll', () => {
        if (!header) return;
        header.classList.toggle('scrolled', window.scrollY > 10);
    });

    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    const headers = document.querySelectorAll('.accordion-header');
    headers.forEach(headerEl => {
        headerEl.addEventListener('click', () => {
            const item = headerEl.parentElement;
            const content = headerEl.nextElementSibling;
            const isOpen = item.classList.contains('open');

            document.querySelectorAll('.accordion-item.open').forEach(openItem => {
                openItem.classList.remove('open');
                const openContent = openItem.querySelector('.accordion-content');
                if (openContent) openContent.style.maxHeight = null;
            });

            if (!isOpen && content) {
                item.classList.add('open');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });

    const galleryItems = document.querySelectorAll('.gallery-item, .gallery-card');
    if (galleryItems.length > 0) {
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.innerHTML = '<span class="lightbox-close">&times;</span><img class="lightbox-content" src="" alt="">';
        document.body.appendChild(lightbox);

        const lightboxImg = lightbox.querySelector('.lightbox-content');
        const lightboxClose = lightbox.querySelector('.lightbox-close');

        galleryItems.forEach(el => {
            el.addEventListener('click', () => {
                const img = el.querySelector('img');
                const text = el.textContent.trim();

                if (img && img.src) {
                    lightboxImg.src = img.src;
                    lightboxImg.alt = img.alt || text;
                } else {
                    const svg = `
                        <svg width="800" height="600" xmlns="http://www.w3.org/2000/svg">
                            <rect width="800" height="600" fill="#1b2430"/>
                            <text x="400" y="300" font-family="Arial" font-size="24" fill="white"
                                text-anchor="middle" dominant-baseline="middle">${text}</text>
                        </svg>
                    `;
                    lightboxImg.src = 'data:image/svg+xml;base64,' + btoa(svg);
                    lightboxImg.alt = text;
                }

                lightbox.classList.add('active');
                document.body.classList.add('no-scroll');
            });
        });

        const closeLightbox = () => {
            lightbox.classList.remove('active');
            document.body.classList.remove('no-scroll');
        };

        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', e => {
            if (e.target === lightbox) closeLightbox();
        });
    }

    const revealElements = document.querySelectorAll('[data-reveal]');
    if (revealElements.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        revealElements.forEach(el => observer.observe(el));
    }

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            alert('Спасибо! Ваша заявка отправлена.');
            form.reset();
        });
    });
});
