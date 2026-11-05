<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/driver" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Buat Laporan</h2>
    </div>
</div>

<div style="padding: 16px; padding-bottom: 60px;">

    <div style="background: var(--dark); color: white; border: var(--border-thick); padding: 16px; border-radius: 8px; margin-bottom: 16px; text-align: center; max-width: 380px; margin: 0 auto 16px auto;">
        <p style="margin: 0; font-size: 0.9rem; opacity: 0.8; font-family: var(--font-display);">KENDARAAN</p>
        <h2 style="margin: 0; font-size: 2rem; color: var(--primary); font-family: var(--font-display);"><?= htmlspecialchars($armada['no_polisi']) ?></h2>
    </div>

    <form action="<?= BASE_URL ?>/driver/report" method="POST" enctype="multipart/form-data" style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 16px; border-radius: 8px; max-width: 380px; margin: 0 auto;">
        
        <input type="hidden" name="armada_id" value="<?= htmlspecialchars($armada['id']) ?>">
        
        <div style="margin-bottom: 20px; position: relative;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px;">Keterangan Lengkap:</label>
            <textarea name="keterangan" id="keterangan_input" rows="4" placeholder="Jelaskan kondisi secara detail..." required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); resize: vertical;"></textarea>
            <button type="button" onclick="startDictation()" style="position: absolute; right: 12px; bottom: 12px; width: 40px; height: 40px; border-radius: 20px; border: 2px solid var(--black); background: var(--primary); cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Bicara untuk Mengetik">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" x2="12" y1="19" y2="22"></line></svg>
            </button>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px;">Upload Foto Bukti:</label>
            <input type="file" name="foto" accept="image/*" style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--white);">
        </div>

        <div style="margin-bottom: 20px;">
            <?php $km_saat_ini = (int)($armada['km_saat_ini'] ?? 0); ?>
            <label style="font-weight: 700; display: block; margin-bottom: 8px;">Odometer (KM Saat Ini) <span style="color: var(--danger);">*</span></label>
            <input type="number" name="km_saat_ini" min="<?= $km_saat_ini ?>" placeholder="Contoh: <?= htmlspecialchars((string)$km_saat_ini) ?>" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px;">
            <p style="font-size: 0.8rem; color: #666; margin-top: 4px; font-weight: bold;">KM Terakhir: <?= number_format($km_saat_ini, 0, ',', '.') ?> KM. Input tidak boleh kurang dari KM terakhir.</p>
        </div>

        <div style="margin-bottom: 24px; padding: 16px; border: 2px dashed var(--black); background: var(--light);">
            <label style="font-weight: 700; display: block; margin-bottom: 8px;">Koordinat Lokasi Darurat (Opsional):</label>
            <input type="text" id="lokasi_teks" readonly placeholder="Klik tombol di bawah..." style="width: 100%; padding: 8px; border: 1px solid var(--black); background: var(--light); color: var(--dark); margin-bottom: 8px; font-size: 0.9rem;">
            <input type="hidden" name="gps_lat" id="gps_lat">
            <input type="hidden" name="gps_lng" id="gps_lng">
            <button type="button" onclick="ambilLokasi()" class="btn-brutal" style="width: 100%; padding: 8px; background: var(--white); font-weight: bold; cursor: pointer;">📍 Ambil Titik GPS Saya</button>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px;">Tingkat Keparahan:</label>
            <select name="tipe" style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light);">
                <option value="normal">⚠️ Kerusakan Ringan (Bisa jalan)</option>
                <option value="urgent">🚨 DARURAT (Mogok / Bahaya)</option>
                <option value="fatal">💥 FATAL (Bongkar/Turun Mesin)</option>
            </select>
            <p style="font-size: 0.8rem; color: #666; margin-top: 4px;">Pilih FATAL jika kerusakan mesin berat. Ini akan me-reset jadwal servis rutin.</p>
        </div>

        <button type="submit" class="btn-brutal" style="width: 100%; background: var(--danger); color: white; border: 3px solid var(--dark);">
            KIRIM LAPORAN SEKARANG
        </button>
    </form>
</div>

<script>
// Geolocation Logic
function getLocation() {
    const status = document.getElementById('gps_status');
    const latInput = document.getElementById('gps_lat');
    const lngInput = document.getElementById('gps_lng');
    
    if (!navigator.geolocation) {
        status.textContent = "Geolokasi tidak didukung oleh browser Anda.";
        return;
    }

    status.textContent = "Mencari lokasi...";
    navigator.geolocation.getCurrentPosition(
        (position) => {
            latInput.value = position.coords.latitude;
            lngInput.value = position.coords.longitude;
            status.textContent = `📍 Ditemukan! (${position.coords.latitude}, ${position.coords.longitude})`;
        },
        () => {
            status.textContent = "Gagal mengambil lokasi. Pastikan GPS aktif.";
        }
    );
}

// Voice-to-Text Logic
function startDictation() {
    if (window.hasOwnProperty('webkitSpeechRecognition') || window.hasOwnProperty('SpeechRecognition')) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = "id-ID";
        
        // Ganti ikon atau warna saat merekam
        const textarea = document.getElementById('keterangan_input');
        const oldPlaceholder = textarea.placeholder;
        textarea.placeholder = "Mendengarkan... Silakan bicara...";
        
        recognition.start();
        
        recognition.onresult = function(e) {
            textarea.value += (textarea.value ? " " : "") + e.results[0][0].transcript;
            textarea.placeholder = oldPlaceholder;
            recognition.stop();
        };
        
        recognition.onerror = function(e) {
            textarea.placeholder = "Gagal mendengar: " + e.error;
            setTimeout(() => textarea.placeholder = oldPlaceholder, 2000);
            recognition.stop();
        }
    } else {
        alert("Maaf, browser Anda tidak mendukung input suara.");
    }
}

function ambilLokasi() {
    const btn = document.querySelector('button[onclick="ambilLokasi()"]');
    const teks = document.getElementById('lokasi_teks');
    const lat = document.getElementById('gps_lat');
    const lng = document.getElementById('gps_lng');
    
    btn.textContent = "⏳ Mengambil GPS...";
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                lat.value = position.coords.latitude;
                lng.value = position.coords.longitude;
                teks.value = position.coords.latitude + ", " + position.coords.longitude;
                btn.textContent = "✅ Berhasil Diambil!";
                btn.style.background = "var(--primary)";
            },
            function(error) {
                alert("Gagal mengambil GPS. Pastikan GPS HP Anda nyala.");
                btn.textContent = "📍 Coba Lagi";
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    } else {
        alert("Browser Anda tidak mendukung GPS.");
    }
}
</script>
