/**
 * AUTHENTIC APPLE MINIMALIST INTERACTION ENGINE
 * Optimized for Narangga Adennas Shaputra Portfolio
 */

document.addEventListener('DOMContentLoaded', () => {
  initThemeToggle();
  initScrollAnimations();
  initNavbarScroll();
  initMobileMenu();
  initProjectTilt();
  initProjectFilter();
  initProjectModal();
  initContactForm();
  initSmoothScroll();
  initKeyboardNav();
});

/* ==========================================================================
   1. Theme Switcher (Dark / Light Mode)
   ========================================================================== */
function initThemeToggle() {
  const themeBtn = document.getElementById('theme-toggle');
  const themeIcon = themeBtn ? themeBtn.querySelector('i') : null;
  
  const savedTheme = localStorage.getItem('portfolio-theme');
  const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
  
  setTheme(initialTheme);

  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      setTheme(newTheme);
    });
  }

  function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('portfolio-theme', theme);
    if (themeIcon) {
      themeIcon.className = theme === 'dark' ? 'ri-sun-line' : 'ri-moon-line';
    }
  }
}

/* ==========================================================================
   2. Scroll-Triggered Enter & Exit Animations (IntersectionObserver)
   ========================================================================== */
function initScrollAnimations() {
  const animatedElements = document.querySelectorAll('.scroll-animate');

  const observerOptions = {
    root: null,
    rootMargin: '0px 0px -40px 0px',
    threshold: 0.1
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      } else {
        const bounding = entry.boundingClientRect;
        if (bounding.top > window.innerHeight || bounding.bottom < 0) {
          entry.target.classList.remove('is-visible');
        }
      }
    });
  }, observerOptions);

  animatedElements.forEach(el => observer.observe(el));
}

/* ==========================================================================
   3. Navbar Scroll & Active Link Highlight
   ========================================================================== */
function initNavbarScroll() {
  const navbar = document.getElementById('navbar');
  const backToTopBtn = document.querySelector('.back-to-top');
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      navbar.classList.add('scrolled');
      if (backToTopBtn) backToTopBtn.style.opacity = '1';
    } else {
      navbar.classList.remove('scrolled');
      if (backToTopBtn) backToTopBtn.style.opacity = '0.7';
    }

    let currentSectionId = '';
    sections.forEach(section => {
      const sectionTop = section.offsetTop - 120;
      const sectionHeight = section.offsetHeight;
      if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
        currentSectionId = section.getAttribute('id');
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === `#${currentSectionId}`) {
        link.classList.add('active');
      }
    });
  });
}

/* ==========================================================================
   4. Mobile Hamburger Menu Toggle
   ========================================================================== */
function initMobileMenu() {
  const hamburger = document.getElementById('hamburger-btn');
  const navLinksContainer = document.querySelector('.nav-links');

  if (hamburger && navLinksContainer) {
    hamburger.addEventListener('click', () => {
      navLinksContainer.classList.toggle('mobile-active');
      const isOpen = navLinksContainer.classList.contains('mobile-active');
      hamburger.querySelector('i').className = isOpen ? 'ri-close-line' : 'ri-menu-line';
    });

    // Close menu when link clicked
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navLinksContainer.classList.remove('mobile-active');
        hamburger.querySelector('i').className = 'ri-menu-line';
      });
    });
  }
}

/* ==========================================================================
   5. 3D Card Tilt Effect
   ========================================================================== */
function initProjectTilt() {
  const cards = document.querySelectorAll('.tilt-card');

  cards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      const centerX = rect.width / 2;
      const centerY = rect.height / 2;

      const rotateX = ((y - centerY) / centerY) * -6;
      const rotateY = ((x - centerX) / centerX) * 6;

      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)';
    });
  });
}

/* ==========================================================================
   6. Interactive Project Filtering
   ========================================================================== */
function initProjectFilter() {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const projectCards = document.querySelectorAll('.project-card-wrapper');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filterValue = btn.getAttribute('data-filter');

      projectCards.forEach(wrapper => {
        const card = wrapper.querySelector('.project-card');
        const category = card.getAttribute('data-category');

        if (filterValue === 'all' || category === filterValue) {
          wrapper.style.display = 'block';
          setTimeout(() => {
            wrapper.classList.add('is-visible');
          }, 30);
        } else {
          wrapper.classList.remove('is-visible');
          setTimeout(() => {
            wrapper.style.display = 'none';
          }, 300);
        }
      });
    });
  });
}

/* ==========================================================================
   7. Project Details Modal Viewer
   ========================================================================== */
