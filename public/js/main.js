(function () {
    'use strict';

    function animateShowcase() {
        var showcase = document.querySelector('.products-showcase');
        if (!showcase) {
            return;
        }

        showcase.classList.add('is-ready');

        var hero = showcase.querySelector('.showcase-hero');
        if (hero) {
            hero.classList.add('is-visible');
        }

        var cards = showcase.querySelectorAll('.product-card');
        var observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                var index = Number(entry.target.getAttribute('data-index') || '0');
                entry.target.style.transitionDelay = Math.min(index * 90, 500) + 'ms';
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            });
        }, { threshold: 0.15 });

        cards.forEach(function (card, index) {
            card.setAttribute('data-index', String(index));
            observer.observe(card);

            card.addEventListener('mousemove', function (event) {
                var rect = card.getBoundingClientRect();
                var x = event.clientX - rect.left;
                var y = event.clientY - rect.top;
                var rotateY = ((x / rect.width) - 0.5) * 6;
                var rotateX = ((0.5 - (y / rect.height))) * 5;
                card.style.transform = 'translateY(-8px) rotateX(' + rotateX.toFixed(2) + 'deg) rotateY(' + rotateY.toFixed(2) + 'deg)';
            });

            card.addEventListener('mouseleave', function () {
                card.style.transform = '';
            });
        });
    }

    function setupMobileMenu() {
        var nav = document.querySelector('#main-nav');
        var toggle = document.querySelector('.menu-toggle');

        if (!nav || !toggle) {
            return;
        }

        function setMenuOpen(open) {
            nav.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        }

        toggle.addEventListener('click', function () {
            setMenuOpen(!nav.classList.contains('is-open'));
        });

        nav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                setMenuOpen(false);
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setMenuOpen(false);
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                setMenuOpen(false);
            }
        });
    }

    function setupLoginForm() {
        var form = document.querySelector('#login-form');
        var pseudo = document.querySelector('#pseudo');
        var motDePasse = document.querySelector('#mot_de_passe');

        if (!form || !pseudo || !motDePasse) {
            return;
        }

        var status = document.querySelector('#pseudo-status');
        var submit = document.querySelector('#login-submit');
        var timer = null;

        pseudo.setAttribute('list', 'pseudo-suggestions');
        var datalist = document.createElement('datalist');
        datalist.id = 'pseudo-suggestions';
        pseudo.parentNode.appendChild(datalist);

        function setStatus(message, type) {
            status.textContent = message;
            status.className = 'field-status ' + (type || '');
        }

        pseudo.addEventListener('input', function () {
            var terme = pseudo.value.trim();
            clearTimeout(timer);

            if (terme.length < 1) {
                setStatus('', '');
                datalist.innerHTML = '';
                return;
            }

            setStatus('Recherche du pseudo...', 'checking');

            timer = setTimeout(function () {
                fetch('index.php?controleur=Utilisateur&action=rechercherPseudo&terme=' + encodeURIComponent(terme), {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Recherche impossible');
                    }
                    return response.json();
                })
                .then(function (data) {
                    datalist.innerHTML = '';

                    data.suggestions.forEach(function (suggestion) {
                        var option = document.createElement('option');
                        option.value = suggestion;
                        datalist.appendChild(option);
                    });

                    if (data.existence) {
                        setStatus('✓ Pseudo trouvé dans la base.', 'available');
                    } else {
                        setStatus('Ce pseudo n’existe pas encore.', 'unavailable');
                    }
                })
                .catch(function () {
                    setStatus('Recherche momentanément indisponible.', 'unavailable');
                });
            }, 300);
        });

        form.addEventListener('submit', function (event) {
            var pseudoValide = pseudo.value.trim().length >= 2;
            var passeValide = motDePasse.value.length >= 4;

            if (!pseudoValide || !passeValide) {
                event.preventDefault();
                setStatus(pseudoValide ? '' : 'Saisissez au moins 2 caractères.', pseudoValide ? '' : 'unavailable');
                motDePasse.setCustomValidity(passeValide ? '' : 'Saisissez au moins 4 caractères.');
                form.reportValidity();
                return;
            }

            motDePasse.setCustomValidity('');
            submit.disabled = true;
            submit.textContent = 'Connexion en cours...';
        });

        motDePasse.addEventListener('input', function () {
            motDePasse.setCustomValidity('');
        });
    }

    function setupCartQuantities() {
        var qtyInputs = document.querySelectorAll('.qty-form input[type="number"]');

        qtyInputs.forEach(function (input) {
            var timeout = null;

            function submitQuantity() {
                if (input.value === '' || Number(input.value) < 1) {
                    return;
                }
                input.form.submit();
            }

            input.addEventListener('change', submitQuantity);

            input.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    if (input.value !== '' && Number(input.value) >= 1) {
                        submitQuantity();
                    }
                }, 600);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setupLoginForm();
            setupMobileMenu();
            animateShowcase();
            setupCartQuantities();
        });
    } else {
        setupLoginForm();
        setupMobileMenu();
        animateShowcase();
        setupCartQuantities();
    }
})();
