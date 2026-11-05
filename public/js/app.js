// Register Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        // Path adjusted for subdirectory deployment
        const path = window.location.pathname.substring(0, window.location.pathname.indexOf('/public') + 7);
        navigator.serviceWorker.register(path + '/sw.js')
            .then(reg => console.log('Service Worker Registered:', reg.scope))
            .catch(err => console.log('Service Worker Registration failed:', err));
    });
}

// Offline/Online Status Detection
window.addEventListener('online', () => {
    showToast('✅ Sinyal kembali! Aplikasi terhubung ke server.', 'success');
    document.body.classList.remove('is-offline');
});

window.addEventListener('offline', () => {
    showToast('⚠️ Koneksi terputus. Anda dalam mode offline.', 'danger');
    document.body.classList.add('is-offline');
});

// Toast Notification System (Neo-Brutalism Style)
function showToast(message, type = 'primary') {
    const toast = document.createElement('div');
    toast.className = `brutal-toast bg-${type}`;
    toast.innerHTML = `<div style="font-weight: bold; font-family: 'Space Grotesk', sans-serif;">${message}</div>`;
    
    document.body.appendChild(toast);
    
    // Play micro-sound or vibration
    if (navigator.vibrate) navigator.vibrate(50);
    
    // Animate in
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Animate out
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Add to Home Screen (A2HS) Logic
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    // Jika sudah di-instal / berjalan di mode Standalone, abaikan prompt instalasi
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
    if (isStandalone) {
        return;
    }

    e.preventDefault();
    deferredPrompt = e;
    // Tampilkan Bottom Sheet & Glass Overlay
    const modal = document.getElementById('a2hs-modal');
    const overlay = document.getElementById('glass-overlay');
    if(modal) modal.style.display = 'block';
    if(overlay) overlay.style.display = 'block';
});

document.addEventListener('DOMContentLoaded', () => {
    const btnAccept = document.getElementById('btn-install-accept');
    const modal = document.getElementById('a2hs-modal');
    const overlay = document.getElementById('glass-overlay');

    if (btnAccept) {
        btnAccept.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    if(modal) modal.style.display = 'none';
                    if(overlay) overlay.style.display = 'none';
                }
                deferredPrompt = null;
            }
        });
    }

    // Tutup jika klik overlay
    if(overlay) {
        overlay.addEventListener('click', () => {
            if(modal) modal.style.display = 'none';
            overlay.style.display = 'none';
        });
    }

    // Tutup jika klik overlay
    if(overlay) {
        overlay.addEventListener('click', () => {
            if(modal) modal.style.display = 'none';
            overlay.style.display = 'none';
        });
    }
});

// Fitur 8: PWA Native Notification (Simulasi Push via Polling untuk Admin)
if ('Notification' in window) {
    // Minta izin saat PWA dimuat
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
}

// Cek otomatis setiap 30 detik jika user berada di halaman Admin
if (window.location.pathname.includes('/admin') || window.location.pathname.includes('/monitor')) {
    setInterval(async () => {
        try {
            // Asumsi BASE_URL sudah ada di script HTML sebelumnya
            const url = window.location.origin + window.location.pathname.split('/public')[0] + '/public/index.php?url=admin/apiCheckUrgent';
            const res = await fetch(url);
            const data = await res.json();
            
            if (data && data.alert) {
                // Mainkan suara darurat
                playClickSound(); // bisa diganti sirine
                
                // Munculkan Native Notification
                if (Notification.permission === 'granted') {
                    const mobilIdentitas = data.alert.no_polisi || data.alert.armada_id;
                    navigator.serviceWorker.ready.then(function(registration) {
                        registration.showNotification("🚨 DARURAT: Mobil " + mobilIdentitas, {
                            body: data.alert.keterangan,
                            icon: '/public/img/icon.png',
                            vibrate: [500, 200, 500, 200, 500],
                            tag: 'urgent-alert-' + data.alert.id
                        });
                    }).catch(() => {
                        // Fallback jika SW tidak siap
                        new Notification("🚨 DARURAT: Mobil " + mobilIdentitas, {
                            body: data.alert.keterangan,
                            vibrate: [500, 200, 500]
                        });
                    });
                }
            }
        } catch(e) {
            console.log("Polling error: ", e);
        }
    }, 30000); // Cek tiap 30 detik
}

// Micro-Sound Effects (AudioContext API)
function playClickSound() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.type = 'sine';
        osc.frequency.setValueAtTime(800, ctx.currentTime);
        gain.gain.setValueAtTime(0.05, ctx.currentTime); // volume kecil
        osc.start();
        osc.stop(ctx.currentTime + 0.05); // bunyi sangat cepat
    } catch(e) {}
}

// Pull-to-Refresh Gestures
let touchstartY = 0;
document.addEventListener('touchstart', e => { 
    touchstartY = e.touches[0].clientY; 
}, {passive: true});

document.addEventListener('touchend', e => { 
    const touchendY = e.changedTouches[0].clientY;
    // Jika user men-scroll ke atas di paling ujung layar (pull down)
    if (window.scrollY === 0 && touchendY - touchstartY > 120) {
        // Tampilkan indikator refresh (menggunakan loader brutal kita)
        const overlay = document.getElementById('brutal-loader');
        if(overlay) overlay.style.display = 'flex';
        setTimeout(() => location.reload(), 300);
    }
});

// Tambahkan suara klik ke semua tombol dan link
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('click', () => {
            playClickSound();
        });
    });

    // Add loading overlay to body
    const overlay = document.createElement('div');
    overlay.id = 'brutal-loader';
    overlay.innerHTML = `<div class="loader-box">⏳ LOADING...</div>`;
    document.body.appendChild(overlay);

    // Show loader on link click
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', (e) => {
            if (link.href && !link.href.startsWith('javascript') && !link.href.startsWith('#') && link.target !== '_blank') {
                overlay.style.display = 'flex';
            }
        });
    });

    // Show loader on form submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            overlay.style.display = 'flex';
        });
    });
});
