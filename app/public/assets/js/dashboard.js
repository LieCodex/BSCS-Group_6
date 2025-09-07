document.addEventListener('DOMContentLoaded', function() {
	var logoBtn = document.getElementById('logo-btn');
	var logoutForm = document.getElementById('logout-form');
	if (logoBtn && logoutForm) {
		logoBtn.addEventListener('click', function(e) {
			e.preventDefault();
			logoutForm.classList.toggle('show');
		});
		// Optional: Hide logout when clicking outside
		document.addEventListener('click', function(e) {
			if (!logoBtn.contains(e.target) && !logoutForm.contains(e.target)) {
				logoutForm.classList.remove('show');
			}
		});
		// Custom modal confirmation before logout
		logoutForm.addEventListener('submit', function(e) {
			e.preventDefault();
			var modal = document.getElementById('logoutConfirmModal');
			var confirmBtn = document.getElementById('confirmLogoutBtn');
			if (modal && confirmBtn) {
				var bsModal = new bootstrap.Modal(modal);
				bsModal.show();
				confirmBtn.onclick = function() {
					bsModal.hide();
					logoutForm.submit();
				};
			}
		});
	}
});
