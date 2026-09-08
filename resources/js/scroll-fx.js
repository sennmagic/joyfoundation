function clamp(value, min = 0, max = 1) {
    return Math.min(max, Math.max(min, value));
}

function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}

/**
 * Progress 0 -> 1 as an element's top edge travels from the bottom of the
 * viewport up to roughly a third of the way down from the top. Mirrors the
 * old CSS `animation-range: entry 15% cover 45%` closely enough to read
 * the same way, without needing to be pixel-identical.
 */
function revealProgress(rect, viewportHeight) {
    const start = viewportHeight;
    const end = viewportHeight * 0.35;

    return clamp((start - rect.top) / (start - end));
}

/**
 * Progress 1 at an element's dead center in the viewport, fading to 0 once
 * it's fully off-screen on either side. Drives the "smallest at the edges,
 * full size mid-scroll" effects.
 */
function centerProgress(rect, viewportHeight) {
    const elementCenter = rect.top + rect.height / 2;
    const viewportCenter = viewportHeight / 2;
    const maxDistance = viewportHeight / 2 + rect.height / 2;

    return clamp(1 - Math.abs(elementCenter - viewportCenter) / maxDistance);
}

function applyRise(el, solid) {
    const progress = easeOutCubic(revealProgress(el.getBoundingClientRect(), window.innerHeight));

    el.style.transform = `translateY(${(1 - progress) * 28}px)`;

    if (!solid) {
        el.style.opacity = String(progress);
    }
}

function applySlide(el, direction, solid) {
    const progress = easeOutCubic(revealProgress(el.getBoundingClientRect(), window.innerHeight));
    const startOffset = direction === 'left' ? -40 : 40;

    el.style.transform = `translateX(${(1 - progress) * startOffset}px)`;

    if (!solid) {
        el.style.opacity = String(progress);
    }
}

function resetSlide(el) {
    el.style.transform = '';
    el.style.opacity = '';
}

function applyScale(el) {
    const progress = centerProgress(el.getBoundingClientRect(), window.innerHeight);

    el.style.transform = `scale(${0.82 + progress * 0.18})`;
}

function applyRing(circle) {
    const progress = centerProgress(circle.getBoundingClientRect(), window.innerHeight);

    circle.style.strokeDashoffset = String(100 - progress * 100);
}

/**
 * Wave drift/bob are keyed to absolute page scroll position over a fixed
 * range (matches the old CSS `scroll(root) animation-range: 0 800px`), not
 * to the wave's own position — it lives inside overflow-hidden wrappers so
 * "how far into view is it" isn't a meaningful question for it.
 */
function applyWave(driftEl, bobEl) {
    const range = 800;
    const progress = clamp(window.scrollY / range);

    if (driftEl) {
        driftEl.style.transform = `translateX(${-progress * 6}%)`;
    }

    if (bobEl) {
        bobEl.style.transform = `translateY(${Math.sin(progress * Math.PI) * 5}px)`;
    }
}

export function initScrollFx() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        return;
    }

    const riseEls = document.querySelectorAll('.rise-in');
    const riseSolidEls = document.querySelectorAll('.rise-in-solid');
    const slideLeftEls = document.querySelectorAll('.slide-in-left');
    const slideRightEls = document.querySelectorAll('.slide-in-right');
    const slideLeftSolidEls = document.querySelectorAll('.slide-in-left-solid');
    const slideRightSolidEls = document.querySelectorAll('.slide-in-right-solid');
    const slideEls = [...slideLeftEls, ...slideRightEls, ...slideLeftSolidEls, ...slideRightSolidEls];
    const scaleEls = document.querySelectorAll('.scroll-scale');
    const ringEls = document.querySelectorAll('.ring-fill circle');
    const waveDriftEl = document.querySelector('.wave-drift');
    const waveBobEl = document.querySelector('.wave-bob');

    const hasWork =
        riseEls.length ||
        riseSolidEls.length ||
        slideEls.length ||
        scaleEls.length ||
        ringEls.length ||
        waveDriftEl ||
        waveBobEl;

    if (!hasWork) {
        return;
    }

    // Slide-in only makes sense once rows are actually side-by-side (xl:
    // 1280px, matching initiative.blade.php's xl:flex-row) — below that
    // they stack full-width, so a horizontal slide has nothing to
    // converge toward.
    const isXl = () => window.innerWidth >= 1280;

    let ticking = false;

    function update() {
        riseEls.forEach((el) => applyRise(el, false));
        riseSolidEls.forEach((el) => applyRise(el, true));

        if (isXl()) {
            slideLeftEls.forEach((el) => applySlide(el, 'left', false));
            slideRightEls.forEach((el) => applySlide(el, 'right', false));
            slideLeftSolidEls.forEach((el) => applySlide(el, 'left', true));
            slideRightSolidEls.forEach((el) => applySlide(el, 'right', true));
        } else {
            slideEls.forEach(resetSlide);
        }

        scaleEls.forEach(applyScale);
        ringEls.forEach(applyRing);
        applyWave(waveDriftEl, waveBobEl);

        ticking = false;
    }

    function onScrollOrResize() {
        if (!ticking) {
            requestAnimationFrame(update);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScrollOrResize, { passive: true });
    window.addEventListener('resize', onScrollOrResize);

    update();
}
