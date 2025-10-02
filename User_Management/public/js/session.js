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
