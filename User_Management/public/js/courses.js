// Courses Management JavaScript
document.addEventListener('DOMContentLoaded', function () {
  console.log("courses.js loaded and DOMContentLoaded event fired");

  // ---------- CSRF Helper ----------
  function getCsrf() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : '';
  }

  // ---------- ID Helper ----------
  function extractId(course) {
    if (!course) return null;
    if (course.id) return course.id;
    if (course._id) {
      if (typeof course._id === 'string') return course._id;
      if (course._id.$oid) return course._id.$oid;
      return course._id.toString();
    }
    return null;
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
  const searchInput = document.querySelector('#searchInput');
  const tableBody = document.querySelector('#coursesTable tbody');
  
  if (searchInput && tableBody) {
    searchInput.addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = tableBody.querySelectorAll('tr');
      
      let visibleCount = 0;
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      // Update showing text if exists
      const showText = document.querySelector('.show');
      if (showText && visibleCount > 0) {
        showText.textContent = `Showing ${visibleCount} entries`;
      }
    });
  }

  // ---------- Entries Dropdown ----------
  const entriesDropdown = document.querySelector('#number');
  if (entriesDropdown) {
    const dropdownItems = entriesDropdown.parentElement.querySelectorAll('.dropdown-item');
    
    dropdownItems.forEach(item => {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        const selectedValue = this.textContent.trim();
        entriesDropdown.textContent = selectedValue;
        console.log('Selected entries per page:', selectedValue);
        
        // Here you can add logic to actually limit table rows
        // For now, it just updates the dropdown text
      });
    });
  }

  // ---------- Delete Confirmation ----------
  const deleteForms = document.querySelectorAll('form[action*="courses"]');
  deleteForms.forEach(form => {
    if (form.querySelector('input[name="_method"][value="DELETE"]') || 
        form.getAttribute('method') === 'POST' && form.action.includes('destroy')) {
      const deleteBtn = form.querySelector('button[type="submit"]');
      if (deleteBtn) {
        form.addEventListener('submit', function(e) {
          if (!confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
            e.preventDefault();
          }
        });
      }
    }
  });

  // ---------- Form Validation ----------
  const createForm = document.querySelector('#createCourseModal form');
  if (createForm) {
    createForm.addEventListener('submit', function(e) {
      const courseName = this.querySelector('input[name="course_name"]').value.trim();
      const courseType = this.querySelector('input[name="course_type"]').value.trim();
      const className = this.querySelector('input[name="class_name"]').value.trim();
      const courseCode = this.querySelector('input[name="course_code"]').value.trim();

      if (!courseName || !courseType || !className || !courseCode) {
        e.preventDefault();
        alert('Please fill in all required fields');
        return false;
      }
    });
  }

  // ---------- Modal Reset on Close ----------
  const createModal = document.getElementById('createCourseModal');
  if (createModal) {
    createModal.addEventListener('hidden.bs.modal', function () {
      const form = this.querySelector('form');
      if (form) {
        form.reset();
      }
    });
  }

  // ---------- Table Row Hover Effect ----------
  const tableRows = document.querySelectorAll('#coursesTable tbody tr');
  tableRows.forEach(row => {
    row.addEventListener('mouseenter', function() {
      this.style.backgroundColor = '#f8f9fa';
    });
    row.addEventListener('mouseleave', function() {
      this.style.backgroundColor = '';
    });
  });

  // ---------- Status Badge Click Prevention ----------
  const statusBadges = document.querySelectorAll('.badge');
  statusBadges.forEach(badge => {
    badge.style.cursor = 'default';
  });

  console.log('Courses management initialized successfully');
});

//extra css idk 
// Minimal Subject Tag Management
(function() {
  // Create modal subjects
  const inp = document.getElementById('subjectInput');
  const tags = document.getElementById('subjectTags');
  let subs = [];

  if (inp) {
    inp.onkeypress = function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const v = this.value.trim();
        if (v && subs.indexOf(v) === -1) {
          subs.push(v);
          render();
          this.value = '';
        }
      }
    };
  }

  function render() {
    tags.innerHTML = '';
    subs.forEach(function(s, i) {
      const t = document.createElement('span');
      t.className = 'subject-tag';
      t.innerHTML = s + '<i class="fas fa-times" onclick="removeTag(' + i + ')"></i><input type="hidden" name="subjects[]" value="' + s + '">';
      tags.appendChild(t);
    });
  }

  window.removeTag = function(i) {
    subs.splice(i, 1);
    render();
  };

  // Edit modals subjects
  document.querySelectorAll('[id^="editSubjectTags"]').forEach(function(tc) {
    const cid = tc.dataset.courseId;
    const ei = document.getElementById('editSubjectInput' + cid);
    let es = JSON.parse(tc.dataset.subjects || '[]');

    function er() {
      tc.innerHTML = '';
      es.forEach(function(s, i) {
        const t = document.createElement('span');
        t.className = 'subject-tag';
        t.innerHTML = s + '<i class="fas fa-times" data-idx="' + i + '" data-cid="' + cid + '"></i><input type="hidden" name="subjects[]" value="' + s + '">';
        tc.appendChild(t);
      });

      tc.querySelectorAll('.fa-times').forEach(function(b) {
        b.onclick = function() {
          const idx = parseInt(this.dataset.idx);
          es.splice(idx, 1);
          er();
        };
      });
    }

    if (ei) {
      ei.onkeypress = function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          const v = this.value.trim();
          if (v && es.indexOf(v) === -1) {
            es.push(v);
            er();
            this.value = '';
          }
        }
      };
    }

    er();
  });
})();
