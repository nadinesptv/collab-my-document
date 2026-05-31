function waitForEcho(callback, attempts = 0) {
    if (window.Echo) { callback(); return; }
    if (attempts > 200) {
        console.warn('Laravel Echo tidak tersedia. Jalankan: php artisan reverb:start');
        return;
    }
    setTimeout(() => waitForEcho(callback, attempts + 1), 50);
}

function initDocumentEditor() {
    const root = document.getElementById('editor-app');
    if (!root) return;

    const documentId = root.dataset.documentId;
    const userId     = Number(root.dataset.userId);
    const userName   = root.dataset.userName ?? 'User';
    const editor     = document.getElementById('editor');
    const statusEl   = document.getElementById('editor-status');
    const csrfToken  = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!editor) return;

    // ── Warna unik per user ──────────────────────────────────────────
    const COLORS = ['#e53e3e','#dd6b20','#d69e2e','#38a169','#3182ce','#805ad5','#d53f8c'];
    const colorOf = (id) => COLORS[id % COLORS.length];

    // ── Cursor overlay ───────────────────────────────────────────────
    const cursors = {}; // { userId: { el, label } }

    // Bungkus textarea dengan div relative agar cursor bisa diposisikan
    const wrapper = document.createElement('div');
    wrapper.style.cssText = 'position:relative; display:inline-block; width:100%;';
    editor.parentNode.insertBefore(wrapper, editor);
    wrapper.appendChild(editor);

    // Canvas tersembunyi untuk hitung posisi karakter
    const canvas = document.createElement('canvas');
    const ctx    = canvas.getContext('2d');

    function getCaretCoords(textarea, pos) {
        const style   = window.getComputedStyle(textarea);
        ctx.font      = `${style.fontSize} ${style.fontFamily}`;

        const lineHeight  = parseFloat(style.lineHeight) || parseFloat(style.fontSize) * 1.5;
        const paddingTop  = parseFloat(style.paddingTop);
        const paddingLeft = parseFloat(style.paddingLeft);
        const width       = textarea.clientWidth - paddingLeft - parseFloat(style.paddingRight);

        const text  = textarea.value.substring(0, pos);
        const words = text.split('');
        let line = 0, lineText = '';

        for (const ch of words) {
            if (ch === '\n') { line++; lineText = ''; continue; }
            const test = lineText + ch;
            if (ctx.measureText(test).width > width) { line++; lineText = ch; }
            else lineText = test;
        }

        const x = paddingLeft + ctx.measureText(lineText).width;
        const y = paddingTop  + line * lineHeight;
        return { x, y, lineHeight };
    }

    function upsertCursor(uId, uName, pos) {
        const color = colorOf(uId);

        if (!cursors[uId]) {
            const el    = document.createElement('div');
            el.style.cssText = `
                position:absolute; pointer-events:none; z-index:10;
                width:2px; background:${color}; border-radius:2px;
                transition: top .1s, left .1s;
            `;
            const label = document.createElement('span');
            label.style.cssText = `
                position:absolute; top:-20px; left:0;
                background:${color}; color:#fff;
                font-size:11px; padding:1px 6px; border-radius:4px;
                white-space:nowrap; font-family:sans-serif;
            `;
            label.textContent = uName;
            el.appendChild(label);
            wrapper.appendChild(el);
            cursors[uId] = { el, label };
        }

        const { x, y, lineHeight } = getCaretCoords(editor, pos);
        const cur = cursors[uId].el;
        cur.style.left   = `${x}px`;
        cur.style.top    = `${y}px`;
        cur.style.height = `${lineHeight}px`;
    }

    function removeCursor(uId) {
        if (cursors[uId]) {
            cursors[uId].el.remove();
            delete cursors[uId];
        }
    }

    // ── Status helper ────────────────────────────────────────────────
    let applyingRemote = false;
    let syncTimeout, saveTimeout, cursorTimeout;

    const setStatus = (text, color = 'text-slate-400') => {
        if (statusEl) {
            statusEl.textContent = text;
            statusEl.className   = `text-xs ${color}`;
        }
    };

    // ── Sync konten ──────────────────────────────────────────────────
    const syncContent = () => {
        fetch(`/document/${documentId}/sync`, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrfToken, Accept:'application/json' },
            body: JSON.stringify({ content: editor.value }),
        })
        .then(async (res) => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error();
            setStatus(data.broadcast === false
                ? 'Reverb mati — jalankan: php artisan reverb:start'
                : 'Tersinkron', data.broadcast === false ? 'text-amber-600' : 'text-green-600');
        })
        .catch(() => setStatus('Gagal simpan ke server', 'text-red-500'));
    };

    const saveRevision = () => {
        fetch(`/document/${documentId}/update`, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrfToken, Accept:'application/json' },
            body: JSON.stringify({ content: editor.value }),
        }).catch(() => {});
    };

    // ── Kirim posisi cursor ──────────────────────────────────────────
    const sendCursor = () => {
        const pos = editor.selectionStart;
        fetch(`/document/${documentId}/cursor`, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':csrfToken, Accept:'application/json' },
            body: JSON.stringify({ position: pos }),
        }).catch(() => {});
    };

    // ── Event listener editor ────────────────────────────────────────
    editor.addEventListener('input', () => {
        if (applyingRemote) return;
        setStatus('Mengetik...', 'text-blue-500');
        clearTimeout(syncTimeout);
        syncTimeout = setTimeout(syncContent, 80);
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveRevision, 2000);
    });

    editor.addEventListener('keyup',    () => { clearTimeout(cursorTimeout); cursorTimeout = setTimeout(sendCursor, 50); });
    editor.addEventListener('click',    sendCursor);
    editor.addEventListener('mouseup',  sendCursor);

    // ── WebSocket channel ────────────────────────────────────────────
    const channel = window.Echo.join(`document.${documentId}`);

    channel
        .here((users) => {
            setStatus('Terhubung — siap kolaborasi', 'text-green-600');
        })
        .joining((user) => {
            setStatus(`${user.name} bergabung`, 'text-green-600');
        })
        .leaving((user) => {
            removeCursor(user.id);
            setStatus(`${user.name} keluar`, 'text-slate-400');
        })
        .listen('.document.updated', (event) => {
            if (Number(event.userId) === userId) return;
            applyingRemote = true;
            editor.value   = event.content ?? '';
            applyingRemote = false;
            setStatus('Diperbarui oleh kolaborator', 'text-green-600');
        })
        .listen('.cursor.moved', (event) => {
            if (Number(event.userId) === userId) return;
            upsertCursor(event.userId, event.userName, event.position);
        })
        .error(() => setStatus('WebSocket gagal — jalankan: php artisan reverb:start', 'text-red-500'));
}

document.addEventListener('DOMContentLoaded', () => {
    waitForEcho(initDocumentEditor);
});