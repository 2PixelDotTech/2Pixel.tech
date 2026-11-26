/**
 * Two Pixel: site interactions (vanilla JavaScript, no dependencies).
 *
 * 1. Mobile navigation (toggle, Escape to close, close on link click)
 * 2. Header shadow on scroll + highlight the nav link for the section in view
 * 3. Scroll-reveal animations
 * 4. CTA buttons prefill the contact form (topic / preferred contact method)
 * 5. Contact form: client-side validation, Cloudflare Turnstile, submit with fetch to the Worker
 */
(function () {
    'use strict';

    document.documentElement.classList.add('js');

    document.addEventListener('DOMContentLoaded', function () {
        initNav();
        initHeaderAndActiveLinks();
        initReveal();
        initContactForm();
    });

    /* ---------- 1. Mobile navigation ---------- */
    function initNav() {
        var toggle = document.querySelector('[data-nav-toggle]');
        var nav = document.querySelector('[data-nav]');
        if (!toggle || !nav) return;

        function setOpen(open) {
            toggle.setAttribute('aria-expanded', String(open));
            nav.classList.toggle('is-open', open);
            document.body.style.overflow = open ? 'hidden' : '';
        }

        toggle.addEventListener('click', function () {
            setOpen(toggle.getAttribute('aria-expanded') !== 'true');
        });
        nav.addEventListener('click', function (e) {
            if (e.target.closest('a')) setOpen(false);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('is-open')) {
                setOpen(false);
                toggle.focus();
            }
        });
        // Close the menu if the window is resized up to desktop width.
        window.matchMedia('(min-width: 1024px)').addEventListener('change', function (mq) {
            if (mq.matches) setOpen(false);
        });
    }

    /* ---------- 2. Header state + active section ---------- */
    function initHeaderAndActiveLinks() {
        var header = document.querySelector('[data-header]');
        var onScroll = function () {
            if (header) header.classList.toggle('is-scrolled', window.scrollY > 8);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });

        var links = document.querySelectorAll('[data-nav-link]');
        if (!('IntersectionObserver' in window) || !links.length) return;

        var byId = {};
        links.forEach(function (a) { byId[a.getAttribute('data-nav-link')] = a; });
        // Watch every section, so that sections without a nav link (hero, team…) clear the highlight.
        var sections = document.querySelectorAll('main > section');
        if (!sections.length) return;
        // The team section belongs to "About".
        if (byId.about) byId.team = byId.about;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                links.forEach(function (a) { a.classList.remove('is-active'); a.removeAttribute('aria-current'); });
                var active = byId[entry.target.id];
                if (active) { active.classList.add('is-active'); active.setAttribute('aria-current', 'location'); }
            });
        }, { rootMargin: '-45% 0px -50% 0px' });
        sections.forEach(function (s) { observer.observe(s); });
    }

    /* ---------- 3. Scroll reveal ---------- */
    function initReveal() {
        var items = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            items.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
        items.forEach(function (el) { observer.observe(el); });
    }

    /* ---------- 4 & 5. Contact form ---------- */
    function initContactForm() {
        var form = document.querySelector('[data-contact-form]');

        // CTA links on other pages (e.g. "/#contact") still work as plain links.
        if (!form) return;

        var interestInput = form.querySelector('[data-interest-input]');
        var interestNote = form.querySelector('[data-interest-note]');
        var interestLabel = form.querySelector('[data-interest-label]');
        var methodSelect = form.querySelector('[data-method-select]');
        var statusBox = form.querySelector('[data-form-status]');
        var submitBtn = form.querySelector('[data-submit]');
        var submitLabel = form.querySelector('[data-submit-label]');

        function setInterest(text) {
            interestInput.value = text || '';
            interestLabel.textContent = text || '';
            interestNote.hidden = !text;
        }

        // "Discuss this service" / industry cards → remember which topic was clicked.
        document.querySelectorAll('[data-interest]').forEach(function (link) {
            link.addEventListener('click', function () {
                setInterest(link.getAttribute('data-interest'));
            });
        });
        form.querySelector('[data-interest-clear]').addEventListener('click', function () { setInterest(''); });

        // "Book a Call" → prefer a video call; "Tell us about your process" → focus the textarea.
        document.querySelectorAll('[data-intent]').forEach(function (link) {
            link.addEventListener('click', function () {
                var intent = link.getAttribute('data-intent');
                if (intent === 'call' && methodSelect) {
                    methodSelect.value = 'video';
                    setInterest(interestInput.value || 'Discovery call');
                }
                if (intent === 'process') {
                    window.setTimeout(function () {
                        var ta = form.querySelector('#f-process');
                        if (ta) ta.focus({ preventScroll: true });
                    }, 500);
                }
            });
        });

        var startedInput = form.querySelector('[data-form-started]');
        function markStarted() { if (startedInput) startedInput.value = String(Math.floor(Date.now() / 1000)); }
        markStarted();

        /* --- Cloudflare Turnstile (spam check) ---
         * Loaded only when the form comes near the screen, so visitors who never reach
         * the form don't contact Cloudflare's challenge service at all. */
        var tsBox = form.querySelector('[data-turnstile]');
        var tsWidget = null;
        var tsRequested = false;
        function loadTurnstile() {
            if (tsRequested || !tsBox) return;
            tsRequested = true;
            window.onTurnstileLoad = function () {
                tsWidget = window.turnstile.render(tsBox, {
                    sitekey: tsBox.getAttribute('data-sitekey'),
                    theme: 'light',
                    size: 'flexible'
                });
            };
            var script = document.createElement('script');
            script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onTurnstileLoad&render=explicit';
            script.async = true;
            document.head.appendChild(script);
        }
        function resetTurnstile() {
            if (window.turnstile && tsWidget !== null) window.turnstile.reset(tsWidget);
        }
        if (tsBox) {
            if ('IntersectionObserver' in window) {
                var tsObserver = new IntersectionObserver(function (entries) {
                    if (entries.some(function (en) { return en.isIntersecting; })) {
                        loadTurnstile();
                        tsObserver.disconnect();
                    }
                }, { rootMargin: '600px 0px' });
                tsObserver.observe(form);
            } else {
                loadTurnstile();
            }
            form.addEventListener('focusin', loadTurnstile);
        }

        /* --- Validation --- */
        var rules = {
            full_name: function (v) { return v.trim().length >= 2 || 'Please enter your name.'; },
            email: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()) || 'Please enter a valid email address.'; },
            phone: function (v) {
                var m = methodSelect ? methodSelect.value : 'email';
                if (!v.trim()) return (m === 'phone' || m === 'whatsapp') ? 'Please add a phone number so we can contact you that way.' : true;
                return /^\+?[0-9 ()\-]{7,20}$/.test(v.trim()) || 'Please enter a valid phone number.';
            },
            process: function (v) { return v.trim().length >= 10 || 'Please tell us a little about the process (at least 10 characters).'; }
        };

        function showError(name, message) {
            var field = form.elements[name];
            var box = document.getElementById('e-' + name);
            if (!field || !box) return;
            if (message) {
                field.setAttribute('aria-invalid', 'true');
                box.textContent = message;
            } else {
                field.removeAttribute('aria-invalid');
                box.textContent = '';
            }
        }

        function validateField(name) {
            var result = rules[name](form.elements[name].value);
            showError(name, result === true ? '' : result);
            return result === true;
        }

        Object.keys(rules).forEach(function (name) {
            var field = form.elements[name];
            if (!field) return;
            field.addEventListener('blur', function () { if (field.value) validateField(name); });
            field.addEventListener('input', function () {
                if (field.getAttribute('aria-invalid') === 'true') validateField(name);
            });
        });
        if (methodSelect) {
            methodSelect.addEventListener('change', function () { validateField('phone'); });
        }

        function showStatus(type, message) {
            statusBox.className = 'form-status is-' + type;
            statusBox.textContent = message; // textContent: never inject HTML
            statusBox.hidden = false;
            statusBox.focus();
        }

        form.addEventListener('submit', function (e) {
            var firstInvalid = null;
            Object.keys(rules).forEach(function (name) {
                if (!validateField(name) && !firstInvalid) firstInvalid = form.elements[name];
            });
            if (firstInvalid) {
                e.preventDefault();
                firstInvalid.focus();
                return;
            }
            e.preventDefault();
            if (!window.fetch || !window.FormData) {
                showStatus('error', 'Your browser is too old to send this form. Please email us instead.');
                return;
            }
            var tsField = form.elements['cf-turnstile-response'];
            if (tsBox && (!tsField || !tsField.value)) {
                loadTurnstile();
                showStatus('error', 'Please wait a moment for the security check to finish, then try again.');
                return;
            }

            submitBtn.disabled = true;
            submitLabel.textContent = 'Sending…';

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' }
            })
                .then(function (res) {
                    return res.json().catch(function () { return { ok: false, message: 'Unexpected response from the server.' }; });
                })
                .then(function (data) {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(function (name) { showError(name, data.errors[name]); });
                    }
                    if (data.ok) {
                        form.reset();
                        setInterest('');
                        markStarted();
                        showStatus('success', data.message);
                    } else {
                        showStatus('error', data.message || 'Something went wrong. Please try again.');
                    }
                })
                .catch(function () {
                    showStatus('error', 'We could not reach the server. Please check your connection and try again.');
                })
                .finally(function () {
                    resetTurnstile(); // each Turnstile token can only be used once
                    submitBtn.disabled = false;
                    submitLabel.textContent = 'Start the Conversation';
                });
        });
    }
})();
