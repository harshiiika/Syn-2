//Code for sidebar, to make it collapsible.
document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.querySelector('.sidebar') || document.querySelector('#sidebar') || document.querySelector('#text') || document.querySelector('#right');

  let isCollapsed = false;

  sidebar.style.transition = 'width 0.5s ease-in-out';
  sidebar.style.overflow = 'hidden';
  sidebar.style.width = '300px';

  const menuItems = sidebar.querySelectorAll('li, a, .nav-item');
  menuItems.forEach(item => {
    item.style.whiteSpace = 'nowrap';
  });

  toggleBtn.addEventListener('click', function () {
    console.log('Toggle button clicked! Current state:', isCollapsed ? 'collapsed' : 'expanded');

    if (isCollapsed) {
      sidebar.style.width = '26%';
      right.style.width = '100%';
      text.style.visibility = 'visible';

    }
    else {
      sidebar.style.width = '41px';
      right.style.width = '100%';
      text.style.visibility = 'hidden';
    }

    isCollapsed = !isCollapsed;
  });
});

document.addEventListener('DOMContentLoaded', function () {

  // ---------- helpers ----------//
  //This function reads the <meta name="csrf-token" content="..."> in your <head> and returns it, used whenever making fetch calls to your Laravel backend
  function getCsrf() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : '';
  }


//Some sessions might use id, some _id (maybe MongoDB style IDs).
//This helper normalizes whatever ID format your session object has into a string,use this if  DB returns inconsistent key names.
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

  // ---------- Auto-hide flash messages ----------//
  console.log("session.js loaded ✅");

  setTimeout(() => {
    document.querySelectorAll('.flash-container .alert').forEach(f => {
      console.log("Removing alert:", f.textContent.trim());
      f.remove(); // instantly disappears
    });
  }, 3000);

  const toastEl = document.getElementById('liveToast');
  if (toastEl) {
    const toast = new bootstrap.Toast(toastEl, { delay: 3000, autohide: true });
    toast.show();
  }
});


