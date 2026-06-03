<?php
// index.php
function renderKey(string $code, string $label, string $class = '', float $units = 1): void
{
    $classes = trim('key ' . $class);
    $safeCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
    $safeClasses = htmlspecialchars($classes, ENT_QUOTES, 'UTF-8');
    $width = 46 * $units + 8 * ($units - 1);
    echo '<div class="' . $safeClasses . '" data-code="' . $safeCode . '" style="--w:' . $width . 'px">' . $label . '</div>';
}

$functionGroups = [
    [['Escape', 'Esc', 'utility']],
    [['F1', 'F1', 'function'], ['F2', 'F2', 'function'], ['F3', 'F3', 'function'], ['F4', 'F4', 'function']],
    [['F5', 'F5', 'function'], ['F6', 'F6', 'function'], ['F7', 'F7', 'function'], ['F8', 'F8', 'function']],
    [['F9', 'F9', 'function'], ['F10', 'F10', 'function'], ['F11', 'F11', 'function'], ['F12', 'F12', 'function']],
];

$typingRows = [
    [
        ['Backquote', '<span>~</span><span>`</span>'], ['Digit1', '<span>!</span><span>1</span>'], ['Digit2', '<span>@</span><span>2</span>'],
        ['Digit3', '<span>#</span><span>3</span>'], ['Digit4', '<span>$</span><span>4</span>'], ['Digit5', '<span>%</span><span>5</span>'],
        ['Digit6', '<span>^</span><span>6</span>'], ['Digit7', '<span>&amp;</span><span>7</span>'], ['Digit8', '<span>*</span><span>8</span>'],
        ['Digit9', '<span>(</span><span>9</span>'], ['Digit0', '<span>)</span><span>0</span>'], ['Minus', '<span>_</span><span>-</span>'],
        ['Equal', '<span>+</span><span>=</span>'], ['Backspace', 'Backspace', 'wide', 2],
    ],
    [
        ['Tab', 'Tab', 'wide', 1.5], ['KeyQ', 'Q'], ['KeyW', 'W'], ['KeyE', 'E'], ['KeyR', 'R'], ['KeyT', 'T'], ['KeyY', 'Y'],
        ['KeyU', 'U'], ['KeyI', 'I'], ['KeyO', 'O'], ['KeyP', 'P'], ['BracketLeft', '<span>{</span><span>[</span>'],
        ['BracketRight', '<span>}</span><span>]</span>'], ['Backslash', '<span>|</span><span>\</span>', 'wide', 1.5],
    ],
    [
        ['CapsLock', 'Caps Lock', 'wide', 1.75], ['KeyA', 'A'], ['KeyS', 'S'], ['KeyD', 'D'], ['KeyF', 'F'], ['KeyG', 'G'],
        ['KeyH', 'H'], ['KeyJ', 'J'], ['KeyK', 'K'], ['KeyL', 'L'], ['Semicolon', '<span>:</span><span>;</span>'],
        ['Quote', '<span>&quot;</span><span>&apos;</span>'], ['Enter', 'Enter', 'enter wide', 2.25],
    ],
    [
        ['ShiftLeft', 'Shift', 'wide', 2.25], ['KeyZ', 'Z'], ['KeyX', 'X'], ['KeyC', 'C'], ['KeyV', 'V'], ['KeyB', 'B'],
        ['KeyN', 'N'], ['KeyM', 'M'], ['Comma', '<span>&lt;</span><span>,</span>'], ['Period', '<span>&gt;</span><span>.</span>'],
        ['Slash', '<span>?</span><span>/</span>'], ['ShiftRight', 'Shift', 'wide', 2.75],
    ],
    [
        ['ControlLeft', 'Ctrl', 'system', 1.25], ['MetaLeft', 'Win', 'system', 1.25], ['AltLeft', 'Alt', 'system', 1.25],
        ['Space', 'Space', 'space', 6.25], ['AltRight', 'Alt', 'system', 1.25], ['MetaRight', 'Win', 'system', 1.25],
        ['ContextMenu', 'Menu', 'system', 1.25], ['ControlRight', 'Ctrl', 'system', 1.25],
    ],
];

