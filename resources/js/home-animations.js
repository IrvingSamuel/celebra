import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

function gridStaggerReveal(scope, gridSelector, itemSelector, options = {}) {
    const { y = 16, stagger = 0.07, duration = 0.45 } = options;

    scope.querySelectorAll(gridSelector).forEach((grid) => {
        const items = grid.querySelectorAll(itemSelector);
        if (!items.length) {
            return;
        }

        gsap.set(items, { autoAlpha: 0, y });

        gsap.to(items, {
            autoAlpha: 1,
            y: 0,
            duration,
            stagger,
            ease: 'power2.out',
            clearProps: 'transform',
            scrollTrigger: {
                trigger: grid,
                start: 'top 82%',
                once: true,
            },
        });
    });
}

function sectionHeaderReveal(scope) {
    scope.querySelectorAll('[data-section-header]').forEach((el) => {
        gsap.set(el, { autoAlpha: 0, y: 16 });

        gsap.to(el, {
            autoAlpha: 1,
            y: 0,
            duration: 0.5,
            ease: 'power2.out',
            clearProps: 'transform',
            scrollTrigger: {
                trigger: el,
                start: 'top 85%',
                once: true,
            },
        });
    });
}

function setupHeroTimeline(scope) {
    const title = scope.querySelector('[data-hero-title]');
    const subtitle = scope.querySelector('[data-hero-subtitle]');
    const search = scope.querySelector('[data-hero-search]');
    const stats = scope.querySelectorAll('[data-hero-stat]');
    const carousel = scope.querySelector('[data-hero-carousel]');

    if (!title) {
        return;
    }

    gsap.set(title, { autoAlpha: 0, y: 24 });
    gsap.set(subtitle, { autoAlpha: 0, y: 16 });
    gsap.set(search, { autoAlpha: 0, y: 16 });
    gsap.set(stats, { autoAlpha: 0, y: 12 });
    if (carousel) {
        gsap.set(carousel, { autoAlpha: 0, x: 32 });
    }

    const tl = gsap.timeline({ defaults: { ease: 'power2.out', duration: 0.65 } });

    tl.to(title, { autoAlpha: 1, y: 0, clearProps: 'transform' })
        .to(subtitle, { autoAlpha: 1, y: 0, clearProps: 'transform' }, '-=0.45')
        .to(search, { autoAlpha: 1, y: 0, clearProps: 'transform' }, '-=0.45')
        .to(stats, { autoAlpha: 1, y: 0, stagger: 0.07, clearProps: 'transform' }, '-=0.35');

    if (carousel) {
        tl.to(carousel, { autoAlpha: 1, x: 0, clearProps: 'transform' }, '-=0.45');
    }
}

function setupScrollAnimations(scope) {
    sectionHeaderReveal(scope);

    gridStaggerReveal(scope, '[data-event-grid]', '[data-event-card]');
    gridStaggerReveal(scope, '[data-step-grid]', '[data-step-card]', { stagger: 0.12 });
    gridStaggerReveal(scope, '[data-service-grid]', '[data-service-card]');
    gridStaggerReveal(scope, '[data-venue-grid]', '[data-venue-card]', { stagger: 0.08 });

    const cta = scope.querySelector('[data-cta-content]');
    if (cta) {
        gsap.set(cta, { autoAlpha: 0, scale: 0.98 });

        gsap.to(cta, {
            autoAlpha: 1,
            scale: 1,
            duration: 0.5,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: cta,
                start: 'top 85%',
                once: true,
            },
        });
    }
}

export function initHomeAnimations() {
    const homePage = document.querySelector('[data-home-page]');
    if (!homePage) {
        return;
    }

    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: reduce)', () => {
        // No animations — content stays visible as rendered.
    });

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const ctx = gsap.context(() => {
            setupHeroTimeline(homePage);
            setupScrollAnimations(homePage);
        }, homePage);

        return () => ctx.revert();
    });
}