const projectData = {
  1: {
    title: "Konfigurasi & Simulasi Jaringan",
    category: "Networking & Infrastructure (Praktikum Kuliah)",
    image: "assets/images/project_net.jpg",
    description: "Merancang dan mengkonfigurasi berbagai topologi jaringan menggunakan Cisco Packet Tracer dan Mikrotik/RouterOS, mencakup simulasi jaringan untuk skala kantor maupun area yang lebih luas. Meliputi konfigurasi routing, switching, subnetting, serta pengujian konektivitas antar perangkat dalam jaringan.",
    tech: ["Cisco Packet Tracer", "Mikrotik / RouterOS", "Subnetting", "Routing & Switching", "TCP/IP"],
    role: "Individu & Kelompok",
    scope: "Topologi jaringan kantor & jaringan area luas"
  },
  2: {
    title: "Gas Detector — IoT Dasar",
    category: "IoT & Hardware (Project Based Learning / PBL)",
    image: "assets/images/project_gas.jpg",
    description: "Merancang dan membangun perangkat pendeteksi gas sederhana berbasis IoT sebagai bagian dari pembelajaran berbasis proyek, mencakup perakitan sensor MQ-2, pemrograman mikrokontroler Arduino, dan pengujian sistem deteksi.",
    tech: ["Arduino", "Sensor MQ-2", "IoT", "Microcontroller", "C++"],
    role: "Kelompok (Hardware & IoT Programmer)",
    scope: "Prototype Pendeteksi Gas"
  },
  3: {
    title: "Edukasi Teknologi & Pancasila",
    category: "Education & Social (Proyek Edukasi SMK)",
    image: "assets/images/project_net.jpg",
    description: "Merancang proyek edukasi yang mengaitkan pemanfaatan teknologi dengan nilai-nilai Pancasila, ditujukan untuk siswa SMK di Jakarta guna meningkatkan pemahaman penerapan nilai kebangsaan dalam konteks perkembangan teknologi.",
    tech: ["Educational Workshop", "Pancasila", "Public Speaking", "Presentation"],
    role: "Kelompok (Tim Edukator)",
    scope: "SMK di Jakarta"
  },
  4: {
    title: "Produksi Video Konten Pribadi",
    category: "Multimedia & Video Production (Proyek Personal)",
    image: "assets/images/project_gas.jpg",
    description: "Memproduksi berbagai video hiburan untuk konsumsi pribadi, mencakup proses shooting, editing, color grading, hingga motion graphics sederhana.",
    tech: ["CapCut", "Alight Motion", "Adobe Photoshop", "Video Editing"],
    role: "Individu (Konsep, Syuting, Editing)",
    scope: "Konten Video Personal"
  },
  5: {
    title: "Audit Laporan Keuangan Klien",
    category: "Finance & Audit (KAP Wibisono dan Rekan)",
    image: "assets/images/project_audit.jpg",
    description: "Terlibat dalam proses audit laporan keuangan untuk klien perusahaan (PT), mencakup pemeriksaan dokumen, verifikasi transaksi, dan penyusunan kertas kerja audit. Bertanggung jawab memastikan kesesuaian laporan keuangan klien dengan standar akuntansi yang berlaku.",
    tech: ["Microsoft Excel", "Financial Audit", "Akuntansi", "Kertas Kerja Audit"],
    role: "Junior Auditor",
    scope: "Audit Klien Perusahaan (PT)"
  },
  6: {
    title: "Bakti Sosial Renovasi Rumah Ibadah",
    category: "Social & Community (P3GN Pertamina)",
    image: "assets/images/project_audit.jpg",
    description: "Berpartisipasi sebagai anggota dalam program bakti sosial pembersihan dan perawatan rumah ibadah secara gotong royong tanpa biaya, sebagai bentuk kontribusi sosial kepada masyarakat di berbagai daerah di Indonesia.",
    tech: ["Social Contribution", "Volunteer", "Community Service", "P3GN"],
    role: "Member / Volunteer",
    scope: "Berbagai Daerah di Indonesia"
  }
};

function initProjectModal() {
  const modalOverlay = document.getElementById('project-modal');
  const closeBtn = document.getElementById('modal-close');
  const projectCards = document.querySelectorAll('.project-card');

  if (!modalOverlay) return;

  projectCards.forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('.project-link-btn')) return;

      const projectId = card.getAttribute('data-id');
      const data = projectData[projectId];

      if (data) {
        document.getElementById('modal-title').textContent = data.title;
        document.getElementById('modal-category').textContent = data.category;
        document.getElementById('modal-img').src = data.image;
        document.getElementById('modal-desc').textContent = data.description;
        
        const techContainer = document.getElementById('modal-tech');
        techContainer.innerHTML = data.tech.map(t => `<span class="tag-item">${t}</span>`).join('');

        modalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }

  modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) closeModal();
  });
}

function closeModal() {
  const modalOverlay = document.getElementById('project-modal');
  if (modalOverlay) {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }
}

/* ==========================================================================
   8. Keyboard Accessibility (Escape Key Listener)
   ========================================================================== */
function initKeyboardNav() {
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeModal();
      const navLinksContainer = document.querySelector('.nav-links');
      if (navLinksContainer && navLinksContainer.classList.contains('mobile-active')) {
        navLinksContainer.classList.remove('mobile-active');
      }
    }
  });
}

/* ==========================================================================
   9. Form Contact Handler & Direct WhatsApp Fallback
   ========================================================================== */
function initContactForm() {
  const form = document.getElementById('contact-form');
  const statusMsg = document.getElementById('form-status');

  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const message = document.getElementById('message').value.trim();

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;

      submitBtn.innerHTML = `<i class="ri-loader-4-line ri-spin"></i> Mengirim...`;
      submitBtn.disabled = true;

      // Simulate API submit and construct WhatsApp link option
      setTimeout(() => {
        submitBtn.innerHTML = `<i class="ri-checkbox-circle-line"></i> Terkirim!`;
        submitBtn.style.background = '#34c759';
        
        if (statusMsg) {
          const waText = encodeURIComponent(`Halo Narangga,\n\nNama: ${name}\nEmail: ${email}\n\nPesan:\n${message}`);
          const waUrl = `https://wa.me/6282337840357?text=${waText}`;

          statusMsg.style.display = 'block';
          statusMsg.innerHTML = `Pesan berhasil dikirim! Anda juga bisa <a href="${waUrl}" target="_blank" style="color: #34c759; font-weight: 700; text-decoration: underline;">kirim langsung via WhatsApp</a>.`;
        }

        form.reset();

        setTimeout(() => {
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
          submitBtn.style.background = '';
        }, 5000);
      }, 1200);
    });
  }
}

/* ==========================================================================
   10. Smooth Scroll for Navigation
   ========================================================================== */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;

      const targetEl = document.querySelector(targetId);
      if (targetEl) {
        e.preventDefault();
        const headerOffset = 70;
        const elementPosition = targetEl.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
}
