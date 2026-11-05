<div class="topbar" style="display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Laporan Masuk</h2>
    </div>
    <a href="<?= BASE_URL ?>/admin/exportLaporan" class="btn-brutal" style="text-decoration: none; padding: 6px 12px; background: var(--warning); font-size: 0.9rem; font-weight: bold; color: var(--black); display: flex; align-items: center; gap: 6px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        Download Rekap
    </a>
</div>

<div style="padding: 24px; padding-bottom: 100px;">
    <?php Session::flash(); ?>

    <?php if (empty($alerts)): ?>
        <div style="text-align: center; padding: 40px 20px; border: var(--border-thick); border-radius: 8px; border-style: dashed;">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <h3>Mantap!</h3>
            <p style="color: #666;">Semua armada sehat hari ini. Tidak ada laporan kerusakan.</p>
        </div>
    <?php else: ?>
        <?php 
        // Buat map ID -> Plat Nomor
        $platMap = [];
        foreach($armada_list as $a) {
            $platMap[$a['id']] = $a['no_polisi'] . ' (' . $a['merk'] . ')';
        }
        ?>
        <?php foreach($alerts as $alert): ?>
        <div style="background: <?= ($alert['tipe'] ?? '') == 'urgent' ? 'var(--danger)' : 'var(--white)' ?>; color: <?= ($alert['tipe'] ?? '') == 'urgent' ? 'white' : 'var(--dark)' ?>; border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 16px; margin-bottom: 16px; border-radius: 8px; <?= $alert['status'] == 'resolved' ? 'opacity: 0.6;' : '' ?>">
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                <h4 style="font-size: 1.2rem; margin: 0;"><?= htmlspecialchars($platMap[$alert['armada_id']] ?? $alert['armada_id'] ?? 'Tidak diketahui') ?></h4>
                <span style="background: <?= $alert['status'] == 'resolved' ? 'var(--primary)' : 'var(--warning)' ?>; color: var(--black); padding: 4px 8px; border: 2px solid var(--black); font-size: 0.7rem; font-weight: bold; border-radius: 4px;">
                    <?= strtoupper($alert['status']) ?>
                </span>
            </div>
            
            <p style="font-size: 1rem; margin-bottom: 12px; font-weight: bold;">
                "<?= htmlspecialchars($alert['keterangan'] ?? '') ?>"
            </p>

            <?php if (!empty($alert['foto'])): ?>
                <div style="margin-bottom: 12px;">
                    <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/uploads/alerts/<?= htmlspecialchars($alert['foto']) ?>" style="max-width: 100%; height: auto; border: 2px solid <?= ($alert['tipe'] ?? '') == 'urgent' ? 'var(--white)' : 'var(--black)' ?>; border-radius: 4px; max-height: 200px; object-fit: cover;">
                </div>
            <?php endif; ?>
            
            <p style="font-size: 0.8rem; margin-bottom: 12px; opacity: 0.9;">
                Dilaporkan oleh: <?= htmlspecialchars($alert['driver_name'] ?? 'Driver') ?><br>
                <?php if(!empty($alert['km_saat_ini'])): ?>
                Odometer: <strong><?= number_format((int)$alert['km_saat_ini'], 0, ',', '.') ?> KM</strong><br>
                <?php endif; ?>
                Waktu: <?= date('d M Y, H:i', strtotime($alert['created_at'])) ?>
                
                <?php if(!empty($alert['gps_lat']) && !empty($alert['gps_lng'])): ?>
                <br><a href="https://maps.google.com/?q=<?= $alert['gps_lat'] ?>,<?= $alert['gps_lng'] ?>" target="_blank" style="color: var(--primary); text-decoration: underline; font-weight: bold; margin-top: 4px; display: inline-block;">📍 Buka Peta Lokasi Darurat</a>
                <?php endif; ?>
            </p>
            
            <?php if ($alert['status'] !== 'resolved'): ?>
            <div style="margin-top: 12px; padding-top: 12px; border-top: 2px dashed var(--black);">
                <form action="<?= BASE_URL ?>/admin/selesaiLaporan/<?= $alert['id'] ?>" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 8px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: bold; color: <?= ($alert['tipe'] ?? '') == 'urgent' ? 'white' : 'var(--dark)' ?>;">Total Biaya (Rp):</label>
                        <input type="number" name="biaya" placeholder="Misal: 350000" required style="width: 100%; padding: 8px; border: 2px solid var(--black); background: var(--light); color: var(--dark);">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: bold; color: <?= ($alert['tipe'] ?? '') == 'urgent' ? 'white' : 'var(--dark)' ?>;">Foto Nota / Kuitansi:</label>
                        <input type="file" name="nota" accept="image/*" style="width: 100%; padding: 8px; border: 2px solid var(--black); background: var(--light); color: var(--dark);">
                    </div>
                    <button type="submit" class="btn-brutal" style="margin-top: 8px; padding: 8px 16px; background: var(--primary); color: var(--black); width: 100%; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Tandai Beres & Simpan Biaya
                    </button>
                </form>
            </div>
            <?php else: ?>
                <?php if(!empty($alert['biaya'])): ?>
                    <p style="color: <?= ($alert['tipe'] ?? '') == 'urgent' ? 'white' : 'var(--dark)' ?>; font-size: 0.9rem; margin-top: 8px; font-weight: bold;">Biaya: Rp <?= number_format((int)$alert['biaya'], 0, ',', '.') ?></p>
                <?php endif; ?>
                <?php if(!empty($alert['nota'])): ?>
                    <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/uploads/servis/<?= htmlspecialchars($alert['nota']) ?>" style="max-width: 100%; border: 2px solid var(--black); border-radius: 4px; margin-top: 8px; max-height: 150px; object-fit: cover;">
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
