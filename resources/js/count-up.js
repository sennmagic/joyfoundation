function easeOutExpo(t) {
    return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
}

function formatCount(value, suffix) {
    return value.toLocaleString('en-US') + suffix;
}

function animateCount(el) {
    const to = parseInt(el.dataset.countTo, 10);
    const suffix = el.dataset.countSuffix || '';
    const duration = 1600;
    const start = performance.now();

    function frame(now) {
        const progress = Math.min((now - start) / duration, 1);
        const value = Math.round(to * easeOutExpo(progress));
        el.textContent = formatCount(value, suffix);

        if (progress < 1) {
            requestAnimationFrame(frame);
        }
    }

    requestAnimationFrame(frame);
}

export function initCountUp() {
    const targets = document.querySelectorAll('[data-count-to]');

    if (targets.length === 0) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        return;
    }

    targets.forEach((el) => {
        el.textContent = formatCount(0, '');
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 },
    );

    targets.forEach((el) => observer.observe(el));
}