$navTop = [
    ['PrintScreen', 'PrtSc'], ['ScrollLock', 'ScrLk'], ['Pause', 'Pause'],
];
$navRows = [
    [['Insert', 'Ins'], ['Home', 'Home'], ['PageUp', 'PgUp']],
    [['Delete', 'Del'], ['End', 'End'], ['PageDown', 'PgDn']],
];
$numpadRows = [
    [['NumLock', 'Num'], ['NumpadDivide', '/'], ['NumpadMultiply', '*'], ['NumpadSubtract', '-']],
    [['Numpad7', '<span>7</span><small>Home</small>'], ['Numpad8', '<span>8</span><small>↑</small>'], ['Numpad9', '<span>9</span><small>PgUp</small>'], ['NumpadAdd', '+', 'tall']],
    [['Numpad4', '<span>4</span><small>←</small>'], ['Numpad5', '5'], ['Numpad6', '<span>6</span><small>→</small>']],
    [['Numpad1', '<span>1</span><small>End</small>'], ['Numpad2', '<span>2</span><small>↓</small>'], ['Numpad3', '<span>3</span><small>PgDn</small>'], ['NumpadEnter', 'Enter', 'enter tall']],
    [['Numpad0', '<span>0</span><small>Ins</small>', 'wide', 2], ['NumpadDecimal', '<span>.</span><small>Del</small>']],
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Keyboard & Mouse Diagnostic Dashboard</title>
    <style>
    :root {
        --bg: #05070d;
        --panel: #0d111b;
        --ink: #e2e8f0;
        --muted: #94a3b8;
        --accent: #60a5fa;
        --good: #10b981;
        --key: #1f2a3a;
        --key-top: #354257;
        --key-border: #4b5e7d;
        --function: #7c3aed;
        --system: #334155;
        --enter: #059669;
        --nav: #0f766e;
        --numpad: #1d4ed8;
    }

    * { box-sizing: border-box; }

    body {
        min-height: 100vh;
        margin: 0;
        padding: 20px;
        color: var(--ink);
        font-family: Inter, system-ui, Arial, sans-serif;
        background: radial-gradient(circle at 20% 20%, #151b2b 0%, var(--bg) 60%);
    }

    .container { max-width: 1480px; margin: 0 auto; }

    .card {
        padding: 18px;
        border: 1px solid rgba(148,163,184,.2);
        border-radius: 16px;
        background: rgba(15,23,42,.75);
        backdrop-filter: blur(8px);
    }

    h1 { margin: 0 0 8px; font-size: 1.5rem; }
    .lead { margin: 0 0 14px; color: var(--muted); }
    .controls { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }

    .btn {
        border: 0;
        border-radius: 10px;
        padding: 9px 13px;
        color: #f8fafc;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        background: #1f2937;
    }

    .btn.primary { background: linear-gradient(120deg, #2563eb, var(--accent)); }
    .btn.good { background: linear-gradient(120deg, #059669, var(--good)); }

    .stats {
        display: grid;
        grid-template-columns: repeat(3,minmax(160px,1fr));
        gap: 10px;
        margin-bottom: 14px;
    }

    .stat {
        padding: 10px;
        border: 1px solid rgba(148,163,184,.2);
        border-radius: 10px;
        background: var(--panel);
    }

    .stat .label { color: var(--muted); font-size: .86rem; }
    .stat .value { margin-top: 4px; font-weight: 700; }

    .keyboard-area {
        overflow-x: auto;
        padding: 8px 2px 6px;
    }

    .keyboard-shell {
        width: max-content;
        min-width: 1200px;
        margin: 0 auto;
        padding: 18px;
        border: 1px solid rgba(148,163,184,.18);
        border-radius: 18px;
        background: #0b0f17;
        user-select: none;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.05), 0 22px 48px rgba(0,0,0,.34);
    }

    .keyboard-row,
    .segment-row,
    .key-group,
    .arrow-grid,
    .numpad-grid,
    .nav-grid {
        display: flex;
        gap: 8px;
    }

    .keyboard-row { align-items: flex-start; margin-bottom: 12px; }
    .key-group { padding: 0 8px 0 0; }

    .segment-grid {
        display: grid;
        grid-template-columns: auto auto auto;
        gap: 18px;
        align-items: end;
    }

    .segment {
        padding: 10px;
        border: 1px solid rgba(148,163,184,.16);
        border-radius: 14px;
        background: rgba(15, 23, 42, .62);
    }

    .segment-title {
        margin: 0 0 8px;
        color: #cbd5e1;
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .segment-row { margin-bottom: 8px; }
    .segment-row:last-child { margin-bottom: 0; }

    .key {
        position: relative;
        display: inline-flex;
        flex: 0 0 var(--w, 46px);
        width: var(--w, 46px);
        height: 46px;
        align-items: center;
        justify-content: center;
        padding: 6px;
        border: 1px solid var(--key-border);
        border-radius: 8px;
        color: #eaf2ff;
        font-size: 13px;
        line-height: 1.05;
        text-align: center;
        cursor: pointer;
        background: linear-gradient(180deg, var(--key-top), var(--key));
        box-shadow: inset 0 1px 0 rgba(255,255,255,.1), 0 4px 8px rgba(0,0,0,.32);
        transition: transform .07s ease, box-shadow .07s ease, background .2s ease, outline-color .16s ease;
    }

    .key span,
    .key small { display: block; }
    .key small { margin-top: 3px; color: #cbd5e1; font-size: 10px; }
    .key.wide { justify-content: flex-start; padding-left: 10px; }
    .key.space { justify-content: center; }
    .key.function { --key: #4c1d95; --key-top: var(--function); --key-border: #8b5cf6; }
    .key.system { --key: #1f2937; --key-top: var(--system); --key-border: #64748b; }
    .key.enter { --key: #065f46; --key-top: var(--enter); --key-border: #34d399; }
    .key.nav { --key: #134e4a; --key-top: var(--nav); --key-border: #2dd4bf; }
    .key.numpad { --key: #1e3a8a; --key-top: var(--numpad); --key-border: #60a5fa; }
    .key.utility { --key: #374151; --key-top: #4b5563; --key-border: #9ca3af; }
    .key.tall { height: 100px; }

    .key.pressed {
        transform: translateY(2px);
        box-shadow: inset 0 3px 7px rgba(0,0,0,.45);
        background: linear-gradient(180deg,#1d4ed8,#1e3a8a);
    }

    .key.ok { outline: 2px solid rgba(16,185,129,.86); outline-offset: 2px; }

    .nav-grid,
    .numpad-grid {
        display: grid;
        gap: 8px;
    }

    .nav-grid { grid-template-columns: repeat(3, 46px); }
    .numpad-grid { grid-template-columns: repeat(4, 46px); align-items: stretch; }
    .nav-grid .key,
    .numpad-grid .key { width: auto; flex-basis: auto; }
    .numpad-grid .tall { grid-row: span 2; height: auto; }
    .numpad-grid .wide { grid-column: span 2; width: auto; }

    .arrow-segment { align-self: end; }
    .arrow-grid {
        display: grid;
        grid-template-columns: repeat(3, 46px);
        grid-template-rows: repeat(2, 46px);
        gap: 8px;
    }

    .arrow-grid .key { width: auto; flex-basis: auto; }
    .arrow-up { grid-column: 2; grid-row: 1; }
    .arrow-left { grid-column: 1; grid-row: 2; }
    .arrow-down { grid-column: 2; grid-row: 2; }
    .arrow-right { grid-column: 3; grid-row: 2; }

    .legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 16px;
        margin-top: 14px;
        color: #cbd5e1;
        font-size: .86rem;
    }

    .legend-item { display: inline-flex; align-items: center; gap: 7px; }
    .legend-item::before { width: 16px; height: 16px; content: ""; border-radius: 4px; background: var(--swatch); }
    .legend-item.typing { --swatch: #354257; }
    .legend-item.function { --swatch: var(--function); }
    .legend-item.system { --swatch: var(--system); }
    .legend-item.enter { --swatch: var(--enter); }
    .legend-item.nav { --swatch: var(--nav); }
    .legend-item.numpad { --swatch: var(--numpad); }

    .mouse-pad { margin-top: 20px; display:flex; align-items:center; justify-content:center; }

    .mouse {
        position: relative;
        width: 170px;
        height: 230px;
        border: 2px solid #6b7280;
        border-radius: 95px;
        background: linear-gradient(180deg,#d1d5db,#9ca3af);
        box-shadow: 0 20px 32px rgba(0,0,0,.35);
    }

    .mouse-btn {
        position: absolute;
        top: 12px;
        width: 72px;
        height: 90px;
        border: 1px solid #6b7280;
        border-radius: 35px 35px 10px 10px;
        background: #e5e7eb;
    }

    .mouse-btn.left { left: 10px; }
    .mouse-btn.right { right: 10px; }

    .wheel {
        position: absolute;
        top: 44px;
        left: 50%;
        width: 16px;
        height: 34px;
        border-radius: 8px;
        background: #4b5563;
        transform: translateX(-50%);
    }

    .mouse-btn.active, .wheel.active { background:#60a5fa; }
    .counts { margin-top: 12px; color: var(--muted); font-size: .92rem; }

    .site-footer {
        margin: 22px auto 0;
        padding: 18px 10px;
        color: #cbd5e1;
        font-size: 14px;
        text-align: center;
    }

    .site-footer a { color: #67e8f9; }

    @media (max-width: 900px) {
        body { padding: 12px; }
        .stats { grid-template-columns: 1fr; }
        .keyboard-shell { min-width: 1120px; padding: 12px; }
    }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Keyboard & Mouse Diagnostic Dashboard</h1>
        <p class="lead">Test a segmented QWERTY ANSI keyboard: function row, typing block, navigation keys, cursor cluster, numpad, and mouse buttons.</p>

        <div class="controls">
            <button id="resetBtn" class="btn">Reset UI</button>
            <button id="clearCountsBtn" class="btn">Clear Counts</button>
            <button id="markAllBtn" class="btn">Mark All OK</button>
            <a id="reportLink" class="btn good" href="#" target="_blank">Missing Keys Report</a>
            <button id="toggleSounds" class="btn primary">Sound: Off</button>
        </div>

        <div class="stats">
            <div class="stat"><div class="label">Last input</div><div id="lastKey" class="value">—</div></div>
            <div class="stat"><div class="label">Unique keys detected</div><div id="uniqueCount" class="value">0</div></div>
            <div class="stat"><div class="label">Session ID</div><div id="sessionBox" class="value"></div></div>
        </div>

        <div class="keyboard-area">
            <div id="keyboard" class="keyboard-shell" aria-label="segmented QWERTY ANSI keyboard layout">
                <div class="keyboard-row" aria-label="function key row">
                    <?php foreach ($functionGroups as $group): ?>
                        <div class="key-group">
                            <?php foreach ($group as $key) renderKey($key[0], $key[1], $key[2] ?? '', $key[3] ?? 1); ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="key-group">
                        <?php foreach ($navTop as $key) renderKey($key[0], $key[1], 'utility', 1); ?>
                    </div>
                </div>

                <div class="segment-grid">
                    <section class="segment typing-segment" aria-label="main typing keys">
                        <p class="segment-title">QWERTY ANSI typing block</p>
                        <?php foreach ($typingRows as $row): ?>
                            <div class="segment-row">
                                <?php foreach ($row as $key) renderKey($key[0], $key[1], $key[2] ?? '', $key[3] ?? 1); ?>
                            </div>
                        <?php endforeach; ?>
                    </section>

                    <div>
                        <section class="segment" aria-label="navigation keys">
                            <p class="segment-title">Navigation</p>
                            <div class="nav-grid">
                                <?php foreach ($navRows as $row): ?>
                                    <?php foreach ($row as $key) renderKey($key[0], $key[1], 'nav', 1); ?>
                                <?php endforeach; ?>
                            </div>
                        </section>
                        <section class="segment arrow-segment" aria-label="cursor control keys" style="margin-top:18px;">
                            <p class="segment-title">Cursor</p>
                            <div class="arrow-grid">
                                <div class="key nav arrow-up" data-code="ArrowUp">↑</div>
                                <div class="key nav arrow-left" data-code="ArrowLeft">←</div>
                                <div class="key nav arrow-down" data-code="ArrowDown">↓</div>
                                <div class="key nav arrow-right" data-code="ArrowRight">→</div>
                            </div>
                        </section>
                    </div>

                    <section class="segment" aria-label="numeric keypad">
                        <p class="segment-title">Numeric keypad</p>
                        <div class="numpad-grid">
                            <?php foreach ($numpadRows as $row): ?>
                                <?php foreach ($row as $key) renderKey($key[0], $key[1], trim('numpad ' . ($key[2] ?? '')), $key[3] ?? 1); ?>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>

                <div class="legend" aria-label="keyboard segment legend">
                    <span class="legend-item typing">Typing block</span>
                    <span class="legend-item function">Function keys</span>
                    <span class="legend-item system">System modifiers</span>
                    <span class="legend-item enter">Enter keys</span>
                    <span class="legend-item nav">Navigation / cursor</span>
                    <span class="legend-item numpad">Numeric keypad</span>
                </div>
            </div>
        </div>

        <div class="mouse-pad">
            <div class="mouse" aria-label="mouse visualizer">
                <div class="mouse-btn left" id="mouseLeft"></div>
                <div class="mouse-btn right" id="mouseRight"></div>
                <div class="wheel" id="mouseMiddle"></div>
            </div>
        </div>

        <div class="counts" id="counts">No keys pressed yet.</div>
    </div>
</div>

<footer class="site-footer">
    <p>&copy; <span id="year"></span> Keyboard Tester Tool — Created by <strong>Anupam Manna</strong> <span>(Data Scientist &amp; Software Developer)</span></p>
    <p>📧 Email: <a href="mailto:contact@keyboard-tester.free.nf">am7059141480@gmail.com</a> | 📱 Phone: <span>+91</span><span>7059</span><span>141480</span></p>
    <p><a href="https://keyboard-tester.free.nf/privacy-policy">Privacy Policy</a> | <a href="https://keyboard-tester.free.nf/terms">Terms of Service</a></p>
</footer>

<script>
(() => {
    const sessionId = Date.now().toString(36) + Math.random().toString(36).slice(2, 9);
    const reportLink = document.getElementById('reportLink');
    const sessionBox = document.getElementById('sessionBox');
    const year = document.getElementById('year');
    reportLink.href = `report.php?session_id=${encodeURIComponent(sessionId)}`;
    sessionBox.textContent = sessionId;
    year.textContent = new Date().getFullYear();

    const els = {
        last: document.getElementById('lastKey'),
        unique: document.getElementById('uniqueCount'),
        counts: document.getElementById('counts'),
        keyboard: document.getElementById('keyboard'),
        reset: document.getElementById('resetBtn'),
        clear: document.getElementById('clearCountsBtn'),
        markAll: document.getElementById('markAllBtn'),
        toggleSounds: document.getElementById('toggleSounds')
    };
    const mouseEls = {
        MouseLeft: document.getElementById('mouseLeft'),
        MouseMiddle: document.getElementById('mouseMiddle'),
        MouseRight: document.getElementById('mouseRight')
    };

    const keyEls = {};
    els.keyboard.querySelectorAll('[data-code]').forEach(el => {
        keyEls[el.dataset.code] = el;
        el.addEventListener('mousedown', () => hit(el.dataset.code, el.textContent.trim()));
    });

    const counts = {};
    let queue = [];
    let timer = null;
    let sounds = false;

    function tone() {
        if (!sounds || !window.AudioContext) return;
        const ctx = new AudioContext();
        const o = ctx.createOscillator();
        const g = ctx.createGain();
        o.type = 'triangle';
        o.frequency.value = 220;
        g.gain.value = 0.02;
        o.connect(g);
        g.connect(ctx.destination);
        o.start();
        o.stop(ctx.currentTime + 0.03);
    }

    function queueLog(item) {
        queue.push(item);
        if (!timer) timer = setTimeout(flush, 120);
    }

    function flush() {
        const items = queue.splice(0);
        timer = null;
        fetch('log_key.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({items})
        }).catch(() => {});
    }

    function updateStats() {
        const keys = Object.keys(counts);
        els.unique.textContent = keys.length;
        els.counts.textContent = keys.length ? keys.map(k => `${k}: ${counts[k]}`).join(' • ') : 'No keys pressed yet.';
    }

    function hit(code, keyValue) {
        els.last.textContent = `${keyValue} · ${code}`;
        counts[code] = (counts[code] || 0) + 1;
        if (keyEls[code]) {
            keyEls[code].classList.add('pressed', 'ok');
            setTimeout(() => keyEls[code]?.classList.remove('pressed'), 110);
        }
        if (mouseEls[code]) {
            mouseEls[code].classList.add('active');
            setTimeout(() => mouseEls[code]?.classList.remove('active'), 110);
        }
        tone();
        updateStats();
        queueLog({ session_id: sessionId, key_code: code, key_value: String(keyValue || code) });
    }

    window.addEventListener('keydown', e => {
        if (!['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) e.preventDefault();
        hit(e.code, e.key);
    }, { passive:false });

    window.addEventListener('mousedown', e => {
        if (e.target.closest('.key')) return;
        const map = {0:'MouseLeft',1:'MouseMiddle',2:'MouseRight'};
        const code = map[e.button];
        if (code) hit(code, code);
    });

    window.addEventListener('contextmenu', e => e.preventDefault());

    els.reset.addEventListener('click', () => {
        Object.values(keyEls).forEach(k => k.classList.remove('pressed','ok'));
        Object.keys(counts).forEach(k => delete counts[k]);
        els.last.textContent = '—';
        updateStats();
    });
    els.clear.addEventListener('click', () => {
        Object.keys(counts).forEach(k => delete counts[k]);
        updateStats();
    });
    els.markAll.addEventListener('click', () => Object.values(keyEls).forEach(k => k.classList.add('ok')));
    els.toggleSounds.addEventListener('click', () => {
        sounds = !sounds;
        els.toggleSounds.textContent = `Sound: ${sounds ? 'On' : 'Off'}`;
    });
    updateStats();
})();
</script>
</body>
</html>
