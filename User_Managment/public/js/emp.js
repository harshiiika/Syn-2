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
//Js for bootstrap
const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

const myModal = document.getElementById('myModal')
const myInput = document.getElementById('myInput')


 $('.dropdown-item').click(function() {
  const selectedText = $(this).text();
  $(this).closest('.dropdown').find('.dropdown-toggle').text(selectedText);
});
