<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Jadwal Servis</h2>
    </div>
</div>

<div style="padding: 24px; padding-bottom: 100px;">
    <?php Session::flash(); ?>

    <!-- Form Tambah Jadwal -->
    <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="12" y1="14" x2="12" y2="20"></line><line x1="9" y1="17" x2="15" y2="17"></line></svg>
            Jadwalkan Servis
        </h3>
        <form action="<?= BASE_URL ?>/admin/tambahServis" method="POST">
            <select name="armada_id" required style="width: 100%; padding: 12px; border: var(--border-thick); margin-bottom: 12px; background-color: var(--light); height: 48px;">
                <option value="">-- Pilih Armada --</option>
                <?php foreach($armada_list as $a): ?>
                    <option value="<?= htmlspecialchars($a['id']) ?>"><?= htmlspecialchars($a['no_polisi']) ?> - <?= htmlspecialchars($a['merk']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="keterangan" placeholder="Keterangan (Ganti Oli, Ban, dll)" required style="width: 100%; padding: 12px; border: var(--border-thick); margin-bottom: 12px; background: var(--light);">
            <input type="date" name="tanggal" required style="width: 100%; padding: 12px; border: var(--border-thick); margin-bottom: 12px; background: var(--light);">
            <button type="submit" class="btn-brutal" style="width: 100%; background: var(--primary); display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Simpan Jadwal
            </button>
        </form>
    </div>

    <!-- Daftar Servis -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
        <h3 style="margin: 0; font-family: var(--font-display);">Daftar Perawatan</h3>
        <button onclick="document.getElementById('export-modal').style.display='flex'" class="btn-brutal" style="padding: 6px 12px; font-size: 0.85rem; background: var(--warning); display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export CSV/Excel
        </button>
    </div>
    
    <?php if (empty($servis)): ?>
        <p style="color: #666; text-align: center; font-style: italic; padding: 20px; border: 2px dashed var(--black); border-radius: 8px;">Belum ada jadwal servis.</p>
    <?php else: ?>
        <?php
        $today = date('Y-m-d');
        ?>
        
        <!-- Filter Tabs (Neo-Brutalism) -->
        <div style="display: flex; gap: 8px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 8px; -webkit-overflow-scrolling: touch;">
            <button class="filter-tab" data-filter="all" style="padding: 8px 16px; border: var(--border-thick); background: var(--warning); font-weight: bold; cursor: pointer; border-radius: 4px; white-space: nowrap; font-family: var(--font-display); box-shadow: 2px 2px 0px var(--black); transition: transform 0.1s;">Semua</button>
            <button class="filter-tab" data-filter="pending" style="padding: 8px 16px; border: var(--border-thick); background: var(--light); font-weight: bold; cursor: pointer; border-radius: 4px; white-space: nowrap; font-family: var(--font-display); box-shadow: 2px 2px 0px var(--black); transition: transform 0.1s;">Pending (Hari Ini)</button>
            <button class="filter-tab" data-filter="terlewat" style="padding: 8px 16px; border: var(--border-thick); background: var(--light); font-weight: bold; cursor: pointer; border-radius: 4px; white-space: nowrap; font-family: var(--font-display); box-shadow: 2px 2px 0px var(--black); transition: transform 0.1s;">Terlewat</button>
            <button class="filter-tab" data-filter="akan_datang" style="padding: 8px 16px; border: var(--border-thick); background: var(--light); font-weight: bold; cursor: pointer; border-radius: 4px; white-space: nowrap; font-family: var(--font-display); box-shadow: 2px 2px 0px var(--black); transition: transform 0.1s;">Akan Datang</button>
            <button class="filter-tab" data-filter="selesai" style="padding: 8px 16px; border: var(--border-thick); background: var(--light); font-weight: bold; cursor: pointer; border-radius: 4px; white-space: nowrap; font-family: var(--font-display); box-shadow: 2px 2px 0px var(--black); transition: transform 0.1s;">Selesai</button>
        </div>

        <?php 
        // Buat map ID -> Plat Nomor
        $platMap = [];
        foreach($armada_list as $a) {
            $platMap[$a['id']] = $a['no_polisi'] . ' (' . $a['merk'] . ')';
        }
        ?>

        <div id="maintenance-list-container">
            <?php foreach($servis as $s): 
                // Hitung status kustom berdasarkan tanggal dan status penyelesaian
                $taskStatus = 'pending';
                if ($s['status'] == 'selesai') {
                    $taskStatus = 'selesai';
                } else if ($s['tanggal'] < $today) {
                    $taskStatus = 'terlewat';
                } else if ($s['tanggal'] > $today) {
                    $taskStatus = 'akan_datang';
                }
            ?>
            <div class="maintenance-card" data-status="<?= $taskStatus ?>" style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 16px; margin-bottom: 16px; border-radius: 8px; <?= $s['status'] == 'selesai' ? 'opacity: 0.7;' : '' ?>">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                    <h4 style="font-size: 1.1rem; margin: 0; font-family: var(--font-display);">Armada: <?= htmlspecialchars($platMap[$s['armada_id']] ?? $s['armada_id']) ?></h4>
                    
                    <!-- Badge Status Khas -->
                    <?php if ($taskStatus == 'selesai'): ?>
                        <span style="background: var(--primary); padding: 4px 8px; border: 2px solid var(--black); font-size: 0.7rem; font-weight: bold; border-radius: 4px; color: var(--black);">SELESAI</span>
                    <?php elseif ($taskStatus == 'terlewat'): ?>
                        <span style="background: var(--danger); padding: 4px 8px; border: 2px solid var(--black); font-size: 0.7rem; font-weight: bold; border-radius: 4px; color: white; animation: fatigue-flash 1s infinite alternate;">⚠️ TERLEWAT</span>
                    <?php elseif ($taskStatus == 'akan_datang'): ?>
                        <span style="background: #9b59b6; padding: 4px 8px; border: 2px solid var(--black); font-size: 0.7rem; font-weight: bold; border-radius: 4px; color: white;">AKAN DATANG</span>
                    <?php else: ?>
                        <span style="background: var(--warning); padding: 4px 8px; border: 2px solid var(--black); font-size: 0.7rem; font-weight: bold; border-radius: 4px; color: var(--black);">PENDING (TODAY)</span>
                    <?php endif; ?>
                </div>
                
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 4px;"><strong>Tanggal:</strong> <?= htmlspecialchars($s['tanggal']) ?></p>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 12px;"><strong>Catatan:</strong> <?= htmlspecialchars($s['keterangan']) ?></p>
                
                <?php if ($s['status'] !== 'selesai'): ?>
                <div style="margin-top: 12px; padding-top: 12px; border-top: 2px dashed var(--black);">
                    <form action="<?= BASE_URL ?>/admin/servisSelesai/<?= $s['id'] ?>" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 8px;">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: bold;">Total Biaya (Rp):</label>
                            <input type="number" name="biaya" placeholder="Misal: 350000" required style="width: 100%; padding: 8px; border: 2px solid var(--black); background: var(--light);">
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: bold;">Foto Nota / Kuitansi:</label>
                            <input type="file" name="nota" accept="image/*" style="width: 100%; padding: 8px; border: 2px solid var(--black); background: var(--light);">
                        </div>
                        <button type="submit" class="btn-brutal" style="margin-top: 8px; padding: 8px 16px; background: var(--primary); width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Tandai Selesai & Simpan Biaya
                        </button>
                    </form>
                </div>
                <?php else: ?>
                    <?php if(!empty($s['biaya'])): ?>
                        <p style="color: var(--dark); font-size: 0.9rem; margin-top: 8px; font-weight: bold;">Biaya: Rp <?= number_format((int)$s['biaya'], 0, ',', '.') ?></p>
                    <?php endif; ?>
                    <?php if(!empty($s['nota'])): ?>
                        <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/uploads/servis/<?= htmlspecialchars($s['nota']) ?>" style="max-width: 100%; border: 2px solid var(--black); border-radius: 4px; margin-top: 8px; max-height: 150px; object-fit: cover;">
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            
            <!-- Fallback Alert jika Filter kosong -->
            <div id="empty-filter-message" style="display: none; text-align: center; padding: 40px 20px; border: var(--border-thick); border-radius: 8px; border-style: dashed; background: var(--white);">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <h4 style="font-family: var(--font-display); margin-bottom: 4px;">Tidak Ada Jadwal</h4>
                <p style="color: #666; font-size: 0.9rem; margin: 0;">Tidak ditemukan riwayat perawatan untuk kategori filter ini.</p>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.filter-tab');
            const cards = document.querySelectorAll('.maintenance-card');
            const emptyMessage = document.getElementById('empty-filter-message');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update Active tab styling
                    tabs.forEach(t => {
                        t.style.background = 'var(--light)';
                        t.style.transform = 'none';
                        t.style.boxShadow = '2px 2px 0px var(--black)';
                    });
                    tab.style.background = 'var(--warning)';
                    tab.style.transform = 'translate(1px, 1px)';
                    tab.style.boxShadow = '1px 1px 0px var(--black)';
                    
                    const filter = tab.dataset.filter;
                    let visibleCount = 0;
                    
                    cards.forEach(card => {
                        if (filter === 'all' || card.dataset.status === filter) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    // Tampilkan pesan kosong jika tidak ada data yang cocok dengan filter
                    if (visibleCount === 0) {
                        emptyMessage.style.display = 'block';
                    } else {
                        emptyMessage.style.display = 'none';
                    }
                });
            });
        });
        
        function exportByMonth() {
            const monthVal = document.getElementById('export-month-picker').value;
            if (monthVal) {
                window.location.href = `<?= BASE_URL ?>/admin/exportServis&filter=month&value=${monthVal}`;
                document.getElementById('export-modal').style.display = 'none';
            }
        }
        </script>
    <?php endif; ?>
