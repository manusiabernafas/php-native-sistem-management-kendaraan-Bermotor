<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/driver" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Mulai Shift Baru</h2>
    </div>
</div>

<div style="padding: 12px; padding-bottom: 60px;">

    <div style="margin-bottom: 16px; text-align: center; max-width: 380px; margin: 0 auto 16px auto;">
        <p style="margin: 0; font-size: 0.95rem; font-family: var(--font-display); color: #555;">Armada Anda:</p>
        <h2 style="margin: 2px 0; font-size: 2rem; color: var(--primary); font-family: var(--font-display); letter-spacing: 1px; line-height: 1.1;"><?= htmlspecialchars($armada['no_polisi']) ?></h2>
        <p style="margin: 0; color: var(--dark); font-size: 0.85rem; font-weight: bold; opacity: 0.8;"><?= htmlspecialchars($armada['merk']) ?></p>
    </div>

    <form action="<?= BASE_URL ?>/driver/checkin" method="POST" style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 16px; border-radius: 8px; display: flex; flex-direction: column; gap: 12px; max-width: 380px; margin: 0 auto;">
        
        <input type="hidden" name="armada_id" value="<?= htmlspecialchars($armada['id']) ?>">
        
        <h3 style="margin-bottom: 4px; border-bottom: 2px dashed var(--black); padding-bottom: 8px; font-family: var(--font-display); font-size: 1.1rem;">Inspeksi Harian</h3>

        <!-- Compact Row 1 -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
            <label style="font-weight: 700; font-size: 0.9rem; color: var(--dark); flex: 1; white-space: nowrap;">Rem Berfungsi Baik?</label>
            <select name="cek_rem" style="width: 110px; height: 38px; padding: 6px 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 0.9rem; background-color: var(--light); border-radius: 4px;">
                <option value="Ya">Ya</option>
                <option value="Tidak">Tidak</option>
            </select>
        </div>

        <!-- Compact Row 2 -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
            <label style="font-weight: 700; font-size: 0.9rem; color: var(--dark); flex: 1; white-space: nowrap;">Lampu & Sein Nyala?</label>
            <select name="cek_lampu" style="width: 110px; height: 38px; padding: 6px 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 0.9rem; background-color: var(--light); border-radius: 4px;">
                <option value="Ya">Ya</option>
                <option value="Tidak">Tidak</option>
            </select>
        </div>

        <!-- Compact Row 3 -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
            <label style="font-weight: 700; font-size: 0.9rem; color: var(--dark); flex: 1; white-space: nowrap;">Bensin Cukup?</label>
            <select name="cek_bensin" style="width: 110px; height: 38px; padding: 6px 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 0.9rem; background-color: var(--light); border-radius: 4px;">
                <option value="Ya">Ya</option>
                <option value="Tidak">Tidak</option>
            </select>
        </div>

        <!-- Compact Row 4: Odometer -->
        <div style="border-top: 2px dashed var(--black); padding-top: 10px; margin-top: 4px;">
            <?php $km_saat_ini = (int)($armada['km_saat_ini'] ?? 0); ?>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-size: 0.9rem; text-transform: uppercase; font-family: var(--font-display); letter-spacing: 0.5px;">Odometer (KM Saat Ini) <span style="color: var(--danger);">*</span></label>
            <input type="number" name="km_saat_ini" min="<?= $km_saat_ini ?>" placeholder="Contoh: <?= htmlspecialchars((string)$km_saat_ini) ?>" required style="width: 100%; height: 42px; padding: 8px 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 0.95rem; background: var(--light); border-radius: 4px;">
            <p style="font-size: 0.8rem; color: #666; margin-top: 4px; font-weight: bold;">KM Terakhir: <?= number_format($km_saat_ini, 0, ',', '.') ?> KM. Input tidak boleh kurang dari KM terakhir.</p>
        </div>

        <button type="submit" class="btn-brutal" style="width: 100%; font-size: 1.1rem; background: var(--primary); padding: 12px; margin-top: 6px; font-weight: bold; border-radius: 4px; cursor: pointer; transition: transform 0.1s; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            SELESAI & MULAI JALAN
        </button>
    </form>

</div>
