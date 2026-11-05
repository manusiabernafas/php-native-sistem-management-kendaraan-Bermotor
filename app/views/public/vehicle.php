<div class="topbar">
    <div style="display: flex; align-items: center;">
        <h2 style="font-size: 1.4rem; margin: 0;">Info Kendaraan</h2>
    </div>
</div>

<div style="padding: 24px; padding-bottom: 80px;">

    <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px; border-radius: 8px; margin-bottom: 30px; text-align: center;">
        <h1 style="font-size: 2.5rem; margin-bottom: 8px;"><?= htmlspecialchars($armada['no_polisi']) ?></h1>
        <p style="color: #666; font-size: 1.2rem; margin-bottom: 16px;">
            <?= htmlspecialchars($armada['merk']) ?> - <?= htmlspecialchars($armada['tahun']) ?>
        </p>
        
        <div style="display: inline-block; background: <?= $armada['status'] == 'tersedia' ? 'var(--primary)' : 'var(--danger)' ?>; padding: 8px 16px; border: 3px solid var(--black); font-size: 1.2rem; font-weight: bold; border-radius: 8px; color: <?= $armada['status'] == 'tersedia' ? 'var(--black)' : 'var(--white)' ?>;">
            STATUS: <?= strtoupper($armada['status']) ?>
        </div>
    </div>

    <h3 style="margin-bottom: 16px;">Riwayat Perawatan (3 Terakhir)</h3>
    <?php 
    $count = 0;
    foreach($riwayat_servis as $s): 
        if ($s['status'] !== 'selesai' || $count >= 3) continue;
        $count++;
    ?>
    <div style="background: var(--light); border: var(--border-thick); padding: 16px; margin-bottom: 12px; border-radius: 8px;">
        <p style="margin: 0; font-weight: bold;"><?= htmlspecialchars($s['tanggal']) ?></p>
        <p style="margin: 0; color: #555;"><?= htmlspecialchars($s['keterangan']) ?></p>
    </div>
    <?php endforeach; ?>
    
    <?php if($count == 0): ?>
        <p style="color: #666; font-style: italic;">Belum ada riwayat servis yang tercatat.</p>
    <?php endif; ?>

</div>
