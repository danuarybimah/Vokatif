import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initScrollAnimations() {
  // Animate cards on scroll
  const cards = document.querySelectorAll('[data-scroll-card]');

  cards.forEach((card, index) => {
    gsap.fromTo(
      card,
      {
        opacity: 0,
        y: 50,
      },
      {
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: 'power3.out',
        delay: index * 0.1,
        scrollTrigger: {
          trigger: card,
          start: 'top 85%',
          toggleActions: 'play none none none',
        },
      }
    );
  });

  // Animate section titles
  const sectionTitles = document.querySelectorAll('[data-section-title]');

  sectionTitles.forEach((title) => {
    gsap.fromTo(
      title,
      {
        opacity: 0,
        x: -30,
      },
      {
        opacity: 1,
        x: 0,
        duration: 0.6,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: title,
          start: 'top 90%',
          toggleActions: 'play none none none',
        },
      }
    );
  });

  // Animate hero section
  const hero = document.querySelector('[data-hero-section]');
  if (hero) {
    gsap.fromTo(
      hero,
      {
        opacity: 0,
        y: 30,
      },
      {
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: 'power3.out',
      }
    );
  }

  // Animate stat cards with stagger
  const statCards = document.querySelectorAll('[data-stat-card]');
  gsap.fromTo(
    statCards,
    {
      opacity: 0,
      scale: 0.95,
    },
    {
      opacity: 1,
      scale: 1,
      duration: 0.6,
      ease: 'back.out',
      stagger: {
        amount: 0.3,
      },
    }
  );

  // Hover animations for cards
  document.querySelectorAll('[data-hover-lift]').forEach((element) => {
    element.addEventListener('mouseenter', () => {
      gsap.to(element, {
        y: -8,
        boxShadow: '0 20px 60px rgba(124, 58, 237, 0.3)',
        duration: 0.3,
        ease: 'power2.out',
      });
    });

    element.addEventListener('mouseleave', () => {
      gsap.to(element, {
        y: 0,
        boxShadow: '0 0px 0px rgba(0, 0, 0, 0)',
        duration: 0.3,
        ease: 'power2.out',
      });
    });
  });

  ScrollTrigger.refresh();
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initScrollAnimations);
} else {
  initScrollAnimations();
}
