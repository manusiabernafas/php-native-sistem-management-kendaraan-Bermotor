<div class="topbar">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="<?= BASE_URL ?>/admin" style="text-decoration: none; color: var(--black); margin-right: 4px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" width="32" height="32">
        <h2 style="font-size: 1.4rem; margin: 0;">Kelola Armada</h2>
    </div>
</div>

<div style="padding: 24px; padding-bottom: 100px;">
    
    <?php Session::flash(); ?>

    <div style="display: flex; gap: 10px; margin-bottom: 16px;">
        <a href="<?= BASE_URL ?>/admin/tambahArmada" style="flex: 1; text-decoration: none;">
            <button class="btn-brutal" style="width: 100%; height: 100%; background: var(--primary); padding: 12px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                Tambah Baru
            </button>
        </a>
        <form action="<?= BASE_URL ?>/admin/importExcel" method="POST" enctype="multipart/form-data" style="flex: 1; display: flex;">
            <input type="file" name="file_excel" accept=".csv" required style="display: none;" id="fileCsv">
            <button type="button" class="btn-brutal" style="flex: 1; background: var(--warning); display: flex; align-items: center; justify-content: center; gap: 6px;" onclick="document.getElementById('fileCsv').click();">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><path d="M2 10h20"></path></svg>
                Pilih CSV
            </button>
            <button type="submit" class="btn-brutal" style="background: var(--black); color: white; margin-left: 5px;">
                Upload
            </button>
        </form>
    </div>

    <!-- Tombol Download Template & Cetak Massal QR -->
    <div style="display: flex; gap: 10px; margin-bottom: 24px;">
        <a href="<?= BASE_URL ?>/admin/downloadTemplateArmada" style="flex: 1; text-decoration: none;">
            <button type="button" class="btn-brutal" style="width: 100%; height: 100%; background: var(--white); padding: 12px; font-weight: bold; cursor: pointer; color: var(--black); display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Unduh Template CSV
            </button>
        </a>
        <a href="<?= BASE_URL ?>/admin/cetakSemuaQr" style="flex: 1; text-decoration: none;">
            <button type="button" class="btn-brutal" style="width: 100%; height: 100%; background: var(--primary); padding: 12px; font-weight: bold; cursor: pointer; color: var(--black); display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Massal QR
            </button>
        </a>
    </div>

    <?php if (empty($armada)): ?>
        <div style="text-align: center; padding: 40px 20px; border: var(--border-thick); border-radius: 8px; border-style: dashed;">
            <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" width="64" height="64" style="margin-bottom: 16px; opacity: 0.5;">
            <h3>Belum ada data armada.</h3>
            <p style="color: #666;">Klik tombol di atas untuk menambahkan mobil baru.</p>
        </div>
    <?php else: ?>
        <?php foreach($armada as $car): ?>
        <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 16px; margin-bottom: 16px; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h3 style="font-size: 1.2rem;"><?= htmlspecialchars($car['no_polisi']) ?></h3>
                <span style="background: <?= $car['status'] == 'tersedia' ? 'var(--primary)' : 'var(--warning)' ?>; padding: 4px 8px; border: 2px solid var(--black); font-size: 0.8rem; font-weight: bold; border-radius: 4px;">
                    <?= strtoupper($car['status']) ?>
                </span>
            </div>

            <?php 
                // Logika Peringatan Pajak (H-30)
                $pajakDate = $car['pajak_date'] ?? null;
                if ($pajakDate) {
                    $daysToPajak = (strtotime($pajakDate) - time()) / (60 * 60 * 24);
                    if ($daysToPajak >= 0 && $daysToPajak <= 30) {
                        echo '<div style="background: var(--warning); padding: 6px; border: 2px solid var(--black); font-size: 0.8rem; font-weight: bold; margin-bottom: 8px;">⚠️ Peringatan: Pajak Mati dlm ' . ceil($daysToPajak) . ' Hari!</div>';
                    } else if ($daysToPajak < 0) {
                        echo '<div style="background: var(--danger); color: white; padding: 6px; border: 2px solid var(--black); font-size: 0.8rem; font-weight: bold; margin-bottom: 8px;">🚨 PAJAK SUDAH MATI!</div>';
                    }
                }

                // Logika Kelelahan Sopir (>12 Jam)
                if (!empty($car['dipakai_oleh'])) {
                    $checkin = $car['checkin_time'] ?? 0;
                    if ($checkin > 0) {
                        $hours = (time() - $checkin) / 3600;
                        if ($hours > 12) {
                            echo '<div style="background: var(--danger); color: white; padding: 6px; border: 2px solid var(--black); font-size: 0.8rem; font-weight: bold; margin-bottom: 8px; animation: flash 1s infinite;">🚨 DILARANG JALAN: Sopir Bertugas > 12 Jam!</div>';
                        }
                    }
                }
            ?>

            <p style="color: #666; font-size: 0.9rem; margin-bottom: 12px;">
                <?= htmlspecialchars($car['merk']) ?> (<?= htmlspecialchars($car['tahun']) ?>)<br>
                Tipe: <?= htmlspecialchars($car['jenis']) ?><br>
                📅 <strong>Pajak (1 Thn):</strong> <?= !empty($car['pajak_date']) ? date('d M Y', strtotime($car['pajak_date'])) : '-' ?><br>
                💳 <strong>STNK (5 Thn):</strong> <?= !empty($car['stnk_date']) ? date('d M Y', strtotime($car['stnk_date'])) : '-' ?>
            </p>
            <div style="display: flex; gap: 8px;">
                <a href="<?= BASE_URL ?>/admin/editArmada/<?= $car['id'] ?>" style="flex: 1; text-decoration: none;">
                    <button style="width: 100%; padding: 8px; background: var(--warning); border: var(--border-thick); font-weight: bold; cursor: pointer;">Edit</button>
                </a>
                <a href="<?= BASE_URL ?>/admin/qr/<?= $car['id'] ?>" style="flex: 1; text-decoration: none;">
                    <button style="width: 100%; padding: 8px; background: var(--white); border: var(--border-thick); font-weight: bold; cursor: pointer;">QR Code</button>
                </a>
                <a href="<?= BASE_URL ?>/admin/hapusArmada/<?= $car['id'] ?>" onclick="return confirm('Yakin ingin menghapus <?= $car['no_polisi'] ?>?');" style="flex: 1; text-decoration: none;">
                    <button style="width: 100%; padding: 8px; background: var(--danger); color: white; border: var(--border-thick); font-weight: bold; cursor: pointer;">Hapus</button>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
