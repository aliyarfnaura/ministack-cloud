/**
 * auth.js
 * Dipakai bersama oleh halaman login & register (ChromaStack).
 * - togglePassword()      : tampil/sembunyikan isi field password
 * - initPasswordMatch()   : validasi konfirmasi password secara real-time
 * - initSubmitLoading()   : kasih loading state + disable tombol saat form dikirim
 */

function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa fa-eye';
    }
}

/**
 * Mengecek apakah field password & password_confirmation sudah sama.
 * Dipasang khusus di halaman register (login tidak punya field ini).
 */
function initPasswordMatch() {
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');

    if (!password || !confirmation) return;

    // Buat elemen pesan kalau belum ada di markup
    let hint = confirmation.parentElement.parentElement.querySelector('.form-match-hint');
    if (!hint) {
        hint = document.createElement('span');
        hint.className = 'form-match-hint';
        confirmation.parentElement.parentElement.appendChild(hint);
    }

    function checkMatch() {
        if (confirmation.value.length === 0) {
            hint.textContent = '';
            hint.className = 'form-match-hint';
            return true;
        }

        const isMatch = password.value === confirmation.value;
        hint.textContent = isMatch ? 'Password cocok ✓' : 'Password belum sama';
        hint.className = 'form-match-hint ' + (isMatch ? 'is-match' : 'is-mismatch');
        return isMatch;
    }

    password.addEventListener('input', checkMatch);
    confirmation.addEventListener('input', checkMatch);

    // Cegah submit kalau jelas-jelas tidak cocok (validasi server tetap jadi penjaga utama)
    const form = confirmation.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!checkMatch() && confirmation.value.length > 0) {
                e.preventDefault();
                confirmation.focus();
            }
        });
    }
}

/**
 * Menambahkan loading state pada tombol submit supaya tidak bisa diklik dua kali,
 * dan memberi indikasi visual bahwa proses sedang berjalan.
 */
function initSubmitLoading() {
    document.querySelectorAll('.auth-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            // Kalau ada validasi konfirmasi password yang mencegah submit, biarkan event itu jalan dulu.
            if (form.dataset.blocked === 'true') return;

            const btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.disabled) return;

            btn.dataset.originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('is-loading');
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initPasswordMatch();
    initSubmitLoading();
});