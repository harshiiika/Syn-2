<<<<<<< HEAD
=======
//Code for sidebar, to make it collapsible.
document.addEventListener('DOMContentLoaded', function () {
  console.log("session.js loaded and DOMContentLoaded event fired");

  // ---------- CSRF Helper - for future AJAX calls ----------
  function getCsrf() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : '';
  }

  // ---------- ID Helper - normalizes session IDs ----------
  function extractId(session) {
    if (!session) return null;
    if (session.id) return session.id;
    if (session._id) {
      if (typeof session._id === 'string') return session._id;
      if (session._id.$oid) return session._id.$oid;
      return session._id.toString();
    }
    return null;
  }

  // ---------- Auto-hide Flash Messages - removes server alerts ----------
  setTimeout(() => {
    document.querySelectorAll('.flash-container .alert').forEach(alert => {
      console.log("Removing alert:", alert.textContent.trim());
      alert.style.transition = 'opacity 0.5s ease-out';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    });
  }, 3000);

  // ---------- Toast Management - REMOVED auto-show ----------
  const toastEl = document.getElementById('liveToast');
  let toast = null;
  
  if (toastEl) {
    toast = new bootstrap.Toast(toastEl, { 
      delay: 4000, 
      autohide: true 
    });
    // Only show when explicitly called - no automatic showing
  }

  // ---------- Sidebar Toggle - collapsible navigation ----------
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

  // ---------- Search Functionality - real-time table filtering ----------
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

  // ---------- Entries Dropdown - show entries per page ----------
  const entriesDropdown = document.querySelector('#number');
  if (entriesDropdown) {
    const dropdownItems = entriesDropdown.parentElement.querySelectorAll('.dropdown-item');
    
    dropdownItems.forEach(item => {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        const selectedValue = this.textContent;
        entriesDropdown.textContent = selectedValue;
        console.log('Selected entries per page:', selectedValue);
      });
    });
  }

  // ---------- Confirm End Session - prevents accidental deletion ----------
  document.querySelectorAll('form[action*="sessions.end"] button[type="submit"]').forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to end this session? This action cannot be undone.')) {
        e.preventDefault();
      }
    });
  });

});


>>>>>>> 3cb5753b8f66bb9cf0628ea821c033e075404a97
// document.addEventListener('DOMContentLoaded', function () {
//   // ---------- helpers ----------
//   function getCsrf() {
//     const tokenMeta = document.querySelector('meta[name="csrf-token"]');
//     return tokenMeta ? tokenMeta.getAttribute('content') : '';
//   }

//   function extractId(session) {
//     if (!session) return null;
//     if (session.id) return session.id;
//     if (session._id) {
//       if (typeof session._id === 'string') return session._id;
//       if (session._id.$oid) return session._id.$oid;
//       return session._id.toString();
//     }
//     return null;
//   }

//   function fetchSession(id, callback) {
//     fetch(`/session/${id}`, {
//       headers: {
//         'X-Requested-With': 'XMLHttpRequest',
//         'Accept': 'application/json',
//         'X-CSRF-TOKEN': getCsrf()
//       }
//     })
//       .then(res => res.json())
//       .then(session => callback(session))
//       .catch(err => {
//         console.error('Fetch session error:', err);
//         alert('Failed to fetch session. Check console.');
//       });
//   }

//   // ---------- View Session ----------
//   window.viewSession = function (id) {
//     fetchSession(id, function (session) {
//       document.getElementById('viewName').textContent = session.name || '';
//       document.getElementById('viewStart').textContent = session.start_date || '';
//       document.getElementById('viewEnd').textContent = session.end_date || '';
//       document.getElementById('viewStatus').textContent = session.status || '';
//       new bootstrap.Modal(document.getElementById('viewSessionModal')).show();
//     });
//   };

//   // ---------- Edit Session ----------
//   window.editSession = function (id) {
//     fetchSession(id, function (session) {
//       const sid = extractId(session);
//       if (!sid) {
//         alert('Invalid session ID');
//         return;
//       }

//       document.getElementById('editSessionId').value = sid;
//       document.getElementById('editName').value = session.name || '';
//       document.getElementById('editStart').value = session.start_date ? session.start_date.split('T')[0] : '';
//       document.getElementById('editEnd').value = session.end_date ? session.end_date.split('T')[0] : '';

//       const statusSelect = document.getElementById('editStatus');
//       const status = (session.status || 'deactive').toLowerCase();
//       statusSelect.value = status.charAt(0).toUpperCase() + status.slice(1);

//       new bootstrap.Modal(document.getElementById('editSessionModal')).show();
//     });
//   };

//   // ---------- Submit Edit Form ----------
//   const editForm = document.getElementById('editSessionForm');
//   if (editForm) {
//     editForm.addEventListener('submit', function (e) {
//       e.preventDefault();
//       const sessionId = document.getElementById('editSessionId').value;
//       if (!sessionId) return alert('Session ID missing.');

//       const fd = new FormData(this);
//       fd.set('_method', 'PUT');

//       fetch(`/session/${sessionId}`, {
//         method: 'POST',
//         headers: {
//           'X-CSRF-TOKEN': getCsrf(),
//           'X-Requested-With': 'XMLHttpRequest',
//           'Accept': 'application/json'
//         },
//         body: fd
//       })
//         .then(res => res.json())
//         .then(result => {
//           if (result.success) {
//             bootstrap.Modal.getInstance(document.getElementById('editSessionModal'))?.hide();
//             window.location.reload();
//           } else {
//             alert(result.error || 'Update failed. Check logs.');
//           }
//         })
//         .catch(err => {
//           console.error('Update failed:', err);
//           alert('Failed to update session. Check console & Laravel logs.');
//         });
//     });
//   }

//   // ---------- Auto-hide flash messages ----------
//   setTimeout(() => {
//     document.querySelectorAll('.flash-container .alert').forEach(f => f.classList.remove('show'));
//   }, 3000);
// });
