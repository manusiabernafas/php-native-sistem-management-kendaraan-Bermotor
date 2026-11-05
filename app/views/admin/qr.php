<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin/armada" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">QR Armada</h2>
    </div>
</div>

<div style="padding: 24px; padding-bottom: 100px; text-align: center;">

    <h2 style="font-size: 2rem; margin-bottom: 8px;"><?= htmlspecialchars($armada['no_polisi']) ?></h2>
    <p style="color: #666; margin-bottom: 30px;"><?= htmlspecialchars($armada['merk']) ?> (<?= strtoupper($armada['jenis']) ?>)</p>

    <!-- QR UMUM -->
    <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="margin-bottom: 16px; color: var(--primary);">QR Code Umum (Publik)</h3>
        <p style="font-size: 0.9rem; color: #666; margin-bottom: 16px;">Tanpa login. Menampilkan status kendaraan untuk masyarakat/karyawan.</p>
        
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($url_umum) ?>" style="border: 4px solid var(--black); border-radius: 8px; margin-bottom: 16px;">
        
        <br>
        <button class="btn-brutal" style="background: var(--primary); padding: 8px 16px; font-size: 0.9rem;" onclick="window.print()">🖨️ Cetak</button>
    </div>

    <!-- QR DRIVER -->
    <div style="background: var(--light); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px; border-radius: 8px;">
        <h3 style="margin-bottom: 16px; color: var(--danger);">QR Code Driver (Khusus Supir)</h3>
        <p style="font-size: 0.9rem; color: #666; margin-bottom: 16px;">Wajib login. Ditempel tersembunyi di dashboard supir untuk laporan cepat.</p>
        
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($url_driver) ?>" style="border: 4px solid var(--danger); border-radius: 8px; margin-bottom: 16px;">
        
        <br>
        <button class="btn-brutal" style="background: var(--warning); padding: 8px 16px; font-size: 0.9rem;" onclick="window.print()">🖨️ Cetak</button>
    </div>

</div>
