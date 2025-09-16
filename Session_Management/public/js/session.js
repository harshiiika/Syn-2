// public/js/session.js
document.addEventListener('DOMContentLoaded', function () {
  // ---------- helpers ----------
  function getCsrf() {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute('content') : (document.querySelector('input[name="_token"]') || {}).value;
  }

  function safeJson(res) {
    const ct = res.headers.get('content-type') || '';
    if (ct.includes('application/json')) return res.json();
    return res.text().then(txt => { throw { status: res.status, text: txt }; });
  }

  function extractId(obj) {
    if (!obj) return null;
    
    // First try the 'id' field
    if (obj.id) return obj.id;
    
    // Then try MongoDB '_id' field
    if (obj._id) {
      if (typeof obj._id === 'string') return obj._id;
      if (obj._id.$oid) return obj._id.$oid;
      if (obj._id['$oid']) return obj._id['$oid'];
      // Handle other ObjectId formats
      if (typeof obj._id === 'object' && obj._id.toString) {
        return obj._id.toString();
      }
    }
    
    // Fallback: try any field that looks like an ID
    const keys = Object.keys(obj);
    for (const key of keys) {
      if (key.toLowerCase().includes('id') && obj[key]) {
        return obj[key].toString();
      }
    }
    
    return null;
  }

  // ---------- viewSession ----------
  window.viewSession = function (id) {
    console.log('viewSession -> id:', id);
    fetch(`/session/${id}`, { 
      headers: { 
        'X-Requested-With': 'XMLHttpRequest', 
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrf()
      } 
    })
    .then(res => {
      if (!res.ok) return safeJson(res);
      return res.json();
    })
    .then(session => {
      console.log('View session data:', session);
      document.getElementById('viewName').textContent = session.name || '';
      document.getElementById('viewStart').textContent = session.start_date || '';
      document.getElementById('viewEnd').textContent = session.end_date || '';
      document.getElementById('viewStatus').textContent = session.status || '';
      new bootstrap.Modal(document.getElementById('viewSessionModal')).show();
    })
    .catch(err => {
      console.error('viewSession error ->', err);
      if (err && err.text) alert('Server returned: ' + err.text);
      else alert('Failed to load session. Check console network & logs.');
    });
  };

  // ---------- editSession (open) ----------
  window.editSession = function (id) {
    console.log('editSession -> id:', id);
    fetch(`/session/${id}`, { 
      headers: { 
        'X-Requested-With': 'XMLHttpRequest', 
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrf()
      } 
    })
    .then(res => {
      if (!res.ok) return safeJson(res);
      return res.json();
    })
    .then(session => {
      console.log('edit payload:', session);
      
      // Debug: log what we received
      console.log('Full session object:', session);
      console.log('session.id:', session.id);
      console.log('session._id:', session._id);
      
      const sid = extractId(session);
      console.log('Extracted ID:', sid);
      
      if (!sid) { 
        console.error('Session object details:', session);
        alert('Invalid session id returned. Check server response in console.'); 
        return; 
      }

      document.getElementById('editSessionId').value = sid;
      document.getElementById('editName').value = session.name || '';
      document.getElementById('editStart').value = session.start_date ? session.start_date.split('T')[0] : '';
      document.getElementById('editEnd').value = session.end_date ? session.end_date.split('T')[0] : '';
      
      // Set status properly
      const statusSelect = document.getElementById('editStatus');
      const currentStatus = session.status || 'deactive';
      statusSelect.value = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1).toLowerCase();

      new bootstrap.Modal(document.getElementById('editSessionModal')).show();
    })
    .catch(err => {
      console.error('editSession error ->', err);
      if (err && err.text) alert('Server returned: ' + err.text);
      else alert('Failed to load session for edit. Check console network & logs.');
    });
  };

  // ---------- submit edit ----------
  const editForm = document.getElementById('editSessionForm');
  if (editForm) {
    editForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const sessionId = document.getElementById('editSessionId').value;
      if (!sessionId) { 
        alert('Session id missing'); 
        return; 
      }

      const fd = new FormData(this);
      if (!fd.get('_method')) fd.append('_method', 'PUT');

      console.log('sending update for', sessionId, Array.from(fd.entries()));
      
      fetch(`/session/${sessionId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': getCsrf(),
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: fd
      })
      .then(res => {
        // try parse json or throw text for clearer debugging
        const ct = res.headers.get('content-type') || '';
        if (ct.includes('application/json')) {
          return res.json().then(j => ({ ok: res.ok, data: j }));
        }
        return res.text().then(t => { 
          throw { status: res.status, text: t }; 
        });
      })
      .then(result => {
        // result.data is JSON payload from server
        console.log('update response', result);
        if (result.data && (result.data.success || result.data.msg)) {
          // success - close and reload
          bootstrap.Modal.getInstance(document.getElementById('editSessionModal'))?.hide();
          window.location.reload();
        } else {
          const err = result.data && (result.data.error || result.data.message) ? 
                     (result.data.error || result.data.message) : 'Unknown server response';
          alert('Update failed: ' + err);
        }
      })
      .catch(err => {
        console.error('update failed ->', err);
        if (err && err.text) {
          // HTML or plain text error
          alert('Server error: ' + err.text);
        } else {
          alert('Network/JS error. Check console & Laravel logs.');
        }
      });
    });
  }
});


  // Auto-hide flash messages after 3 seconds
  setTimeout(() => {
    const flash = document.querySelectorAll('.flash-container .alert');
    flash.forEach(f => f.classList.remove('show'));
  }, 3000);
