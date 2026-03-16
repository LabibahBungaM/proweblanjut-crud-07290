</div> <footer class="text-center mt-5 py-4 text-muted small">
    <hr class="container" style="border-top: 2px dashed var(--color-mint); opacity: 0.3;">
    &copy; <?= date('Y'); ?> - Tugas Pemrograman Web Lanjut
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var alertElement = document.getElementById('alert-notif');
        if (alertElement) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
                // Membersihkan URL dari status= agar tidak muncul lagi saat di-refresh
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 2000); 
        }
    });
</script>
</body>
</html>