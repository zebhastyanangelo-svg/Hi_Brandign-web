document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const primaryNav = document.querySelector('#primary-nav');
    const introLoader = document.querySelector('#intro-loader');
    const isCheckoutPage = document.querySelector('.checkout-page') !== null;

    if (isCheckoutPage) {
        introLoader?.remove();
    }

    if (introLoader && !isCheckoutPage) {
        const introKey = 'hi-branding-intro-seen';
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let hasSeenIntro = false;

        try {
            hasSeenIntro = window.sessionStorage.getItem(introKey) === 'true';
        } catch {
            hasSeenIntro = false;
        }

        if (hasSeenIntro || reducedMotion) {
            introLoader.remove();
        } else {
            introLoader.classList.add('is-active');

            const introVideo = introLoader.querySelector('#intro-video');

            const dismissIntro = () => {
                if (!introLoader.parentNode) return;
                introLoader.classList.add('is-closing');
                document.documentElement.classList.add('intro-revealing');

                try {
                    window.sessionStorage.setItem(introKey, 'true');
                } catch {
                    // Storage can be disabled; the loader still completes normally.
                }

                window.setTimeout(() => introLoader.remove(), 750);
            };

            if (introVideo) {
                introVideo.play().catch(() => {
                    // Autoplay fallback if blocked
                });
                introVideo.addEventListener('ended', dismissIntro);
            } else {
                window.setTimeout(dismissIntro, 1800);
            }

            const handleSkip = () => {
                dismissIntro();
                window.removeEventListener('click', handleSkip);
                window.removeEventListener('keydown', handleSkip);
            };

            window.addEventListener('click', handleSkip, { once: true });
            window.addEventListener('keydown', handleSkip, { once: true });
        }
    }

    const storyHero = document.querySelector('[data-story-hero]');

    if (storyHero && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const storyImage = storyHero.querySelector('.story-hero-image img');
        const storyCopy = storyHero.querySelector('.story-hero-inner');
        const spaceFinder = storyHero.querySelector('.story-space-finder');
        let scrollFrame = 0;

        const updateStoryProgress = () => {
            const bounds = storyHero.getBoundingClientRect();
            const progress = Math.max(0, Math.min(1, -bounds.top / (bounds.height * 0.82)));

            if (storyImage) {
                storyImage.style.transform = `scale(${1 + progress * 0.1})`;
            }

            if (storyCopy) {
                storyCopy.style.opacity = String(1 - progress * 0.6);
                storyCopy.style.transform = `translate3d(0, ${progress * -42}px, 0)`;
            }

            if (spaceFinder) {
                spaceFinder.style.transform = `translate3d(0, ${progress * -16}px, 0)`;
            }

            scrollFrame = 0;
        };

        const requestStoryUpdate = () => {
            if (scrollFrame === 0) {
                scrollFrame = window.requestAnimationFrame(updateStoryProgress);
            }
        };

        window.addEventListener('scroll', requestStoryUpdate, { passive: true });
        window.addEventListener('resize', requestStoryUpdate, { passive: true });
        updateStoryProgress();
    }

    const scrollRevealSelector = [
        'main h1',
        'main h2',
        '.space-card',
        '.membership-card',
        '.plan-card',
        '.membership-callout',
        '.value-grid article',
        '.allies-logos span',
        '.metrics-grid > div',
        '.member-card',
        '.location-feature',
        '.amenities-grid article',
        '.appointment-row',
        '.history-row',
        '.advisor-card',
        '.office-note',
        '.featured-event',
        '.event-row',
        '.event-subscribe',
        '.story-space-finder',
        '.confirmation-card',
        '.kpi-card',
        '.directivo-module',
        '.membership-admin-card',
        '.admin-flash',
    ].join(',');
    const scrollRevealTargets = document.querySelectorAll(scrollRevealSelector);
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!isCheckoutPage && !prefersReducedMotion && scrollRevealTargets.length > 0) {
        scrollRevealTargets.forEach((element) => {
            element.classList.add('scroll-reveal-target');

            if (!element.classList.contains('animate__animated')) {
                element.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });

        if ('IntersectionObserver' in window) {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

            scrollRevealTargets.forEach((element) => revealObserver.observe(element));
        } else {
            scrollRevealTargets.forEach((element) => element.classList.add('is-visible'));
        }
    }

    menuToggle?.addEventListener('click', () => {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isOpen));
        menuToggle.setAttribute('aria-label', isOpen ? 'Abrir menú' : 'Cerrar menú');
        primaryNav?.classList.toggle('is-open', !isOpen);
    });

    primaryNav?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            menuToggle?.setAttribute('aria-expanded', 'false');
            menuToggle?.setAttribute('aria-label', 'Abrir menú');
            primaryNav.classList.remove('is-open');
        });
    });

    const bookingDialog = document.querySelector('#booking-dialog');

    document.querySelectorAll('[data-open-booking]').forEach((button) => {
        button.addEventListener('click', () => bookingDialog?.showModal());
    });

    document.querySelector('[data-close-booking]')?.addEventListener('click', () => bookingDialog?.close());
    bookingDialog?.addEventListener('click', (event) => {
        if (event.target === bookingDialog) {
            bookingDialog.close();
        }
    });

    if (bookingDialog?.querySelector('.field-error')) {
        bookingDialog.showModal();
    }

    const memberFilterButtons = document.querySelectorAll('[data-member-filter]');
    const memberCards = document.querySelectorAll('[data-member-category]');
    const memberSearch = document.querySelector('[data-member-search]');
    const filterEmpty = document.querySelector('#filter-empty');
    let selectedMemberCategory = 'all';

    const updateMembers = () => {
        let visibleCount = 0;
        const searchTerm = memberSearch?.value.trim().toLocaleLowerCase() ?? '';

        memberCards.forEach((card) => {
            const categoryMatches = selectedMemberCategory === 'all' || card.dataset.memberCategory === selectedMemberCategory;
            const textMatches = card.dataset.memberSearch?.includes(searchTerm) ?? true;
            const shouldShow = categoryMatches && textMatches;

            card.hidden = !shouldShow;
            visibleCount += Number(shouldShow);
        });

        if (filterEmpty) {
            filterEmpty.hidden = visibleCount > 0;
        }
    };

    memberFilterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            selectedMemberCategory = button.dataset.memberFilter ?? 'all';

            memberFilterButtons.forEach((filterButton) => {
                const isActive = filterButton === button;
                filterButton.classList.toggle('is-active', isActive);
                filterButton.setAttribute('aria-pressed', String(isActive));
            });

            updateMembers();
        });
    });

    memberSearch?.addEventListener('input', updateMembers);

    const spaceFilterButtons = document.querySelectorAll('[data-space-filter]');
    const spaceCards = document.querySelectorAll('[data-space-card]');
    const spaceSearch = document.querySelector('[data-space-search]');
    const spaceEmpty = document.querySelector('[data-space-empty]');
    let selectedSpaceCategory = 'all';

    const updateSpaces = () => {
        let visibleCount = 0;
        const searchTerm = spaceSearch?.value.trim().toLocaleLowerCase() ?? '';

        spaceCards.forEach((card) => {
            const categoryMatches = selectedSpaceCategory === 'all' || card.dataset.spaceCategory === selectedSpaceCategory;
            const textMatches = card.dataset.spaceName?.includes(searchTerm) ?? true;
            const shouldShow = categoryMatches && textMatches;

            card.hidden = !shouldShow;
            visibleCount += Number(shouldShow);
        });

        if (spaceEmpty) {
            spaceEmpty.hidden = visibleCount > 0;
        }
    };

    spaceFilterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            selectedSpaceCategory = button.dataset.spaceFilter ?? 'all';

            spaceFilterButtons.forEach((filterButton) => {
                const isActive = filterButton === button;
                filterButton.classList.toggle('is-active', isActive);
                filterButton.setAttribute('aria-pressed', String(isActive));
            });

            updateSpaces();
        });
    });

    spaceSearch?.addEventListener('input', updateSpaces);

    // Admin dropdown - solo para usuarios autenticados
    const adminDropdownToggle = document.getElementById('adminDropdownToggle');
    const adminDropdownMenu = document.getElementById('adminDropdownMenu');

    if (adminDropdownToggle && adminDropdownMenu) {
        adminDropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = adminDropdownToggle.getAttribute('aria-expanded') === 'true';
            adminDropdownToggle.setAttribute('aria-expanded', String(!isOpen));
            adminDropdownMenu.hidden = isOpen;
        });

        document.addEventListener('click', (e) => {
            if (!adminDropdownToggle.contains(e.target) && !adminDropdownMenu.contains(e.target)) {
                adminDropdownToggle.setAttribute('aria-expanded', 'false');
                adminDropdownMenu.hidden = true;
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !adminDropdownMenu.hidden) {
                adminDropdownToggle.setAttribute('aria-expanded', 'false');
                adminDropdownMenu.hidden = true;
                adminDropdownToggle.focus();
            }
        });
    }

    // Modal de acceso privado (avatar sin sesión activa)
    const adminLoginModal = document.getElementById('admin-login-modal');

    document.querySelectorAll('[data-open-admin-login]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            if (!adminLoginModal || typeof adminLoginModal.showModal !== 'function') {
                return;
            }

            event.preventDefault();

            if (!adminLoginModal.open) {
                adminLoginModal.showModal();
            }
        });
    });

    adminLoginModal?.querySelector('[data-close-admin-login]')?.addEventListener('click', () => adminLoginModal.close());

    adminLoginModal?.addEventListener('click', (event) => {
        if (event.target === adminLoginModal) {
            adminLoginModal.close();
        }
    });

    if (adminLoginModal?.querySelector('.field-error')) {
        adminLoginModal.showModal();
    }
});