<div class="topbar no-print">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin/armada" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Cetak Massal QR (1 QR per Kartu)</h2>
    </div>
    <button onclick="window.print()" class="btn-brutal" style="background: var(--warning); padding: 8px 16px; font-weight: bold; cursor: pointer;">🖨️ Cetak Semua Halaman</button>
</div>

<div style="padding: 24px; padding-bottom: 100px;">
    
    <div class="print-instruction no-print" style="background: var(--light); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 16px; border-radius: 8px; margin-bottom: 30px; font-weight: bold; font-family: var(--font-body);">
        💳 FORMAT 1 QR PER KARTU (UKURAN IC CARD / KTP)<br>
        <span style="font-weight: normal; font-size: 0.9rem; color: #555;">
            Sistem secara otomatis memisahkan **QR Umum (Publik)** dan **QR Khusus Driver** menjadi **2 kartu terpisah** untuk setiap kendaraan. 
            Hal ini memudahkan penempelan: 1 kartu publik ditempel di luar kendaraan (kaca pintu samping/belakang), dan 1 kartu driver ditempel di area dalam (dashboard supir).
        </span>
    </div>

    <div class="qr-grid">
        <?php foreach ($all_armada as $armada): 
            $url_umum = BASE_URL . '/public/vehicle/' . $armada['id'];
            $url_driver = BASE_URL . '/driver/scan/' . $armada['id'];
        ?>
        <!-- KARTU 1: MONITOR PUBLIK (DITEMPEL DI LUAR) -->
        <div class="qr-card border-card-public">
            <div class="qr-card-header">
                <div class="header-plate"><?= htmlspecialchars($armada['no_polisi']) ?></div>
                <div class="header-sub"><?= htmlspecialchars($armada['merk']) ?> (<?= htmlspecialchars($armada['jenis']) ?>)</div>
            </div>
            
            <div class="qr-card-body-single">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($url_umum) ?>" class="qr-img border-public">
            </div>

            <div class="qr-card-footer bg-public">
                🌐 MONITOR PUBLIK
            </div>
        </div>

        <!-- KARTU 2: KHUSUS DRIVER (DITEMPEL DI DALAM DASHBOARD) -->
        <div class="qr-card border-card-driver">
            <div class="qr-card-header">
                <div class="header-plate"><?= htmlspecialchars($armada['no_polisi']) ?></div>
                <div class="header-sub"><?= htmlspecialchars($armada['merk']) ?> (<?= htmlspecialchars($armada['jenis']) ?>)</div>
            </div>
            
            <div class="qr-card-body-single">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($url_driver) ?>" class="qr-img border-driver">
            </div>

            <div class="qr-card-footer bg-driver">
                🔑 KHUSUS DRIVER / SUPIR
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
/* Lebarkan container khusus halaman cetak massal agar preview di PC bagus */
.container {
    max-width: 850px !important;
    transition: none !important;
}

/* Grid Layout Layar (Browser Preview) */
.qr-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

/* Card Styling - Dibuat mirip bentuk Kartu KTP/IC Card di Layar */
.qr-card {
    background: var(--white);
    border: 3px solid var(--black);
    border-radius: 8px;
    box-shadow: 6px 6px 0px var(--black);
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    aspect-ratio: 85.6 / 53.98; /* Rasio IC Card */
    box-sizing: border-box;
    page-break-inside: avoid;
    break-inside: avoid;
}

.border-card-public {
    border-color: #27ae60 !important;
    box-shadow: 6px 6px 0px #27ae60 !important;
}

.border-card-driver {
    border-color: #c0392b !important;
    box-shadow: 6px 6px 0px #c0392b !important;
}

.qr-card-header {
    border-bottom: 2px dashed var(--black);
    padding-bottom: 4px;
    margin-bottom: 4px;
    text-align: center;
}

.header-plate {
    font-size: 1.3rem;
    font-weight: 900;
    font-family: var(--font-display);
    letter-spacing: 0.5px;
    line-height: 1.1;
    color: var(--black);
}

.header-sub {
    font-size: 0.7rem;
    color: #555;
    font-weight: bold;
    text-transform: uppercase;
    margin-top: 1px;
}

.qr-card-body-single {
    display: flex;
    justify-content: center;
    align-items: center;
    flex: 1;
}

.qr-img {
    width: 72px;
    height: 72px;
    border-radius: 4px;
    background: #fff;
    padding: 2px;
    box-sizing: border-box;
}

.border-public {
    border: 2px solid #27ae60;
}

.border-driver {
    border: 2px solid #c0392b;
}

.qr-card-footer {
    text-align: center;
    font-size: 0.75rem;
    font-weight: 900;
    padding: 4px;
    border: 2px solid var(--black);
    border-radius: 4px;
    font-family: var(--font-display);
    letter-spacing: 0.5px;
    margin-top: 4px;
}

.bg-public {
    background: #27ae60;
    color: white !important;
}

.bg-driver {
    background: #c0392b;
    color: white !important;
}

/* ========================================================
   PROPORTIONAL PRINT STYLES - EXACT PHYSICAL IC CARD SIZE
   ======================================================== */
@media print {
    @page {
        size: A4;
        margin: 15mm 10mm 15mm 10mm; /* Margin halaman cetak */
    }
    
    /* Reset container untuk print agar tidak terpotong (max-width 480px di style.css) */
    .container {
        max-width: none !important;
        width: 100% !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        min-height: 0 !important;
        background: transparent !important;
        overflow: visible !important;
    }
    
    /* Sembunyikan semua tombol & navigasi web */
    .no-print, 
    .topbar, 
    button, 
    a, 
    #brutal-loader, 
    .print-instruction {
        display: none !important;
    }
    
    body {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Grid 2 Kolom Kartu per baris kertas A4 */
    .qr-grid {
        display: grid !important;
        grid-template-columns: repeat(2, 85.6mm) !important;
        gap: 8mm !important;
        justify-content: center !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Fisik Kartu: PERSIS UKURAN IC CARD (8.56cm x 5.4cm) */
    .qr-card {
        width: 85.6mm !important;
        height: 53.98mm !important;
        border: 2px solid #000000 !important;
        border-radius: 4.5mm !important; /* Standar lengkungan KTP */
        box-shadow: none !important;
        background: #ffffff !important;
        padding: 3.5mm !important;
        box-sizing: border-box !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        overflow: hidden !important;
    }

    .border-card-public {
        border-color: #27ae60 !important;
    }

    .border-card-driver {
        border-color: #c0392b !important;
    }

    .qr-card-header {
        border-bottom: 1.5px dashed #000000 !important;
        padding-bottom: 1.5mm !important;
        margin-bottom: 1.5mm !important;
    }

    .header-plate {
        font-size: 14pt !important;
        line-height: 1.1 !important;
    }

    .header-sub {
        font-size: 7.5pt !important;
        margin-top: 0.5mm !important;
    }

    .qr-card-body-single {
        flex: 1 !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    .qr-img {
        width: 30mm !important; /* Ukuran QR diperbesar maksimal untuk kemudahan scanning */
        height: 30mm !important;
        border-width: 1.5px !important;
        padding: 1px !important;
        border-radius: 2px !important;
    }

    .qr-card-footer {
        font-size: 8pt !important;
        padding: 1.5mm !important;
        border-width: 1.5px !important;
        margin-top: 1.5mm !important;
    }
}
</style>
