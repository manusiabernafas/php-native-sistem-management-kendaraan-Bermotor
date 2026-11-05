<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS untuk Layar TV -->
    <link rel="stylesheet" href="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/css/monitor.css">
</head>
<body>

    <div class="header">
        <div style="display: flex; align-items: center; gap: 20px;">
            <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" width="64" height="64" style="filter: invert(1);">
            <h1>SIMANDA MONITOR</h1>
        </div>
        <div class="clock" id="clockDisplay">00:00:00</div>
    </div>

    <?php 
        $hasDarurat = false;
        if (!empty($alerts)) {
            foreach ($alerts as $a) {
                if (in_array($a['tipe'] ?? '', ['urgent', 'fatal'])) {
                    $hasDarurat = true;
                    break;
                }
            }
        }
    ?>
    <?php if($hasDarurat): ?>
    <div style="background: var(--danger); color: white; text-align: center; padding: 10px; font-weight: bold; font-size: 1.5rem; text-transform: uppercase; animation: flash 0.5s infinite alternate;">
        ⚠️ ADA LAPORAN DARURAT YANG BELUM DISELESAIKAN! ⚠️
    </div>
    <style>
        @keyframes flash {
            0% { background: var(--danger); color: white; }
            100% { background: #ffeb3b; color: #d32f2f; }
        }
    </style>
    <?php endif; ?>

    <?php 
        // Hitung pembagian operasional armada secara riil
        $ada_di_tempat = [];
        $sedang_dipakai = [];
        $rusak = [];
        $dalam_servis = [];

        if (!empty($armada_list)) {
            foreach ($armada_list as $a) {
                if ($a['status'] === 'rusak') {
                    $rusak[] = $a;
                } elseif ($a['status'] === 'service') {
                    $dalam_servis[] = $a;
                } elseif (!empty($a['dipakai_oleh'])) {
                    $sedang_dipakai[] = $a;
                } else {
                    $ada_di_tempat[] = $a;
                }
            }
        }
    ?>

    <div class="main-content">
        <!-- Panel Statistik -->
        <div class="stats-panel">
            <div class="stat-card" style="grid-column: span 2; background: var(--primary);">
                <h2><?= count($armada_list) ?></h2>
                <p>Total Armada Terdaftar</p>
            </div>
            
            <!-- Armada Ada Di Tempat (Tersedia & Idle) -->
            <div class="stat-card" style="background: #E8F8F5; border-color: var(--black); padding: 10px;">
                <h2 style="font-size: 2.8rem; color: #27AE60;"><?= count($ada_di_tempat) ?></h2>
                <p style="font-size: 0.95rem; margin-top: 4px; color: var(--black);">Ada di Tempat</p>
                <div style="font-size: 0.8rem; max-height: 65px; overflow-y: auto; width: 100%; text-align: left; padding: 6px 10px; margin-top: 8px; border-top: 1px dashed var(--black);">
                    <?php if(empty($ada_di_tempat)): ?>
                        <span style="opacity: 0.6; color: var(--black);">Kosong</span>
                    <?php else: ?>
                        <?php foreach($ada_di_tempat as $v): ?>
                            <div style="font-weight: bold; color: #27AE60; display: inline-block; margin-right: 8px;">🚘 <?= htmlspecialchars($v['no_polisi']) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Armada Sedang Dinas (Baru Dipakai Supir) -->
            <div class="stat-card" style="background: #FEF9E7; border-color: var(--black); padding: 10px;">
                <h2 style="font-size: 2.8rem; color: #D35400;"><?= count($sedang_dipakai) ?></h2>
                <p style="font-size: 0.95rem; margin-top: 4px; color: var(--black);">Sedang Dipakai</p>
                <div style="font-size: 0.8rem; max-height: 65px; overflow-y: auto; width: 100%; text-align: left; padding: 6px 10px; margin-top: 8px; border-top: 1px dashed var(--black); color: var(--black);">
                    <?php if(empty($sedang_dipakai)): ?>
                        <span style="opacity: 0.6;">Tidak ada</span>
                    <?php else: ?>
                        <?php foreach($sedang_dipakai as $v): ?>
                            <div style="margin-bottom: 2px; font-size: 0.75rem; border-bottom: 1px dotted #ccc;">
                                <strong>🚘 <?= htmlspecialchars($v['no_polisi']) ?></strong> 
                                <span style="color: #555;">(<?= htmlspecialchars($v['dipakai_oleh']) ?>)</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Armada Rusak & Servis -->
            <div class="stat-card" style="background: #FDEDEC; border-color: var(--black); padding: 10px; grid-column: span 2; display: flex; flex-direction: row; justify-content: space-around; align-items: center;">
                <div style="text-align: center;">
                    <h2 style="font-size: 3.2rem; color: var(--danger);"><?= count($rusak) + count($dalam_servis) ?></h2>
                    <p style="font-size: 0.95rem; margin-top: 4px; color: var(--black);">Rusak / Servis</p>
                </div>
                <div style="font-size: 0.8rem; max-height: 75px; overflow-y: auto; width: 55%; text-align: left; padding: 6px 10px; border-left: 2px dashed var(--black); color: var(--black);">
                    <?php if(empty($rusak) && empty($dalam_servis)): ?>
                        <span style="opacity: 0.6; font-weight: bold; color: #27AE60;">🟢 Semua Unit Sehat Walafiat!</span>
                    <?php else: ?>
                        <?php foreach($rusak as $v): ?>
                            <div style="color: var(--danger); font-size: 0.8rem; margin-bottom: 4px;">
                                <strong>💥 <?= htmlspecialchars($v['no_polisi']) ?></strong> 
                                <span style="font-weight: bold; background: var(--danger); color: white; padding: 1px 4px; font-size: 0.65rem; border-radius: 3px; margin-left: 4px;">RUSAK</span>
                            </div>
                        <?php endforeach; ?>
                        <?php foreach($dalam_servis as $v): ?>
                            <div style="color: #2980B9; font-size: 0.8rem; margin-bottom: 4px;">
                                <strong>🔧 <?= htmlspecialchars($v['no_polisi']) ?></strong> 
                                <span style="font-weight: bold; background: #2980B9; color: white; padding: 1px 4px; font-size: 0.65rem; border-radius: 3px; margin-left: 4px;">SERVIS</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Panel Laporan -->
        <div class="alert-panel">
            <div class="alert-header">
                <h2>🚨 Live Alerts <span><?= count($alerts) ?></span></h2>
            </div>
            <div class="alert-list">
                <?php if(empty($alerts)): ?>
                    <div style="text-align: center; margin-top: 100px; opacity: 0.5;">
                        <h1 style="font-size: 5rem; margin:0;">✅</h1>
                        <h2>Semua Aman Terkendali</h2>
                    </div>
                <?php else: ?>
                    <?php 
                    // Buat map ID -> Plat Nomor
                    $platMap = [];
                    if (!empty($armada_list)) {
                        foreach($armada_list as $a) {
                            $platMap[$a['id']] = $a['no_polisi'] . ' (' . $a['merk'] . ')';
                        }
                    }
                    ?>
                    <?php foreach($alerts as $alert): ?>
                    <div class="alert-item <?= ($alert['tipe'] ?? '') == 'urgent' ? 'urgent' : '' ?>" style="padding: 8px 12px; margin-bottom: 8px;">
                        <div>
                            <h3 style="margin: 0 0 3px 0; font-size: 1.2rem; font-family: 'Space Grotesk', sans-serif;">Mobil <?= htmlspecialchars($platMap[$alert['armada_id']] ?? $alert['armada_id'] ?? 'Tidak diketahui') ?></h3>
                            <p style="margin: 0; font-size: 1rem; line-height: 1.2;"><?= htmlspecialchars($alert['keterangan']) ?></p>
                            <small style="opacity: 0.8; font-size: 0.75rem;">Dilaporkan oleh: <?= htmlspecialchars($alert['driver_name']) ?> (<?= $alert['created_at'] ?>)</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Auto Refresh Script -->
    <script>
        // Jam Digital
        function updateClock() {
            const now = new Date();
            document.getElementById('clockDisplay').textContent = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Refresh halaman setiap 10 detik
        setTimeout(function(){
            window.location.reload();
        }, 10000);
    </script>
</body>
</html>
