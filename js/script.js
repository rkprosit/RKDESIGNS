// ========== MOBILE MENU ==========
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('navLinks');
const navOverlay = document.createElement('div');
navOverlay.className = 'nav-overlay';
document.body.appendChild(navOverlay);

function closeMobileMenu() {
  hamburger.classList.remove('active');
  navLinks.classList.remove('open');
  navOverlay.classList.remove('active');
  document.body.style.overflow = '';
}

function openMobileMenu() {
  hamburger.classList.add('active');
  navLinks.classList.add('open');
  navOverlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}

hamburger.addEventListener('click', () => {
  if (navLinks.classList.contains('open')) {
    closeMobileMenu();
  } else {
    openMobileMenu();
  }
});

navOverlay.addEventListener('click', closeMobileMenu);

document.querySelectorAll('.nav-links .dropdown > a').forEach(function(link) {
  link.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
      e.preventDefault();
      this.parentElement.classList.toggle('open');
    }
  });
});

document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', function() {
    if (this.parentElement.classList.contains('dropdown') && window.innerWidth <= 768) {
      return;
    }
    closeMobileMenu();
  });
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && navLinks.classList.contains('open')) {
    closeMobileMenu();
  }
});

// ========== PORTFOLIO TOUCH SUPPORT ==========
document.addEventListener('touchstart', function(e) {
  var item = e.target.closest('.portfolio-item');
  if (item) {
    document.querySelectorAll('.portfolio-item.touched').forEach(function(el) {
      el.classList.remove('touched');
    });
    item.classList.add('touched');
  }
}, { passive: true });

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
    if (link.getAttribute('href') === '#' + current) {
      link.classList.add('active');
    }
  });
});

// ========== SCROLL PROGRESS BAR ==========
const progressBar = document.getElementById('scrollProgress');
window.addEventListener('scroll', function() {
  var scrollTop = window.scrollY;
  var docHeight = document.documentElement.scrollHeight - window.innerHeight;
  var progress = (scrollTop / docHeight) * 100;
  progressBar.style.width = progress + '%';
});

// ========== SCROLL REVEAL ANIMATIONS ==========
const revealObserver = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    if (entry.isIntersecting) {
      entry.target.classList.add('revealed');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('[data-reveal], [data-stagger]').forEach(function(el) {
  revealObserver.observe(el);
});

// ========== SECTION DIVIDER ANIMATION ==========
const dividerObserver = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    if (entry.isIntersecting) {
      entry.target.classList.add('animate');
      dividerObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.section-divider').forEach(function(el) {
  dividerObserver.observe(el);
});

// ========== IMAGE LOADING FADE-IN ==========
document.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
  if (img.complete) {
    img.classList.add('loaded');
  } else {
    img.addEventListener('load', function() {
      this.classList.add('loaded');
    });
    img.addEventListener('error', function() {
      this.classList.add('loaded');
    });
  }
});

// ========== STAT COUNTER ANIMATION ==========
function animateCounter(el, target, suffix) {
  var current = 0;
  var step = Math.ceil(target / 60);
  var timer = setInterval(function() {
    current += step;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    el.textContent = current + (suffix || '');
  }, 20);
}

const statObserver = new IntersectionObserver(function(entries) {
  entries.forEach(function(entry) {
    if (entry.isIntersecting) {
      var el = entry.target;
      var text = el.textContent.trim();
      var match = text.match(/(\d+)(\+?)/);
      if (match) {
        var num = parseInt(match[1]);
        var suffix = match[2] || '';
        animateCounter(el, num, suffix);
      }
      statObserver.unobserve(el);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-number').forEach(function(el) {
  statObserver.observe(el);
});

// ========== CURSOR FOLLOWER ==========
var cursorDot = document.getElementById('cursorDot');

if (window.matchMedia('(hover: hover)').matches) {
  document.addEventListener('mousemove', function(e) {
    cursorDot.style.left = e.clientX + 'px';
    cursorDot.style.top = e.clientY + 'px';
  });

  document.querySelectorAll('a, button, .btn, .filter-btn, .portfolio-item, .team-card, .social-big, .float-btn').forEach(function(el) {
    el.addEventListener('mouseenter', function() {
      cursorDot.classList.add('hover');
    });
    el.addEventListener('mouseleave', function() {
      cursorDot.classList.remove('hover');
    });
  });
}

// ========== NEWSLETTER FORM ==========
var newsletterForm = document.getElementById('newsletterForm');
if (newsletterForm) {
  newsletterForm.addEventListener('submit', function(e) {
    e.preventDefault();
    var input = this.querySelector('input');
    var email = input.value;
    var originalPlaceholder = input.placeholder;
    fetch('api/subscribe.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email })
    }).then(function(r) { return r.json(); }).then(function(data) {
      input.value = '';
      input.placeholder = data.success ? 'Thank you for subscribing!' : data.error || 'Error';
      input.style.color = '#c8a165';
      setTimeout(function() {
        input.placeholder = originalPlaceholder;
        input.style.color = '';
      }, 3000);
    }).catch(function() {
      input.value = '';
      input.placeholder = 'Thank you for subscribing!';
      input.style.color = '#c8a165';
      setTimeout(function() {
        input.placeholder = originalPlaceholder;
        input.style.color = '';
      }, 3000);
    });
  });
}
