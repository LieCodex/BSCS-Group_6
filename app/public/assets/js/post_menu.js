
function toggleMenu(postId) {
    const menu = document.getElementById('menu-' + postId);
    if (menu) {
        menu.classList.toggle('hidden');
        // Optional: Hide other open menus
        document.querySelectorAll('[id^="menu-"]').forEach(m => {
            if (m !== menu) m.classList.add('hidden');
        });
    }
}
// Optional: Hide menu when clicking outside
document.addEventListener('click', function(event) {
    document.querySelectorAll('[id^="menu-"]').forEach(menu => {
        if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !event.target.matches('button[onclick^="toggleMenu"]')) {
            menu.classList.add('hidden');
        }
    });
});
// Hide the success message after 2 seconds
setTimeout(function() {
    var msg = document.getElementById('successmsg');
    if (msg) {
        msg.style.display = 'none';
    }
    }, 2000); 