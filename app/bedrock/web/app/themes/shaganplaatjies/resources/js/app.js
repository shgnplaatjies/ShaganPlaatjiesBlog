/**
 * Shagan Plaatjies Theme
 * Main JavaScript entry point
 */

// Import Alpine.js for interactive components (optional)
// import Alpine from 'alpinejs';

/**
 * Theme Initialization
 *
 * Initialize theme functionality when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
  initializeTheme();
});

/**
 * Initialize Theme
 *
 * Main initialization function for theme functionality
 */
function initializeTheme() {
  console.log('Shagan Plaatjies theme initialized');

  // Initialize interactive components
  initializeMobileMenu();
  initializeScrollEffects();
  initializeAccessibility();
  initializeFormBehavior();
}

/**
 * Initialize Mobile Menu
 *
 * Handle mobile menu toggle and interactions
 */
function initializeMobileMenu() {
  const menuButton = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');

  if (!menuButton || !mobileMenu) {
    return;
  }

  menuButton.addEventListener('click', (e) => {
    e.preventDefault();
    mobileMenu.classList.toggle('hidden');
    menuButton.setAttribute('aria-expanded', mobileMenu.classList.contains('hidden') ? 'false' : 'true');
  });

  // Close menu when clicking outside
  document.addEventListener('click', (e) => {
    if (!menuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
      mobileMenu.classList.add('hidden');
      menuButton.setAttribute('aria-expanded', 'false');
    }
  });

  // Close menu on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      mobileMenu.classList.add('hidden');
      menuButton.setAttribute('aria-expanded', 'false');
    }
  });
}

/**
 * Initialize Scroll Effects
 *
 * Handle scroll-based animations and effects
 */
function initializeScrollEffects() {
  // Intersection Observer for fade-in animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px',
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe all elements with data-animate attribute
  document.querySelectorAll('[data-animate]').forEach((el) => {
    observer.observe(el);
  });
}

/**
 * Initialize Accessibility Features
 *
 * Enhance accessibility for keyboard navigation and screen readers
 */
function initializeAccessibility() {
  // Skip to main content link
  const skipLink = document.querySelector('[data-skip-link]');
  const mainContent = document.querySelector('main');

  if (skipLink && mainContent) {
    skipLink.addEventListener('click', (e) => {
      e.preventDefault();
      mainContent.focus();
      mainContent.scrollIntoView({ behavior: 'smooth' });
    });
  }

  // Keyboard navigation for dropdowns
  const dropdowns = document.querySelectorAll('[data-dropdown]');
  dropdowns.forEach((dropdown) => {
    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
    const menu = dropdown.querySelector('[data-dropdown-menu]');

    if (!trigger || !menu) {
      return;
    }

    trigger.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        menu.classList.remove('hidden');
        const firstItem = menu.querySelector('a, button');
        if (firstItem) {
          firstItem.focus();
        }
      }
    });

    menu.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        e.preventDefault();
        menu.classList.add('hidden');
        trigger.focus();
      }
    });
  });
}

/**
 * Initialize Form Behavior
 *
 * Handle form interactions and validations
 */
function initializeFormBehavior() {
  const forms = document.querySelectorAll('[data-form-enhanced]');

  forms.forEach((form) => {
    // Add loading state during submission
    form.addEventListener('submit', (e) => {
      const submitButton = form.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.dataset.originalText = submitButton.textContent;
        submitButton.textContent = submitButton.dataset.loadingText || 'Sending...';
      }
    });

    // Handle form validation
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach((input) => {
      input.addEventListener('blur', () => {
        validateField(input);
      });
    });
  });
}

/**
 * Validate Form Field
 *
 * @param {HTMLElement} field The form field to validate
 */
function validateField(field) {
  let isValid = true;

  // Check required fields
  if (field.hasAttribute('required') && !field.value.trim()) {
    isValid = false;
  }

  // Check email fields
  if (field.type === 'email' && field.value) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    isValid = emailRegex.test(field.value);
  }

  // Apply validation classes
  if (isValid) {
    field.classList.remove('invalid');
    field.classList.add('valid');
  } else {
    field.classList.remove('valid');
    field.classList.add('invalid');
  }

  return isValid;
}

/**
 * Export functions for use in other modules
 */
export {
  initializeTheme,
  initializeMobileMenu,
  initializeScrollEffects,
  initializeAccessibility,
  initializeFormBehavior,
  validateField,
};
