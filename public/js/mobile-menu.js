// Gestion du menu mobile hamburger avec overlay
document.addEventListener('DOMContentLoaded', function() {
    // Créer le bouton hamburger s'il n'existe pas
    if (!document.querySelector('.menu-toggle')) {
        const menuToggle = document.createElement('button');
        menuToggle.className = 'menu-toggle';
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        menuToggle.setAttribute('aria-label', 'Toggle menu');
        document.body.appendChild(menuToggle);
    }

    // Créer l'overlay s'il n'existe pas
    if (!document.querySelector('.menu-overlay')) {
        const overlay = document.createElement('div');
        overlay.className = 'menu-overlay';
        document.body.appendChild(overlay);
    }

    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.menu-overlay');

    // Fonction pour ouvrir le menu
    function openMenu() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        menuToggle.style.opacity = '0';
        menuToggle.style.pointerEvents = 'none';
        document.body.style.overflow = 'hidden'; // Empêcher le scroll du body
    }

    // Fonction pour fermer le menu
    function closeMenu() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        menuToggle.style.opacity = '1';
        menuToggle.style.pointerEvents = 'auto';
        document.body.style.overflow = ''; // Rétablir le scroll
    }

    let isScrollHidden = false;

    function setToggleHidden(hidden) {
        if (hidden === isScrollHidden) {
            return;
        }
        isScrollHidden = hidden;
        menuToggle.style.opacity = hidden ? '0' : '1';
        menuToggle.style.pointerEvents = hidden ? 'none' : 'auto';
    }

    function onScroll() {
        if (window.innerWidth > 768) {
            setToggleHidden(true);
            return;
        }
        if (sidebar.classList.contains('active')) {
            setToggleHidden(true);
            return;
        }
        setToggleHidden(window.scrollY > 0);
    }

    let scrollTicking = false;
    window.addEventListener('scroll', function() {
        if (!scrollTicking) {
            scrollTicking = true;
            window.requestAnimationFrame(function() {
                onScroll();
                scrollTicking = false;
            });
        }
    }, { passive: true });

    // Toggle du menu au clic sur le bouton
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (sidebar.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Fermer le menu en cliquant sur l'overlay
        overlay.addEventListener('click', function() {
            closeMenu();
        });

        // Fermer le menu au clic sur un lien
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeMenu();
                }
            });
        });

        // Gérer le resize de la fenêtre
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    closeMenu();
                }
                onScroll();
            }, 250);
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeMenu();
            }
        });
    }

    onScroll();
});
