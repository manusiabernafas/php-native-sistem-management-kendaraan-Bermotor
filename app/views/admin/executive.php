<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Analitik Eksekutif</h2>
    </div>
</div>

<div style="padding: 24px; padding-bottom: 80px;">

    <!-- Cost per KM Widget -->
    <div style="background: var(--dark); color: white; border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px; border-radius: 8px; margin-bottom: 24px; text-align: center;">
        <p style="margin: 0; font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px;">Biaya Operasional per KM</p>
        <h1 style="margin: 8px 0; font-size: 3rem; color: var(--primary);">Rp <?= number_format($costPerKM, 0, ',', '.') ?></h1>
        <p style="margin: 0; font-size: 0.8rem; opacity: 0.8;">Total Biaya: Rp <?= number_format($totalBiaya, 0, ',', '.') ?> / Total KM: <?= number_format($totalKM, 0, ',', '.') ?></p>
    </div>

    <!-- Rasio Kendaraan Widget -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
        <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div style="margin-bottom: 8px; color: var(--primary); height: 48px; display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h2 style="font-size: 2rem; margin: 0; color: var(--primary);"><?= $mobilSehat ?></h2>
            <p style="margin: 4px 0 0 0; font-size: 0.8rem; font-weight: bold;">SEHAT</p>
        </div>
        <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div style="margin-bottom: 8px; color: var(--danger); height: 48px; display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            </div>
            <h2 style="font-size: 2rem; margin: 0; color: var(--danger);"><?= $mobilSakit ?></h2>
            <p style="margin: 4px 0 0 0; font-size: 0.8rem; font-weight: bold;">SAKIT / SERVIS</p>
        </div>
    </div>

    <!-- Top 3 Rusak Widget -->
    <h3 style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        Top 3 Paling Sering Rusak
    </h3>
    
    <?php if (empty($top3Rusak)): ?>
        <div style="background: var(--light); border: 2px dashed var(--black); padding: 20px; text-align: center; border-radius: 8px;">
            Belum ada data laporan kerusakan.
        </div>
    <?php else: ?>
        <?php 
        $rank = 1;
        $colors = ['var(--danger)', 'var(--warning)', 'var(--primary)'];
        foreach($top3Rusak as $armadaId => $count): 
            $color = $colors[$rank-1] ?? 'var(--dark)';
            // Cari data plat mobil
            $plat = $armadaId;
            foreach($armadas as $a) {
                if ($a['id'] == $armadaId) {
                    $plat = $a['no_polisi']; break;
                }
            }
        ?>
        <div style="background: var(--white); border: var(--border-thick); box-shadow: 4px 4px 0px var(--black); padding: 16px; border-radius: 8px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 40px; height: 40px; border-radius: 20px; background: <?= $color ?>; border: 2px solid var(--black); display: flex; align-items: center; justify-content: center; font-weight: bold; color: <?= $rank == 1 ? 'white' : 'var(--dark)' ?>;">
                    #<?= $rank ?>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.2rem;"><?= htmlspecialchars($plat) ?></h3>
                    <p style="margin: 0; font-size: 0.8rem; color: #666;">ID: <?= htmlspecialchars($armadaId) ?></p>
                </div>
            </div>
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--danger);">
                <?= $count ?>x
            </div>
        </div>
        <?php $rank++; endforeach; ?>
    <?php endif; ?>

</div>
