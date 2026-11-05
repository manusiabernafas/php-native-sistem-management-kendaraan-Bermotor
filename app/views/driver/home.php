<div class="topbar">
    <div style="display: flex; align-items: center; gap: 12px;">
        <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" width="32" height="32">
        <h2 style="margin: 0;">Driver</h2>
    </div>
    <a href="<?= BASE_URL ?>/auth/logout" style="text-decoration: none; color: var(--danger); font-weight: bold;">Logout</a>
</div>

<div style="padding: 24px; text-align: center;">
    
    <div style="margin-bottom: 40px;">
        <h1 style="font-size: 2rem; margin-bottom: 8px;">Halo, <?= htmlspecialchars(Session::get('name')) ?></h1>
        <p style="color: #666;">Semoga perjalanan Anda aman hari ini.</p>
    </div>

    <?php Session::flash(); ?>

    <?php 
        // Cek apakah supir ini sedang membawa armada
        $mobil_dipakai = null;
        foreach ($armada_list as $a) {
            if (($a['dipakai_oleh'] ?? '') === Session::get('name')) {
                $mobil_dipakai = $a;
                break;
            }
        }
    ?>

    <!-- Modul Check-in Kendaraan -->
    <?php if(!$mobil_dipakai): ?>
    <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="margin-bottom: 12px; font-family: var(--font-display);">Mulai Shift Baru?</h3>
        <p style="color: #666; margin-bottom: 20px; font-size: 0.9rem;">Scan stiker QR di kaca mobil untuk melakukan Check-in dan Inspeksi Harian.</p>
        
        <a href="<?= BASE_URL ?>/driver/scanner" style="text-decoration: none;">
            <button class="btn-brutal" style="width: 100%; height: 80px; font-size: 1.5rem; background: var(--warning); color: #000000 !important; cursor: pointer; font-family: var(--font-display); display: flex; align-items: center; justify-content: center; gap: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                Mulai Shift
            </button>
        </a>
    </div>
    <?php else: ?>
    <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="margin-bottom: 12px; font-family: var(--font-display); color: var(--primary); display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            Shift Anda Sedang Aktif
        </h3>
        <p style="color: #666; margin-bottom: 20px; font-size: 0.9rem;">Anda saat ini sedang terdaftar mengoperasikan armada berikut:</p>
        
        <div style="background: var(--light); border: 3px solid var(--black); padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; box-shadow: 4px 4px 0px var(--black);">
            <p style="margin: 0; font-size: 0.85rem; font-weight: bold; text-transform: uppercase; color: #555;">No. Polisi Armada</p>
            <h2 style="margin: 4px 0 8px 0; font-size: 2.2rem; font-family: var(--font-display); letter-spacing: 1px;"><?= htmlspecialchars($mobil_dipakai['no_polisi']) ?></h2>
            <p style="margin: 0; font-size: 0.9rem; color: #666;"><?= htmlspecialchars($mobil_dipakai['merk']) ?> (<?= htmlspecialchars($mobil_dipakai['tahun']) ?>)</p>
        </div>
 
        <form action="<?= BASE_URL ?>/driver/checkout" method="POST">
            <input type="hidden" name="armada_id" value="<?= htmlspecialchars($mobil_dipakai['id']) ?>">
            <button type="submit" class="btn-brutal" style="width: 100%; background: var(--danger); color: white !important; padding: 16px; font-size: 1.1rem; font-weight: bold; font-family: var(--font-display); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;" onclick="return confirm('Apakah Anda yakin ingin mengakhiri shift dan check-out dari armada ini?');">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                Akhiri Shift (Check-Out)
            </button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Tombol Panic Merah Raksasa -->
    <div style="margin-top: 40px; background: var(--white); border: var(--border-thick); padding: 24px; border-radius: 8px;">
        <h3 style="margin-bottom: 16px; color: var(--danger);">KONDISI DARURAT / KERUSAKAN?</h3>
        
        <?php if($mobil_dipakai): ?>
            <form action="<?= BASE_URL ?>/driver/report_page_redirect" method="POST">
                <input type="hidden" name="armada_id" value="<?= htmlspecialchars($mobil_dipakai['id']) ?>">
                
                <button type="submit" class="btn-brutal" style="width: 100%; padding: 30px 20px; background: var(--danger); border-radius: 12px; font-size: 1.5rem; color: var(--white); text-transform: uppercase; animation: pulse 2s infinite; display: flex; align-items: center; justify-content: center; gap: 12px;" onclick="if(navigator.vibrate) navigator.vibrate([500, 200, 500]);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    LAPOR DARURAT
                </button>
            </form>
        <?php else: ?>
            <div style="background: var(--light); border: 2px dashed var(--danger); padding: 20px; border-radius: 8px; text-align: center; color: var(--danger); font-weight: bold;">
                ⚠️ Anda harus "Mulai Shift" (Scan QR) terlebih dahulu untuk dapat mengirim laporan darurat armada.
            </div>
        <?php endif; ?>
    </div>

</div>

</div>

<style>
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7); }
    70% { box-shadow: 0 0 0 15px rgba(255, 71, 87, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 71, 87, 0); }
}
</style>
