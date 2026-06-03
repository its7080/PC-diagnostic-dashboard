<?php
$session_id = isset($_GET['session_id']) ? trim((string)$_GET['session_id']) : '';

$allKeys = [
    'Escape','F1','F2','F3','F4','F5','F6','F7','F8','F9','F10','F11','F12',
    'PrintScreen','ScrollLock','Pause','MediaTrackPrevious','MediaPlayPause','MediaTrackNext','VolumeMute','VolumeDown','VolumeUp',
    'Backquote','Digit1','Digit2','Digit3','Digit4','Digit5','Digit6','Digit7','Digit8','Digit9','Digit0','Minus','Equal','Backspace',
    'Insert','Home','PageUp','Delete','End','PageDown',
    'Tab','KeyQ','KeyW','KeyE','KeyR','KeyT','KeyY','KeyU','KeyI','KeyO','KeyP','BracketLeft','BracketRight','Backslash',
    'CapsLock','KeyA','KeyS','KeyD','KeyF','KeyG','KeyH','KeyJ','KeyK','KeyL','Semicolon','Quote','Enter',
    'ShiftLeft','KeyZ','KeyX','KeyC','KeyV','KeyB','KeyN','KeyM','Comma','Period','Slash','ShiftRight',
    'ControlLeft','MetaLeft','AltLeft','Space','AltRight','MetaRight','ContextMenu','ControlRight',
    'ArrowLeft','ArrowDown','ArrowRight','ArrowUp',
    'NumLock','NumpadDivide','NumpadMultiply','NumpadSubtract','Numpad7','Numpad8','Numpad9','NumpadAdd','Numpad4','Numpad5','Numpad6','Numpad1','Numpad2','Numpad3','Numpad0','NumpadDecimal','NumpadEnter',
    'LaunchMail','LaunchCalculator','MouseLeft','MouseMiddle','MouseRight'
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Missing Keys Report<?= $session_id !== '' ? ' — ' . htmlspecialchars($session_id) : '' ?></title>
<style>
body { font-family: Inter, system-ui, Arial, sans-serif; margin: 0; background: #eef2ff; color: #111827; padding: 20px; }
.box { max-width: 980px; margin: 0 auto; background: #fff; border:1px solid #dbeafe; border-radius: 14px; padding: 18px; }
.grid { display:grid; grid-template-columns: repeat(3,minmax(140px,1fr)); gap:10px; }
.stat { background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px; }
ul { list-style:none; padding:0; display:grid; grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); gap:8px; }
li { border-radius:8px; padding:10px; border:1px solid #e5e7eb; background:#fafafa; }
.ok { background:#ecfdf5; border-color:#10b981; }
.missing { background:#fef2f2; border-color:#ef4444; }
.count { float:right; font-weight:700; color:#334155; }
.badge { display:inline-block; border-radius:999px; padding:4px 8px; background:#dbeafe; color:#1d4ed8; font-weight:700; }
.warning { border:1px solid #f59e0b; background:#fffbeb; border-radius:10px; padding:10px; margin:12px 0; color:#92400e; }
</style>
</head>
<body>
<div class="box">
<h1>Missing Keys Report</h1>
<p>Session: <strong id="sessionId"><?= $session_id !== '' ? htmlspecialchars($session_id) : 'No session selected' ?></strong> — total detected: <strong id="detectedCount">0</strong></p>
<div id="notice" class="warning" hidden></div>
<div class="grid">
    <div class="stat"><div>Total mapped keys</div><strong id="totalKeys">0</strong></div>
    <div class="stat"><div>Detected keys</div><strong id="detectedKeys">0</strong></div>
    <div class="stat"><div>Coverage</div><strong id="coverage">0%</strong></div>
</div>
<p><span class="badge">No database required</span> This report reads the report link snapshot first, then falls back to this browser's saved test counts for the selected session.</p>
<ul id="keyList"></ul>
</div>
<script>
(() => {
    const sessionId = <?= json_encode($session_id) ?>;
    const allKeys = <?= json_encode($allKeys) ?>;
    const counts = {};
    const storageKey = `keyboardMouseDiagnostic:${sessionId}:counts`;

    function showNotice(message) {
        const notice = document.getElementById('notice');
        notice.textContent = message;
        notice.hidden = false;
    }

    if (!sessionId) {
        showNotice('No session_id was provided. Open the diagnostic page and use its Missing Keys Report link.');
    } else {
        const hashParams = new URLSearchParams(window.location.hash.slice(1));
        const hashCounts = hashParams.get('counts');

        try {
            Object.assign(counts, JSON.parse(hashCounts || localStorage.getItem(storageKey) || '{}'));
        } catch (error) {
            showNotice('Unable to read saved key counts from the report link or this browser. Local storage may be disabled or unavailable.');
        }

        if (!Object.keys(counts).length) {
            showNotice('No saved key presses were found for this session. Return to the diagnostic page, press keys, then refresh this report.');
        }
    }

    const detected = Object.keys(counts).filter(code => allKeys.includes(code));
    const detectedMap = Object.fromEntries(detected.map(code => [code, true]));
    const coverage = allKeys.length ? Math.round((detected.length / allKeys.length) * 1000) / 10 : 0;

    document.getElementById('totalKeys').textContent = allKeys.length;
    document.getElementById('detectedKeys').textContent = detected.length;
    document.getElementById('detectedCount').textContent = detected.length;
    document.getElementById('coverage').textContent = `${coverage}%`;

    document.getElementById('keyList').innerHTML = allKeys.map(code => {
        const ok = detectedMap[code];
        const count = Number(counts[code] || 0);
        return `<li class="${ok ? 'ok' : 'missing'}">${ok ? '✔' : '✘'} ${code}${ok ? `<span class="count">${count}</span>` : ''}</li>`;
    }).join('');
})();
</script>
</body>
</html>
