<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin/users" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0; font-family: var(--font-display);"><?= isset($user) ? 'Edit' : 'Tambah' ?> Pengguna</h2>
    </div>
</div>

<div style="padding: 16px; padding-bottom: 80px;">
    <?php Session::flash(); ?>

    <form action="<?= BASE_URL ?>/admin/simpanUser" method="POST" style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 20px; border-radius: 8px; display: flex; flex-direction: column; gap: 16px;">
        
        <?php if(isset($user)): ?>
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <?php endif; ?>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Nama Lengkap <span style="color: var(--danger);">*</span></label>
            <input type="text" name="name" value="<?= isset($user) ? htmlspecialchars($user['name'] ?? '') : '' ?>" placeholder="Contoh: Supir Budi" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px;">
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Username <span style="color: var(--danger);">*</span></label>
            <input type="text" name="username" value="<?= isset($user) ? htmlspecialchars($user['username'] ?? '') : '' ?>" placeholder="Contoh: budi123" required style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px;" <?= isset($user) ? 'readonly style="opacity: 0.7; pointer-events: none;"' : '' ?>>
            <?php if(isset($user)): ?>
                <small style="color: #666; margin-top: 4px; display: block;">Username tidak dapat diubah setelah dibuat.</small>
            <?php endif; ?>
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Password <?= isset($user) ? '' : '<span style="color: var(--danger);">*</span>' ?></label>
            <input type="password" name="password" placeholder="<?= isset($user) ? 'Masukkan password baru untuk mengganti password lama' : 'Masukkan password' ?>" <?= isset($user) ? '' : 'required' ?> style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background: var(--light); border-radius: 4px;">
            <?php if(isset($user)): ?>
                <small style="color: #666; margin-top: 4px; display: block;">Kosongkan password jika Anda tidak ingin mengubahnya.</small>
            <?php endif; ?>
        </div>

        <div>
            <label style="font-weight: 700; display: block; margin-bottom: 6px; font-family: var(--font-display); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;">Hak Akses (Role)</label>
            <select name="role" style="width: 100%; padding: 12px; border: var(--border-thick); font-family: var(--font-body); font-size: 1rem; background-color: var(--light); border-radius: 4px; height: 48px;">
                <option value="driver" <?= (isset($user) && $user['role'] == 'driver') ? 'selected' : '' ?>>Driver (Supir)</option>
                <option value="admin" <?= (isset($user) && $user['role'] == 'admin') ? 'selected' : '' ?>>Admin (Pengelola)</option>
            </select>
        </div>

        <button type="submit" class="btn-brutal" style="width: 100%; font-size: 1.2rem; background: var(--primary); padding: 14px; margin-top: 10px; font-weight: bold; border-radius: 4px; cursor: pointer; transition: transform 0.1s; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Simpan Pengguna
        </button>

    </form>
</div>
