<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin/armada" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0; font-family: var(--font-display);"><?= isset($armada) ? 'Edit' : 'Tambah' ?> Armada</h2>
    </div>
</div>

<div style="padding: 16px; padding-bottom: 80px;">
    <form action="<?= BASE_URL ?>/admin/simpanArmada" method="POST" style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; display: flex; flex-direction: column; gap: 16px;">
        
        <?php if(isset($armada)): ?>
            <input type="hidden" name="id" value="<?= $armada['id'] ?>">
        <?php endif; ?>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Nomor Polisi / Kode Aset <span style="color: var(--danger);">*</span></label>
            <input type="text" name="no_polisi" value="<?= isset($armada) ? htmlspecialchars($armada['no_polisi'] ?? '') : '' ?>" placeholder="Contoh: AB 1234 XY atau SPD-01" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px;">
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Merk / Model <span style="color: var(--danger);">*</span></label>
            <input type="text" name="merk" value="<?= isset($armada) ? htmlspecialchars($armada['merk'] ?? '') : '' ?>" placeholder="Contoh: Toyota HiAce" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px;">
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Tipe Kendaraan</label>
            <select name="jenis" style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background-color: var(--light); border-radius: 4px; height: 48px;">
                <option value="Ambulance" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Ambulance') ? 'selected' : '' ?>>Ambulance</option>
                <option value="Mobil Jenazah" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Mobil Jenazah') ? 'selected' : '' ?>>Mobil Jenazah</option>
                <option value="Mobil Operasional" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Mobil Operasional') ? 'selected' : '' ?>>Mobil Operasional</option>
                <option value="Roda 2 Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 2 Bermesin') ? 'selected' : '' ?>>Roda 2 Bermesin (Motor)</option>
                <option value="Roda 2 Tidak Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 2 Tidak Bermesin') ? 'selected' : '' ?>>Roda 2 Tidak Bermesin (Sepeda)</option>
                <option value="Roda 3 Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 3 Bermesin') ? 'selected' : '' ?>>Roda 3 Bermesin (Motor Tiga Roda)</option>
                <option value="Roda 3 Tidak Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 3 Tidak Bermesin') ? 'selected' : '' ?>>Roda 3 Tidak Bermesin</option>
                <option value="Roda 4 Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 4 Bermesin') ? 'selected' : '' ?>>Roda 4 Bermesin (Mobil Dinas/SUV)</option>
                <option value="Roda 4 Tidak Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 4 Tidak Bermesin') ? 'selected' : '' ?>>Roda 4 Tidak Bermesin</option>
                <option value="Roda 6 Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 6 Bermesin') ? 'selected' : '' ?>>Roda 6 Bermesin (Truk/Bus)</option>
                <option value="Roda 6 Tidak Bermesin" <?= (isset($armada) && ($armada['jenis'] ?? '') == 'Roda 6 Tidak Bermesin') ? 'selected' : '' ?>>Roda 6 Tidak Bermesin</option>
            </select>
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Tahun Pembuatan <span style="color: var(--danger);">*</span></label>
            <input type="number" name="tahun" value="<?= isset($armada) ? htmlspecialchars($armada['tahun'] ?? '') : '' ?>" placeholder="Contoh: 2022" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px;">
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Tgl Pajak (1 Thn)</label>
            <input type="date" name="pajak_date" value="<?= isset($armada) ? htmlspecialchars($armada['pajak_date'] ?? '') : '' ?>" style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px; height: 48px;">
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Tgl STNK (5 Thn)</label>
            <input type="date" name="stnk_date" value="<?= isset($armada) ? htmlspecialchars($armada['stnk_date'] ?? '') : '' ?>" style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px; height: 48px;">
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Status Operasional</label>
            <select name="status" style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background-color: var(--light); border-radius: 4px; height: 48px;">
                <option value="tersedia" <?= (isset($armada) && $armada['status'] == 'tersedia') ? 'selected' : '' ?>>Tersedia (Aman)</option>
                <option value="service" <?= (isset($armada) && $armada['status'] == 'service') ? 'selected' : '' ?>>Sedang Servis</option>
                <option value="rusak" <?= (isset($armada) && $armada['status'] == 'rusak') ? 'selected' : '' ?>>Rusak / Mogok</option>
            </select>
        </div>

        <button type="submit" class="btn-brutal" style="width: 100%; font-size: 1.2rem; background: var(--primary); padding: 14px; margin-top: 10px; font-weight: bold; border-radius: 4px; cursor: pointer; transition: transform 0.1s; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Simpan Data
        </button>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const platInput = document.querySelector('input[name="no_polisi"]');
    const jenisSelect = document.querySelector('select[name="jenis"]');
    
    if (platInput) {
        const isPlatNomorNeeded = () => {
            if (!jenisSelect) return true;
            const val = jenisSelect.value;
            // Hanya gunakan format plat nomor jika bermesin roda 2/3/4/6 atau mobil dinas
            return ['Ambulance', 'Mobil Jenazah', 'Mobil Operasional', 'Roda 2 Bermesin', 'Roda 3 Bermesin', 'Roda 4 Bermesin', 'Roda 6 Bermesin'].includes(val);
        };

        platInput.addEventListener('input', function(e) {
            // Simpan posisi kursor agar tidak loncat saat mengetik di tengah
            let cursorPosition = e.target.selectionStart;
            let originalLength = e.target.value.length;
            
            // Konversi ke Huruf Kapital penuh
            let val = e.target.value.toUpperCase();
            
            if (isPlatNomorNeeded()) {
                // Bersihkan semua karakter selain Huruf A-Z dan Angka 0-9
                let cleaned = val.replace(/[^A-Z0-9]/g, '');
                
                // Format menggunakan Regex khas Plat Nomor Indonesia: [Huruf Depan 1-2] [Angka Tengah 1-4] [Huruf Belakang 1-3]
                let match = cleaned.match(/^([A-Z]{0,2})([0-9]{0,4})([A-Z]{0,3})/);
                
                if (match) {
                    let parts = [];
                    if (match[1]) parts.push(match[1]);
                    if (match[2]) parts.push(match[2]);
                    if (match[3]) parts.push(match[3]);
                    
                    let formatted = parts.join(' ');
                    e.target.value = formatted;
                }
            } else {
                // Untuk non-motorized (sepeda/gerobak): izinkan spasi, huruf, angka, dan dash (-)
                let cleaned = val.replace(/[^A-Z0-9\-\s]/g, '');
                e.target.value = cleaned;
            }
            
            // Kembalikan kursor ke posisi yang tepat agar pengetikan mulus
            let newLength = e.target.value.length;
            cursorPosition = cursorPosition + (newLength - originalLength);
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        });

        // Trigger format ulang jika user mengubah pilihan jenis kendaraan
        if (jenisSelect) {
            jenisSelect.addEventListener('change', function() {
                platInput.dispatchEvent(new Event('input'));
            });
        }
    }
});
</script>
