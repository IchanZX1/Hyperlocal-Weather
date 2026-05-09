<header class="topbar">
    <div class="logo">ZoomWeather</div>
    <div class="loc-pill" onclick="toggleModal()">
        <span>📍</span>
        <span><b><?= htmlspecialchars($kota) ?></b>, <?= htmlspecialchars($wilayah) ?></span>
        <span style="margin-left: 10px; opacity: 0.5; font-size: 0.7rem;">▼</span>
    </div>
    <div style="text-align: right;">
        <div id="live-clock" style="font-size: 1.1rem; font-weight: 600; letter-spacing: 1px; color: #fff;">
            <?= date('H:i') ?></div>
        <div style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; margin-top: 2px;">
            <?= date('d M Y') ?></div>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('live-clock').textContent = `${hours}:${minutes}`;
    }
    setInterval(updateClock, 10000);
</script>