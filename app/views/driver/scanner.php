<div class="topbar">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="javascript:history.back()" style="text-decoration: none; color: var(--black); margin-right: 4px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Scanner PWA</h2>
    </div>
</div>

<div style="padding: 24px; display: flex; flex-direction: column; align-items: center;">
    
    <div style="text-align: center; margin-bottom: 24px;">
        <h3 style="font-size: 1.2rem; margin-bottom: 8px;">Arahkan Kamera ke QR Code</h3>
        <p style="color: #666; font-size: 0.9rem;">Pastikan kode QR berada di dalam kotak scanner.</p>
    </div>

    <!-- Scanner Container -->
    <div style="width: 100%; max-width: 400px; background: var(--black); border: var(--border-thick); box-shadow: var(--shadow-hard); border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
        <div id="reader" style="width: 100%;"></div>
    </div>

    <div id="scan-result" style="display: none; width: 100%; background: var(--primary); padding: 16px; border: var(--border-thick); box-shadow: var(--shadow-hard); border-radius: 8px; text-align: center; margin-bottom: 24px;">
        <p style="margin: 0; font-weight: bold; color: var(--black);">Berhasil! Mengalihkan...</p>
    </div>

</div>

<!-- HTML5 QR Code Library -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const html5QrCode = new Html5Qrcode("reader");
    
    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        // Stop scanning
        html5QrCode.stop().then((ignore) => {
            // Tampilkan toast success
            document.getElementById('scan-result').style.display = 'block';
            if (navigator.vibrate) navigator.vibrate([100, 50, 100]); // double vibrate success
            
            // Redirect ke URL yang didapat (jika valid)
            setTimeout(() => {
                window.location.href = decodedText;
            }, 1000);
            
        }).catch((err) => {
            console.log("Stop failed: ", err);
        });
    };
    
    const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

    // Start scanner using back camera
    html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
    .catch((err) => {
        console.log("Kamera gagal diakses:", err);
        alert("Gagal mengakses kamera! Pastikan Anda memberi izin kamera atau gunakan perangkat dengan kamera aktif.");
    });
});
</script>
