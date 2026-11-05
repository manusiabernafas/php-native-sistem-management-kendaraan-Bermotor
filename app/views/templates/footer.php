    </div> <!-- End Container -->

    <!-- Offline Banner -->
    <div id="offline-banner" style="display: none; background: var(--danger); color: white; padding: 10px; text-align: center; font-weight: bold; font-family: var(--font-display); position: fixed; top: 0; left: 0; right: 0; z-index: 9999; border-bottom: var(--border-thick);">
        ⚠️ ANDA SEDANG OFFLINE. DATA AKAN DISIMPAN LOKAL.
    </div>

    <!-- Script Global PWA -->
    <!-- A2HS Promo Modal -->
<div id="glass-overlay" class="glass-overlay"></div>
<div id="a2hs-modal" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background: var(--white); border-top: 5px solid var(--black); padding: 24px; z-index: 10000; box-shadow: 0 -10px 20px rgba(0,0,0,0.2);">
    <h3 style="margin-bottom: 8px; font-size: 1.4rem; color: var(--dark); display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
        Instal SIMANDA
    </h3>
    <p style="color: #555; margin-bottom: 16px;">Instal PWA ke layar utama HP Anda agar bisa diakses lebih cepat layaknya aplikasi native!</p>
    <div style="display: flex; gap: 12px;">
        <button id="btn-install-accept" class="btn-brutal" style="flex: 1; background: var(--primary); padding: 12px;">Instal Sekarang</button>
        <button onclick="document.getElementById('a2hs-modal').style.display='none'; document.getElementById('glass-overlay').style.display='none';" class="btn-brutal" style="flex: 1; background: var(--light); padding: 12px;">Nanti Saja</button>
    </div>
</div>

<?php 
// Sembunyikan T+ jika di halaman driver (berdasarkan dark_mode atau URL)
$is_driver_page = (isset($dark_mode) && $dark_mode) || (strpos($_GET['url'] ?? '', 'driver') !== false);
if (!$is_driver_page): 
?>
<!-- Aksesibilitas T+ Button -->
<button onclick="document.body.classList.toggle('a11y-large'); playClickSound();" title="Perbesar Teks" style="position: fixed; bottom: 80px; right: 20px; width: 48px; height: 48px; border-radius: 24px; background: var(--warning); border: 3px solid var(--black); font-weight: bold; font-size: 1.2rem; box-shadow: 4px 4px 0px var(--black); z-index: 9000; cursor: pointer; color: var(--black);">
    T+
</button>
<?php endif; ?>