</div>

<!-- Modal Popup Export Laporan (Neo-Brutalism) -->
<div id="export-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 10000; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px; border-radius: 8px; max-width: 400px; width: 100%; position: relative;">
        <h3 style="margin-top: 0; margin-bottom: 12px; font-family: var(--font-display); display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Ekspor Laporan Perawatan
        </h3>
        <p style="font-size: 0.9rem; color: #555; margin-bottom: 20px; line-height: 1.4;">Pilih rentang data jadwal servis & perawatan armada yang ingin Anda unduh ke format Excel/CSV:</p>
        
        <!-- Opsi 1: Semua Data -->
        <a href="<?= BASE_URL ?>/admin/exportServis" onclick="document.getElementById('export-modal').style.display='none'" class="btn-brutal" style="display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; background: var(--warning); padding: 12px; margin-bottom: 16px; font-weight: bold; font-family: var(--font-display); color: var(--black);">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><path d="M2 10h20"></path></svg>
            Unduh Semua Riwayat (All Time)
        </a>
        
        <!-- Opsi 2: Berdasarkan Bulan & Tahun -->
        <div style="border-top: 2px dashed var(--black); padding-top: 16px; margin-top: 16px;">
            <label style="display: block; font-weight: bold; font-size: 0.9rem; margin-bottom: 8px; font-family: var(--font-display); text-transform: uppercase; letter-spacing: 0.5px;">📅 Pilih Bulan & Tahun:</label>
            <div style="display: flex; gap: 8px;">
                <input type="month" id="export-month-picker" value="<?= date('Y-m') ?>" style="flex: 1; padding: 10px; border: var(--border-thick); font-family: var(--font-body); font-weight: bold; background: var(--light); border-radius: 4px; height: 44px;">
                <button onclick="exportByMonth()" class="btn-brutal" style="background: var(--primary); padding: 10px 16px; height: 44px; font-weight: bold; font-family: var(--font-display);">Ekspor</button>
            </div>
        </div>
        
        <button onclick="document.getElementById('export-modal').style.display='none'" class="btn-brutal" style="width: 100%; background: var(--light); padding: 10px; margin-top: 24px; font-weight: bold; font-family: var(--font-display);">Batal</button>
    </div>
</div>
