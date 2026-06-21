// ========== MOBILE MENU ==========
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('navLinks');

hamburger.addEventListener('click', () => {
  hamburger.classList.toggle('active');
  navLinks.classList.toggle('open');
});

// ========== MOBILE DROPDOWN TOGGLE ==========
document.querySelectorAll('.nav-links .dropdown > a').forEach(function(link) {
  link.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
      e.preventDefault();
      this.parentElement.classList.toggle('open');
    }
  });
});

// ========== CLOSE MOBILE MENU ON LINK CLICK ==========
document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', function() {
    if (this.parentElement.classList.contains('dropdown')) {
      return;
    }
    hamburger.classList.remove('active');
    navLinks.classList.remove('open');
  });
});

// ========== ACTIVE NAV LINK ==========
const sections = document.querySelectorAll('section[id]');

window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(section => {
    const sectionTop = section.offsetTop - 300;
    if (window.scrollY >= sectionTop) {
      current = section.getAttribute('id');
    }
  });
  document.querySelectorAll('.nav-links a').forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href') === `#${current}`) {
      link.classList.add('active');
    }
  });
});

// ========== NEWSLETTER FORM ==========
const newsletterForm = document.getElementById('newsletterForm');

newsletterForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const input = newsletterForm.querySelector('input');
  const originalPlaceholder = input.placeholder;
  input.value = '';
  input.placeholder = 'Thank you for subscribing!';
  input.style.color = '#c8a165';
  setTimeout(() => {
    input.placeholder = originalPlaceholder;
    input.style.color = '';
  }, 3000);
});

// ========== SCROLL ANIMATIONS ==========
const fadeEls = document.querySelectorAll(
  '.service-block, .portfolio-item, .service-text, .service-image'
);

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.1 }
);

fadeEls.forEach((el) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(30px)';
  el.style.transition = '0.6s ease';
  observer.observe(el);
});
