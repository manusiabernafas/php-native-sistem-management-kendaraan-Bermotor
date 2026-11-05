<div class="topbar">
    <div style="display: flex; align-items: center;">
        <a href="<?= BASE_URL ?>/admin" style="text-decoration: none; color: var(--black); margin-right: 16px; display: flex; align-items: center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg></a>
        <h2 style="font-size: 1.4rem; margin: 0;">Kelola Pengguna</h2>
    </div>
</div>

<div style="padding: 24px; padding-bottom: 100px;">
    <?php Session::flash(); ?>

    <a href="<?= BASE_URL ?>/admin/tambahUser" class="btn-brutal" style="display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; margin-bottom: 24px; background: var(--primary); color: var(--black); font-weight: bold;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        Tambah User Baru
    </a>

    <?php if (empty($users)): ?>
        <p>Belum ada user terdaftar.</p>
    <?php else: ?>
        <?php foreach($users as $u): ?>
        <div style="background: var(--white); border: var(--border-thick); box-shadow: var(--shadow-hard); padding: 16px; margin-bottom: 16px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center;">
                <div style="margin-right: 16px; color: var(--dark); display: flex; align-items: center;">
                    <?php if($u['role'] == 'admin'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <?php endif; ?>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; margin: 0 0 4px 0;"><?= htmlspecialchars($u['name'] ?? 'User') ?></h3>
                    <p style="color: #666; font-size: 0.9rem; margin: 0 0 6px 0;">@<?= htmlspecialchars($u['username']) ?></p>
                    <span style="background: <?= $u['role'] == 'admin' ? 'var(--warning)' : 'var(--light)' ?>; padding: 2px 6px; border: 1px solid var(--black); font-size: 0.7rem; font-weight: bold; border-radius: 4px;">
                        <?= strtoupper($u['role']) ?>
                    </span>
                </div>
            </div>
            
            <div style="display: flex; gap: 8px;">
                <a href="<?= BASE_URL ?>/admin/editUser/<?= $u['id'] ?>" class="btn-brutal" style="background: var(--warning); padding: 6px 12px; font-size: 0.85rem; text-decoration: none; color: var(--black); min-height: unset; line-height: 1; display: inline-flex; align-items: center; gap: 4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                    Edit
                </a>
                <?php if($u['id'] !== Session::get('user_id')): ?>
                <a href="<?= BASE_URL ?>/admin/hapusUser/<?= $u['id'] ?>" class="btn-brutal" style="background: var(--danger); padding: 6px 12px; font-size: 0.85rem; text-decoration: none; color: var(--white); min-height: unset; line-height: 1; display: inline-flex; align-items: center; gap: 4px;" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna <?= htmlspecialchars($u['name']) ?>?');">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    Hapus
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