<script src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/js/app.js"></script>
<script>
    // Cek inisial jika buka web dalam kondisi mati internet
    if (!navigator.onLine) {
        document.body.classList.add('is-offline');
    }

    // --- BRUTALIST CUSTOM SELECT COMPONENT GLOBALLY ---
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            // Jangan kustomisasi jika select ini sudah di-render ulang
            if (select.dataset.customized === 'true') return;
            select.dataset.customized = 'true';

            // Sembunyikan select bawaan HTML
            select.style.display = 'none';
            
            // Buat container wrapper kustom
            const wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';
            wrapper.style.position = 'relative';
            wrapper.style.width = '100%';
            
            // Masukkan wrapper sebelum select asli
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);
            
            // Buat tombol trigger utama
            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            trigger.style.width = '100%';
            trigger.style.padding = '12px 16px';
            trigger.style.border = 'var(--border-thick)';
            trigger.style.fontFamily = 'var(--font-body)';
            trigger.style.fontSize = '1rem';
            trigger.style.fontWeight = '600';
            trigger.style.backgroundColor = 'var(--light)';
            trigger.style.color = 'var(--dark)';
            trigger.style.borderRadius = '4px';
            trigger.style.cursor = 'pointer';
            trigger.style.display = 'flex';
            trigger.style.justifyContent = 'space-between';
            trigger.style.alignItems = 'center';
            trigger.style.height = '48px';
            trigger.style.transition = 'all 0.1s';
            
            // Ambil opsi yang saat ini dipilih
            const selectedOpt = select.options[select.selectedIndex];
            const triggerText = document.createElement('span');
            triggerText.innerText = selectedOpt ? selectedOpt.text : '-- Pilih --';
            trigger.appendChild(triggerText);
            
            // Panah chevron brutal kustom
            const arrow = document.createElement('span');
            arrow.innerHTML = '▼';
            arrow.style.fontSize = '0.8rem';
            arrow.style.transition = 'transform 0.2s';
            trigger.appendChild(arrow);
            
            wrapper.appendChild(trigger);
            
            // Buat pop-up menu daftar pilihan
            const menu = document.createElement('div');
            menu.className = 'custom-select-menu';
            menu.style.position = 'absolute';
            menu.style.top = 'calc(100% + 4px)';
            menu.style.left = '0';
            menu.style.right = '0';
            menu.style.zIndex = '999';
            menu.style.backgroundColor = 'var(--white)';
            menu.style.border = 'var(--border-thick)';
            menu.style.boxShadow = 'var(--shadow-hard)';
            menu.style.borderRadius = '4px';
            menu.style.display = 'none';
            menu.style.flexDirection = 'column';
            menu.style.maxHeight = '200px';
            menu.style.overflowY = 'auto';
            
            // Masukkan setiap opsi select ke dalam pop-up menu
            Array.from(select.options).forEach(opt => {
                const item = document.createElement('div');
                item.className = 'custom-select-item';
                item.innerText = opt.text;
                item.style.padding = '12px 16px';
                item.style.fontWeight = 'bold';
                item.style.cursor = 'pointer';
                item.style.borderBottom = '2px solid var(--black)';
                item.style.color = document.body.classList.contains('dark-mode') ? '#FFFFFF' : 'var(--black)';
                item.style.backgroundColor = 'var(--white)';
                item.style.transition = 'all 0.1s';
                
                // Efek Hover Item (Kuning/Hijau Brutalisme)
                item.addEventListener('mouseenter', () => {
                    item.style.backgroundColor = 'var(--primary)';
                    item.style.color = '#000000';
                });
                item.addEventListener('mouseleave', () => {
                    item.style.backgroundColor = 'var(--white)';
                    item.style.color = document.body.classList.contains('dark-mode') ? '#FFFFFF' : 'var(--black)';
                });
                
                // Klik Item Opsi
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    select.value = opt.value;
                    triggerText.innerText = opt.text;
                    menu.style.display = 'none';
                    arrow.style.transform = 'rotate(0deg)';
                    trigger.style.boxShadow = 'none';
                    
                    // Picu event change bawaan agar terdeteksi form
                    select.dispatchEvent(new Event('change'));
                });
                
                menu.appendChild(item);
            });
            
            // Hilangkan border bawah di item terakhir biar rapi
            if (menu.lastChild) {
                menu.lastChild.style.borderBottom = 'none';
            }
            
            wrapper.appendChild(menu);
            
            // Toggle Buka/Tutup Menu saat diklik
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = menu.style.display === 'flex';
                
                // Tutup semua dropdown kustom lainnya yang sedang terbuka
                document.querySelectorAll('.custom-select-menu').forEach(m => {
                    if (m !== menu) m.style.display = 'none';
                });
                document.querySelectorAll('.custom-select-trigger span:last-child').forEach(a => {
                    if (a !== arrow) a.style.transform = 'rotate(0deg)';
                });
                document.querySelectorAll('.custom-select-trigger').forEach(t => {
                    if (t !== trigger) t.style.boxShadow = 'none';
                });
                
                if (!isOpen) {
                    menu.style.display = 'flex';
                    arrow.style.transform = 'rotate(180deg)';
                    trigger.style.boxShadow = '4px 4px 0px var(--primary)';
                    trigger.style.borderColor = 'var(--primary)';
                } else {
                    menu.style.display = 'none';
                    arrow.style.transform = 'rotate(0deg)';
                    trigger.style.boxShadow = 'none';
                }
            });
        });
        
        // Tutup dropdown saat pengguna mengklik di luar area menu
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-menu').forEach(m => m.style.display = 'none');
            document.querySelectorAll('.custom-select-trigger span:last-child').forEach(a => a.style.transform = 'rotate(0deg)');
            document.querySelectorAll('.custom-select-trigger').forEach(t => t.style.boxShadow = 'none');
        });
    });
</script>
</body>
</html>
