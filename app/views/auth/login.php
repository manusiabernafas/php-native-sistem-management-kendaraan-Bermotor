<div style="padding: 24px; min-height: 90vh; display: flex; flex-direction: column; justify-content: center;">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <img src="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" width="80" height="80" style="margin-bottom: 16px;">
        <h1 style="font-size: 2.5rem; color: var(--primary); margin: 0;">SIMANDA</h1>
        <p style="font-size: 1.1rem; margin-top: 4px;">Sistem Manajemen Armada</p>
    </div>

    <?php Session::flash(); ?>

    <form action="<?= BASE_URL ?>/auth/proses" method="POST" style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 24px;">
        
        <h2 style="margin-bottom: 20px;">Masuk</h2>
        
        <div style="margin-bottom: 20px;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px;">Username</label>
            <input type="text" name="username" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light);">
        </div>

        <div style="margin-bottom: 30px;">
            <label style="font-weight: 700; display: block; margin-bottom: 8px;">Password</label>
            <input type="password" name="password" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light);">
        </div>

        <button type="submit" class="btn-brutal" style="width: 100%; font-size: 1.2rem; background: var(--warning); display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Login Sekarang
        </button>

    </form>
    
    <p style="text-align: center; margin-top: 30px; font-size: 0.9rem; color: #666;">
        Belum punya akun? Hubungi Admin Garasi Dinkes.
    </p>

</div>
