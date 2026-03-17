/**
 * Aura Theme Main JavaScript
 */

(function($) {
    'use strict';

    // Wait for DOM to be ready
    $(document).ready(function() {
        
        // Spotlight Card Effects
        $('.spotlight-card[data-spotlight]').on('mousemove', function(e) {
            var rect = this.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            
            $(this).css('--mouse-x', x + 'px');
            $(this).css('--mouse-y', y + 'px');
        });

        // Chart bars animation on scroll
        function animateChartBars() {
            $('.chart-bars').each(function() {
                var $bars = $(this).find('.chart-bar');
                var $visual = $(this).closest('.usecase-visual');
                
                if ($visual.length && isElementInViewport($visual[0])) {
                    $bars.each(function(i) {
                        var $bar = $(this);
                        setTimeout(function() {
                            $bar.css('animation-play-state', 'running');
                        }, i * 50);
                    });
                }
            });
        }
        
        // Check if element is in viewport
        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        
        // Initialize chart bars animation
        animateChartBars();
        $(window).on('scroll', animateChartBars);

        function isRecaptchaEnabled() {
            return (
                typeof aura_ajax !== 'undefined' &&
                aura_ajax.recaptcha &&
                aura_ajax.recaptcha.enabled &&
                aura_ajax.recaptcha.site_key
            );
        }

        function executeRecaptcha(action) {
            return new Promise(function(resolve, reject) {
                if (!isRecaptchaEnabled()) {
                    resolve('');
                    return;
                }

                if (typeof grecaptcha === 'undefined') {
                    reject(new Error('reCAPTCHA is unavailable'));
                    return;
                }

                grecaptcha.ready(function() {
                    grecaptcha.execute(aura_ajax.recaptcha.site_key, { action: action })
                        .then(function(token) {
                            resolve(token || '');
                        })
                        .catch(function(error) {
                            reject(error || new Error('reCAPTCHA execution failed'));
                        });
                });
            });
        }

        // Waitlist Form Submission
        (function initWaitlistForm() {
            var $form = $('#waitlist-form');
            if (!$form.length) {
                return;
            }

            var $emailInput = $('#waitlist-email');
            var $submitBtn = $('#waitlist-submit');
            var $successMsg = $('#waitlist-success');
            var $errorMsg = $('#waitlist-error');
            var waitlistAction = (aura_ajax.recaptcha && aura_ajax.recaptcha.waitlist_action) ? aura_ajax.recaptcha.waitlist_action : 'waitlist_submit';

            function showWaitlistError(message) {
                $successMsg.hide();
                $errorMsg.find('span').text(message);
                $errorMsg.css('display', 'flex');
            }

            function resetWaitlistState() {
                $submitBtn.removeClass('loading').prop('disabled', false);
                $emailInput.prop('disabled', false);
                setTimeout(function() {
                    $submitBtn.removeClass('success');
                }, 3000);
            }

            if ($emailInput.length) {
                $emailInput.on('focus', function() {
                    $(this).parent().addClass('focused');
                });

                $emailInput.on('blur', function() {
                    $(this).parent().removeClass('focused');
                });
            }

            $form.on('submit', function(e) {
                e.preventDefault();

                var email = String($emailInput.val() || '').trim();
                var isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

                $successMsg.hide();
                $errorMsg.hide();

                if (!isValidEmail) {
                    showWaitlistError('Please enter a valid email address.');
                    return;
                }

                $submitBtn.addClass('loading').prop('disabled', true);
                $emailInput.prop('disabled', true);

                executeRecaptcha(waitlistAction)
                    .then(function(token) {
                        $.ajax({
                            url: aura_ajax.ajax_url,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                action: 'aura_waitlist',
                                nonce: aura_ajax.nonce,
                                email: email,
                                recaptcha_token: token,
                                recaptcha_action: waitlistAction
                            }
                        })
                        .done(function(response) {
                            if (response && response.success) {
                                $form[0].reset();
                                $submitBtn.addClass('success');
                                $successMsg.find('span').text(response.data && response.data.message ? response.data.message : 'You have been added to the waitlist.');
                                $successMsg.css('display', 'flex');
                                return;
                            }

                            showWaitlistError(response && response.data && response.data.message ? response.data.message : 'Something went wrong. Please try again.');
                        })
                        .fail(function() {
                            showWaitlistError('Something went wrong. Please try again.');
                        })
                        .always(function() {
                            resetWaitlistState();
                        });
                    })
                    .catch(function() {
                        showWaitlistError('Security verification failed. Please try again.');
                        resetWaitlistState();
                    });
            });
        })();

        // About Contact Form reCAPTCHA
        (function initContactFormRecaptcha() {
            var $contactForm = $('.about-contact-form');
            if (!$contactForm.length || !isRecaptchaEnabled()) {
                return;
            }

            $contactForm.on('submit', function(e) {
                var formEl = this;
                var $form = $(formEl);
                var $submitBtn = $form.find('button[type="submit"]');
                var $tokenInput = $form.find('input[name="aura_recaptcha_token"]');
                var $actionInput = $form.find('input[name="aura_recaptcha_action"]');
                var contactAction = $actionInput.val() || ((aura_ajax.recaptcha && aura_ajax.recaptcha.contact_action) ? aura_ajax.recaptcha.contact_action : 'contact_submit');

                e.preventDefault();
                $submitBtn.prop('disabled', true);

                executeRecaptcha(contactAction)
                    .then(function(token) {
                        if (!token) {
                            throw new Error('No reCAPTCHA token');
                        }
                        $tokenInput.val(token);
                        $actionInput.val(contactAction);
                        formEl.submit();
                    })
                    .catch(function() {
                        $submitBtn.prop('disabled', false);
                        window.alert('Security verification failed. Please try again.');
                    });
            });
        })();

        // Smooth Scroll for Anchor Links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });

        // Navbar Scroll Effect
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 50) {
                $('.site-header').addClass('scrolled');
            } else {
                $('.site-header').removeClass('scrolled');
            }
        });

        // Animation on Scroll
        (function initScrollReveal() {
            var revealTargets = document.querySelectorAll('.animate-on-scroll, .reveal-up, .reveal-scale');
            var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!revealTargets.length) {
                return;
            }

            if (!('IntersectionObserver' in window) || prefersReducedMotion) {
                revealTargets.forEach(function(el) {
                    el.classList.add('visible');
                    el.classList.add('in-view');
                });
                return;
            }

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        entry.target.classList.add('in-view');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.14,
                rootMargin: '0px 0px -8% 0px'
            });

            revealTargets.forEach(function(el) {
                observer.observe(el);
            });
        })();

        // Agentic Lifecycle dock hover sizing
        (function initAgenticLifecycleDock() {
            var docks = document.querySelectorAll('[data-agentic-dock]');
            var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var supportsHover = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

            if (!docks.length) {
                return;
            }

            function clamp(value, min, max) {
                return Math.min(Math.max(value, min), max);
            }

            function getDockBounds() {
                var viewportWidth = window.innerWidth || document.documentElement.clientWidth || 1440;

                if (viewportWidth < 768) {
                    return {
                        min: 92,
                        max: 120,
                        distance: 180
                    };
                }

                if (viewportWidth < 1200) {
                    return {
                        min: 104,
                        max: 170,
                        distance: 240
                    };
                }

                return {
                    min: 120,
                    max: 220,
                    distance: 300
                };
            }

            function resetDock(dock) {
                var items = dock.querySelectorAll('[data-agentic-dock-item]');

                for (var i = 0; i < items.length; i++) {
                    items[i].style.removeProperty('--dock-size');
                }
            }

            if (prefersReducedMotion || !supportsHover) {
                for (var resetIndex = 0; resetIndex < docks.length; resetIndex++) {
                    resetDock(docks[resetIndex]);
                }
                return;
            }

            for (var dockIndex = 0; dockIndex < docks.length; dockIndex++) {
                (function(dock) {
                    var items = dock.querySelectorAll('[data-agentic-dock-item]');

                    if (!items.length) {
                        return;
                    }

                    dock.addEventListener('mousemove', function(e) {
                        var bounds = getDockBounds();

                        for (var i = 0; i < items.length; i++) {
                            var item = items[i];
                            var rect = item.getBoundingClientRect();
                            var distance = e.clientX - rect.left - (rect.width / 2);
                            var width = bounds.max - ((Math.min(Math.abs(distance), bounds.distance) / bounds.distance) * (bounds.max - bounds.min));

                            item.style.setProperty('--dock-size', clamp(width, bounds.min, bounds.max).toFixed(2) + 'px');
                        }
                    });

                    dock.addEventListener('mouseleave', function() {
                        resetDock(dock);
                    });
                })(docks[dockIndex]);
            }
        })();

        // Ecosystem control-plane animation
        (function initControlPlaneAnimation() {
            var containers = document.querySelectorAll('[data-control-plane-animation]');
            var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var svgNamespace = 'http://www.w3.org/2000/svg';

            if (!containers.length) {
                return;
            }

            function clamp(value, min, max) {
                return Math.min(Math.max(value, min), max);
            }

            function getHexPoints(size) {
                var points = [];
                for (var i = 0; i < 6; i++) {
                    var angle = (Math.PI / 3) * i - (Math.PI / 6);
                    points.push((size * Math.cos(angle)).toFixed(3) + ',' + (size * Math.sin(angle)).toFixed(3));
                }
                return points.join(' ');
            }

            function getConvergence(now, state) {
                if (!state.isInView) {
                    return 0;
                }

                var progressMs = now % state.loopDuration;

                if (progressMs < state.transitionDuration) {
                    var p1 = progressMs / state.transitionDuration;
                    return (1 - Math.cos(p1 * Math.PI)) / 2;
                }

                if (progressMs < state.transitionDuration + state.pauseDuration) {
                    return 1;
                }

                if (progressMs < (2 * state.transitionDuration) + state.pauseDuration) {
                    var p2 = (progressMs - (state.transitionDuration + state.pauseDuration)) / state.transitionDuration;
                    return (1 + Math.cos(p2 * Math.PI)) / 2;
                }

                return 0;
            }

            function statusLineOneOpacity(convergence) {
                if (convergence <= 0.2) {
                    return 1;
                }
                if (convergence <= 0.4) {
                    return 1 - ((convergence - 0.2) / 0.2);
                }
                return 0;
            }

            function statusLineTwoOpacity(convergence) {
                if (convergence <= 0.4 || convergence >= 0.8) {
                    return 0;
                }
                if (convergence <= 0.6) {
                    return (convergence - 0.4) / 0.2;
                }
                return 1 - ((convergence - 0.6) / 0.2);
            }

            function statusLineThreeOpacity(convergence) {
                if (convergence <= 0.8) {
                    return 0;
                }
                return (convergence - 0.8) / 0.2;
            }

            function buildHiveLines(layer, nodeData, centerX, centerY) {
                while (layer.firstChild) {
                    layer.removeChild(layer.firstChild);
                }

                for (var i = 0; i < nodeData.length; i++) {
                    var node = nodeData[i];
                    var nextNode = nodeData[(i + 1) % nodeData.length];
                    var hiveAngle = node.baseAngle;
                    var nextHiveAngle = nextNode.baseAngle;

                    var x1 = centerX + (node.hiveDistance * 1.2 * Math.cos(hiveAngle));
                    var y1 = centerY + (node.hiveDistance * 1.2 * Math.sin(hiveAngle));
                    var x2 = centerX + (nextNode.hiveDistance * 1.2 * Math.cos(nextHiveAngle));
                    var y2 = centerY + (nextNode.hiveDistance * 1.2 * Math.sin(nextHiveAngle));

                    var centerLine = document.createElementNS(svgNamespace, 'line');
                    centerLine.setAttribute('x1', String(centerX));
                    centerLine.setAttribute('y1', String(centerY));
                    centerLine.setAttribute('x2', x1.toFixed(3));
                    centerLine.setAttribute('y2', y1.toFixed(3));

                    var ringLine = document.createElementNS(svgNamespace, 'line');
                    ringLine.setAttribute('x1', x1.toFixed(3));
                    ringLine.setAttribute('y1', y1.toFixed(3));
                    ringLine.setAttribute('x2', x2.toFixed(3));
                    ringLine.setAttribute('y2', y2.toFixed(3));

                    layer.appendChild(centerLine);
                    layer.appendChild(ringLine);
                }
            }

            for (var containerIndex = 0; containerIndex < containers.length; containerIndex++) {
                (function(container) {
                    var centerX = 400;
                    var centerY = 400;
                    var baseHexSize = 64;
                    var spiralTurns = 1;
                    var orbitRadius = 320;
                    var hiveDistance = baseHexSize * 1.5;
                    var loopDuration = 24000;
                    var pauseDuration = 2000;
                    var transitionDuration = (loopDuration - (2 * pauseDuration)) / 2;

                    var now = (window.performance && typeof window.performance.now === 'function') ? window.performance.now() : Date.now();
                    var nodes = container.querySelectorAll('.eco-cp-node');
                    var hiveLinesLayer = container.querySelector('.eco-cp-hive-lines');
                    var logoScaleEl = container.querySelector('.eco-cp-logo-scale');
                    var logoWrap = container.querySelector('.eco-cp-logo-wrap');
                    var coreGlow = container.querySelector('.eco-cp-core-glow');
                    var statusLineOne = container.querySelector('.eco-cp-status-line-1');
                    var statusLineTwo = container.querySelector('.eco-cp-status-line-2');
                    var statusLineThree = container.querySelector('.eco-cp-status-line-3');

                    if (!nodes.length) {
                        return;
                    }

                    var nodeCount = nodes.length;
                    var nodeData = [];
                    var hexPathPoints = getHexPoints(baseHexSize * 0.85);

                    for (var nodeIndex = 0; nodeIndex < nodeCount; nodeIndex++) {
                        var nodeEl = nodes[nodeIndex];
                        var scaleEl = nodeEl.querySelector('.eco-cp-node-scale');
                        var polygon = nodeEl.querySelector('.eco-cp-node-hex');
                        var label = nodeEl.querySelector('.eco-cp-node-label');

                        if (polygon) {
                            polygon.setAttribute('points', hexPathPoints);
                        }

                        if (label) {
                            label.setAttribute('x', '0');
                            label.setAttribute('y', String(baseHexSize + 20));
                        }

                        nodeData.push({
                            el: nodeEl,
                            scaleEl: scaleEl,
                            glow: nodeEl.querySelector('.eco-cp-node-glow'),
                            label: label,
                            baseAngle: (nodeIndex / nodeCount) * Math.PI * 2,
                            orbitRadius: orbitRadius,
                            hiveDistance: hiveDistance
                        });
                    }

                    if (hiveLinesLayer) {
                        buildHiveLines(hiveLinesLayer, nodeData, centerX, centerY);
                    }

                    var state = {
                        isInView: false,
                        rafId: 0,
                        loopDuration: loopDuration,
                        pauseDuration: pauseDuration,
                        transitionDuration: transitionDuration
                    };

                    function applyFrame(convergence, tickNow) {
                        var c = clamp(convergence, 0, 1);
                        var logoScale = 1 + (0.1 * c);
                        var logoGlowOpacity = 0.1 + (0.5 * c);
                        var hiveOpacity = clamp((c - 0.85) / 0.15, 0, 1) * 0.4;
                        var idleRotation = (tickNow / loopDuration) * Math.PI * 2;

                        if (logoWrap && !logoWrap.hasAttribute('transform')) {
                            logoWrap.setAttribute('transform', 'translate(' + centerX + ' ' + centerY + ')');
                        }

                        if (logoScaleEl) {
                            logoScaleEl.setAttribute('transform', 'scale(' + logoScale.toFixed(4) + ')');
                        }

                        if (coreGlow) {
                            coreGlow.style.opacity = logoGlowOpacity.toFixed(3);
                        }

                        if (hiveLinesLayer) {
                            hiveLinesLayer.style.opacity = hiveOpacity.toFixed(3);
                        }

                        for (var i = 0; i < nodeData.length; i++) {
                            var node = nodeData[i];
                            var currentRadius = node.orbitRadius * (1 - c) + ((node.hiveDistance * 1.2) * c);
                            var spiralAngle = node.baseAngle + (idleRotation * (1 - c)) + (c * spiralTurns * Math.PI * 2);
                            var x = centerX + (currentRadius * Math.cos(spiralAngle));
                            var y = centerY + (currentRadius * Math.sin(spiralAngle));
                            var scale = 0.8 + (0.35 * c);
                            var glowOpacity = 0.02 + (0.18 * clamp((c - 0.7) / 0.3, 0, 1));
                            var labelOpacity = clamp((c - 0.85) / 0.15, 0, 1);

                            node.el.setAttribute('transform', 'translate(' + x.toFixed(3) + ' ' + y.toFixed(3) + ')');

                            if (node.scaleEl) {
                                node.scaleEl.setAttribute('transform', 'scale(' + scale.toFixed(4) + ')');
                            }

                            if (node.glow) {
                                node.glow.style.opacity = glowOpacity.toFixed(3);
                            }

                            if (node.label) {
                                node.label.style.opacity = labelOpacity.toFixed(3);
                            }
                        }

                        if (statusLineOne) {
                            statusLineOne.style.opacity = clamp(statusLineOneOpacity(c), 0, 1).toFixed(3);
                        }

                        if (statusLineTwo) {
                            statusLineTwo.style.opacity = clamp(statusLineTwoOpacity(c), 0, 1).toFixed(3);
                        }

                        if (statusLineThree) {
                            statusLineThree.style.opacity = clamp(statusLineThreeOpacity(c), 0, 1).toFixed(3);
                        }
                    }

                    function stopAnimation() {
                        if (state.rafId) {
                            window.cancelAnimationFrame(state.rafId);
                            state.rafId = 0;
                        }
                    }

                    function tick(tickNow) {
                        if (!state.isInView) {
                            state.rafId = 0;
                            return;
                        }

                        applyFrame(getConvergence(tickNow, state), tickNow);
                        state.rafId = window.requestAnimationFrame(tick);
                    }

                    function startAnimation() {
                        if (state.rafId) {
                            return;
                        }

                        state.rafId = window.requestAnimationFrame(tick);
                    }

                    if (prefersReducedMotion) {
                        state.isInView = true;
                        applyFrame(1, now);
                        return;
                    }

                    applyFrame(0, now);

                    if (!('IntersectionObserver' in window)) {
                        state.isInView = true;
                        startAnimation();
                        return;
                    }

                    var observer = new IntersectionObserver(function(entries) {
                        for (var entryIndex = 0; entryIndex < entries.length; entryIndex++) {
                            var entry = entries[entryIndex];

                            if (entry.target !== container) {
                                continue;
                            }

                            if (entry.isIntersecting) {
                                state.isInView = true;
                                startAnimation();
                            } else {
                                state.isInView = false;
                                stopAnimation();
                                applyFrame(0, (window.performance && typeof window.performance.now === 'function') ? window.performance.now() : Date.now());
                            }
                        }
                    }, {
                        threshold: 0.2
                    });

                    observer.observe(container);
                })(containers[containerIndex]);
            }
        })();
    });

})(jQuery);
