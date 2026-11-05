<div class="topbar">
    <div style="display: flex; align-items: center; gap: 12px;">
        <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" width="32" height="32">
        <h2 style="margin: 0;">SIMANDA</h2>
    </div>
    <div>Dinkes Bantul</div>
</div>

<div style="padding: 24px; padding-bottom: 80px;">
    <h1 style="margin-bottom: 16px; font-size: 2rem;">Cek Status Armada</h1>
    <p style="margin-bottom: 24px; color: #666;">
        Sistem Informasi Manajemen Armada Dinas Kesehatan Kabupaten Bantul. Silakan scan kode QR yang ada di kaca kendaraan untuk melihat status.
    </p>

    <!-- Camera / Scanner Container -->
    <div id="scanner-wrapper" style="display: none; width: 100%; max-width: 400px; background: var(--black); border: var(--border-thick); box-shadow: var(--shadow-hard); border-radius: 12px; overflow: hidden; margin: 0 auto 24px auto;">
        <div id="reader" style="width: 100%;"></div>
    </div>

    <div style="background: var(--warning); border: var(--border-thick); padding: 20px; box-shadow: var(--shadow-hard); margin-bottom: 24px;" id="info-box">
        <h3 style="margin-bottom: 8px;">ℹ️ Informasi</h3>
        <p style="margin: 0;">Halaman ini dapat diakses oleh publik tanpa perlu login.</p>
    </div>

    <button id="btn-scan" class="btn-brutal" style="width: 100%; text-align: center; font-size: 1.1rem; background: var(--primary); margin-bottom: 16px;">
        📷 Buka Kamera (Scan QR)
    </button>
    
    <button id="btn-stop-scan" class="btn-brutal" style="width: 100%; text-align: center; font-size: 1.1rem; background: var(--danger); display: none;">
        🛑 Hentikan Scanner
    </button>
</div>

<!-- HTML5 QR Code Library -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const btnScan = document.getElementById('btn-scan');
    const btnStopScan = document.getElementById('btn-stop-scan');
    const scannerWrapper = document.getElementById('scanner-wrapper');
    const infoBox = document.getElementById('info-box');
    
    let html5QrCode = null;
    
    btnScan.addEventListener('click', function() {
        // Tampilkan scanner, sembunyikan info box
        scannerWrapper.style.display = 'block';
        infoBox.style.display = 'none';
        btnScan.style.display = 'none';
        btnStopScan.style.display = 'block';
        
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }
        
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (navigator.vibrate) navigator.vibrate([100, 50, 100]); // double vibrate success
            
            // Berhenti scan & redirect ke url hasil scan
            html5QrCode.stop().then(() => {
                window.location.href = decodedText;
            }).catch(err => {
                window.location.href = decodedText;
            });
        };
        
        const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
        
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
        .catch(err => {
            console.error("Kamera gagal diakses:", err);
            alert("Gagal mengakses kamera! Pastikan Anda memberi izin akses kamera di browser.");
            // Reset UI
            scannerWrapper.style.display = 'none';
            infoBox.style.display = 'block';
            btnScan.style.display = 'block';
            btnStopScan.style.display = 'none';
        });
    });
    
    btnStopScan.addEventListener('click', function() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                // Reset UI
                scannerWrapper.style.display = 'none';
                infoBox.style.display = 'block';
                btnScan.style.display = 'block';
                btnStopScan.style.display = 'none';
            }).catch(err => {
                console.error("Gagal menghentikan scanner: ", err);
            });
        }
    });
});
</script>
