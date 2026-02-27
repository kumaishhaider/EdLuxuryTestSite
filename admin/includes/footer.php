</div><!-- Close container -->
</div><!-- Close wrapper -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Simplified Admin JS
    function updateLiveViewers() {
        const topEl = document.getElementById('adminLiveViewers');
        if (!topEl) return;

        let current = parseInt(topEl.innerText) || 42;
        const change = Math.floor(Math.random() * 5) - 2; // -2 to +2
        const newValue = Math.max(12, Math.min(95, current + change));

        topEl.innerText = newValue;
    }

    // Update every 8 seconds
    setInterval(updateLiveViewers, 8000);
</script>
</body>

</html>