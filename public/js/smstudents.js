// smstudents.js - Students Management JavaScript
document.addEventListener('DOMContentLoaded', function () {
  console.log("smstudents.js loaded and DOMContentLoaded event fired");

  // ---------- CSRF Helper ----------
  function getCsrf() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : '';
  }

  // ---------- Auto-hide Flash Messages ----------
  setTimeout(() => {
    document.querySelectorAll('.flash-container .alert').forEach(alert => {
      console.log("Removing alert:", alert.textContent.trim());
      alert.style.transition = 'opacity 0.5s ease-out';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    });
  }, 3000);

  // ---------- Sidebar Toggle ----------
  const sidebar = document.querySelector('#sidebar');
  const toggleBtn = document.querySelector('#toggleBtn');
  const text = document.querySelector('#text');
  const right = document.querySelector('#right');

  if (sidebar && toggleBtn && text && right) {
    let isCollapsed = false;

    // Initialize sidebar styling
    sidebar.style.transition = 'width 0.3s ease-in-out';
    sidebar.style.overflow = 'hidden';
    sidebar.style.width = '300px';

    // Menu items styling for collapse
    const menuItems = sidebar.querySelectorAll('li, a, .nav-item');
    menuItems.forEach(item => {
      item.style.whiteSpace = 'nowrap';
    });

    toggleBtn.addEventListener('click', function () {
      console.log('Toggle button clicked! Current state:', isCollapsed ? 'collapsed' : 'expanded');

      if (isCollapsed) {
        // Expand sidebar
        sidebar.style.width = '26%';
        right.style.width = '100%';
        text.style.visibility = 'visible';
      } else {
        // Collapse sidebar
        sidebar.style.width = '41px';
        right.style.width = '100%';
        text.style.visibility = 'hidden';
      }

      isCollapsed = !isCollapsed;
    });
  } else {
    console.warn('Sidebar toggle elements not found');
  }

  // ---------- Search Functionality ----------
  const searchInput = document.querySelector('.search-holder');
  const table = document.querySelector('#table tbody');
  
  if (searchInput && table) {
    searchInput.addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = table.querySelectorAll('tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }

  // ---------- Entries Dropdown ----------
  const entriesDropdown = document.querySelector('#number');
  if (entriesDropdown) {
    const dropdownItems = document.querySelectorAll('.entries-option');
    
    dropdownItems.forEach(item => {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        const selectedValue = this.getAttribute('data-value');
        entriesDropdown.textContent = selectedValue;
        console.log('Selected entries per page:', selectedValue);
        
        // Here you can implement pagination logic if needed
        // For now, it just updates the display
      });
    });
  }

  // ---------- Confirm Deactivation ----------
  document.querySelectorAll('form[action*="students.deactivate"] button[type="submit"]').forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to deactivate this student? This action can be reversed later.')) {
        e.preventDefault();
      }
    });
  });

  // ---------- Password Match Validation ----------
  document.querySelectorAll('form[action*="updatePassword"]').forEach(form => {
    form.addEventListener('submit', function(e) {
      const password = form.querySelector('input[name="password"]').value;
      const passwordConfirm = form.querySelector('input[name="password_confirmation"]').value;
      
      if (password !== passwordConfirm) {
        e.preventDefault();
        alert('Passwords do not match!');
      }
    });
  });

  // ---------- Modal Reset on Close ----------
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('hidden.bs.modal', function () {
      const form = this.querySelector('form');
      if (form) {
        form.reset();
      }
    });
  });

  // ---------- Table Row Hover Effect ----------
  const tableRows = document.querySelectorAll('#table tbody tr');
  tableRows.forEach(row => {
    row.addEventListener('mouseenter', function() {
      this.style.backgroundColor = '#f8f9fa';
    });
    
    row.addEventListener('mouseleave', function() {
      this.style.backgroundColor = '';
    });
  });

  // ---------- Dropdown Menu Position Fix ----------
  document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
    dropdown.addEventListener('click', function(e) {
      const menu = this.nextElementSibling;
      if (menu && menu.classList.contains('dropdown-menu')) {
        // Ensure dropdown stays within viewport
        setTimeout(() => {
          const rect = menu.getBoundingClientRect();
          if (rect.right > window.innerWidth) {
            menu.style.left = 'auto';
            menu.style.right = '0';
          }
        }, 10);
      }
    });
  });

  // ---------- Form Validation Enhancement ----------
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      const requiredFields = form.querySelectorAll('[required]');
      let isValid = true;
      
      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add('is-invalid');
        } else {
          field.classList.remove('is-invalid');
        }
      });
      
      if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields');
      }
    });
  });

  // ---------- Email Validation ----------
  document.querySelectorAll('input[type="email"]').forEach(emailInput => {
    emailInput.addEventListener('blur', function() {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (this.value && !emailPattern.test(this.value)) {
        this.classList.add('is-invalid');
        alert('Please enter a valid email address');
      } else {
        this.classList.remove('is-invalid');
      }
    });
  });

  // ---------- Phone Validation ----------
  document.querySelectorAll('input[name="phone"]').forEach(phoneInput => {
    phoneInput.addEventListener('input', function() {
      // Remove non-numeric characters
      this.value = this.value.replace(/\D/g, '');
      
      // Limit to 15 characters
      if (this.value.length > 15) {
        this.value = this.value.slice(0, 15);
      }
    });
  });

  // ---------- Active Menu Item Highlight ----------
  const currentPath = window.location.pathname;
  document.querySelectorAll('.menu .item').forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.add('active');
      link.style.fontWeight = 'bold';
      link.style.color = '#e05301';
    }
  });

  console.log("All JavaScript functionality initialized successfully");
});