<div style="padding: 20px; padding-bottom: 80px;">
    <?php Session::flash(); ?>
    <!-- Greeting Top -->
    <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="font-size: 1.8rem; margin-bottom: 4px;">Halo, <?= htmlspecialchars(Session::get('name')) ?>!</h2>
            <p style="color: #666;">Pilih menu di bawah ini untuk mengelola SIMANDA.</p>
        </div>
        <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" width="48" height="48">
    </div>

    <!-- Grid Menu Gojek-style -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
        
        <!-- Card 1 -->
        <a href="<?= BASE_URL ?>/admin/armada" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
            <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; text-align: center; transition: transform 0.1s; cursor:pointer; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;" onmousedown="this.style.transform='translate(4px,4px)'; this.style.boxShadow='var(--shadow-press)';" onmouseup="this.style.transform=''; this.style.boxShadow='var(--shadow-hard)';">
                <div style="margin-bottom: 12px; color: var(--dark);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><circle cx="17" cy="17" r="2"></circle></svg>
                </div>
                <h3 style="font-size: 1.1rem; margin-bottom: 4px;">Armada</h3>
                <p style="font-size: 0.8rem; color: #666; margin: 0;">Kelola Kendaraan</p>
            </div>
        </a>

        <!-- Card 2 -->
        <a href="<?= BASE_URL ?>/admin/laporan" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
            <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; text-align: center; transition: transform 0.1s; cursor:pointer; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;" onmousedown="this.style.transform='translate(4px,4px)'; this.style.boxShadow='var(--shadow-press)';" onmouseup="this.style.transform=''; this.style.boxShadow='var(--shadow-hard)';">
                <div style="margin-bottom: 12px; color: var(--dark);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                </div>
                <h3 style="font-size: 1.1rem; margin-bottom: 4px;">Laporan</h3>
                <p style="font-size: 0.8rem; color: #666; margin: 0;">Cek Kerusakan</p>
            </div>
        </a>

        <!-- Card 3 -->
        <a href="<?= BASE_URL ?>/admin/users" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
            <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; text-align: center; transition: transform 0.1s; cursor:pointer; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;" onmousedown="this.style.transform='translate(4px,4px)'; this.style.boxShadow='var(--shadow-press)';" onmouseup="this.style.transform=''; this.style.boxShadow='var(--shadow-hard)';">
                <div style="margin-bottom: 12px; color: var(--dark);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3 style="font-size: 1.1rem; margin-bottom: 4px;">Users</h3>
                <p style="font-size: 0.8rem; color: #666; margin: 0;">Kelola Supir</p>
            </div>
        </a>

        <!-- Card 4 -->
        <a href="<?= BASE_URL ?>/admin/servis" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
            <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; text-align: center; transition: transform 0.1s; cursor:pointer; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;" onmousedown="this.style.transform='translate(4px,4px)'; this.style.boxShadow='var(--shadow-press)';" onmouseup="this.style.transform=''; this.style.boxShadow='var(--shadow-hard)';">
                <div style="margin-bottom: 12px; color: var(--dark);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <h3 style="font-size: 1.1rem; margin-bottom: 4px;">Servis</h3>
                <p style="font-size: 0.8rem; color: #666; margin: 0;">Jadwal Perawatan</p>
            </div>
        </a>

        <!-- Card 5 (Executive Dashboard) -->
        <a href="<?= BASE_URL ?>/admin/executive" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; grid-column: span 2;">
            <div style="background: var(--dark); color: white; border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; text-align: center; transition: transform 0.1s; cursor:pointer; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center;" onmousedown="this.style.transform='translate(4px,4px)'; this.style.boxShadow='var(--shadow-press)';" onmouseup="this.style.transform=''; this.style.boxShadow='var(--shadow-hard)';">
                <div style="margin-bottom: 12px; color: var(--warning);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                </div>
                <h3 style="font-size: 1.1rem; margin-bottom: 4px;">Analitik Eksekutif</h3>
                <p style="font-size: 0.8rem; color: #ccc; margin: 0;">Laporan Performa & Biaya</p>
            </div>
        </a>

    </div>
</div>

<<!-- Bottom Navigation Bar dengan QR Melayang -->
<div style="position: fixed; bottom: 0; left: 0; right: 0; background: var(--white); border-top: var(--border-thick); display: flex; justify-content: space-around; align-items: center; padding: 12px 0; z-index: 100; max-width: 480px; margin: 0 auto; padding-bottom: calc(12px + env(safe-area-inset-bottom));">
    
    <a href="<?= BASE_URL ?>/admin" style="text-align: center; text-decoration: none; color: var(--primary);" class="nav-item">
        <div style="margin-bottom: 4px;"><svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></div>
        <div style="font-size: 0.7rem; font-weight: 700;">Home</div>
    </a>
    
    <a href="<?= BASE_URL ?>/admin/laporan" style="text-align: center; text-decoration: none; color: var(--dark);" class="nav-item">
        <div style="margin-bottom: 4px;"><svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg></div>
        <div style="font-size: 0.7rem; font-weight: 700;">Laporan</div>
    </a>

    <!-- Floating Action Button (FAB) QR Code -->
    <div style="position: relative; width: 60px; display: flex; justify-content: center;">
        <a href="<?= BASE_URL ?>/admin/qr" class="fab-btn nav-item" style="position: absolute; top: -40px; background: var(--warning); border: var(--border-thick); width: 64px; height: 64px; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0px -2px 0px var(--black), 2px 2px 0px var(--black); text-decoration: none; color: var(--black); transition: transform 0.1s;">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
        </a>
    </div>

    <a href="<?= BASE_URL ?>/admin/servis" style="text-align: center; text-decoration: none; color: var(--dark);" class="nav-item">
        <div style="margin-bottom: 4px;"><svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
        <div style="font-size: 0.7rem; font-weight: 700;">Servis</div>
    </a>

    <a href="<?= BASE_URL ?>/auth/logout" style="text-align: center; text-decoration: none; color: var(--danger);" class="nav-item">
        <div style="margin-bottom: 4px;"><svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></div>
        <div style="font-size: 0.7rem; font-weight: 700;">Keluar</div>
    </a>

</div>
