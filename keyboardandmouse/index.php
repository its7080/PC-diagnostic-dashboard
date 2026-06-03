<?php
// index.php
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
        --warn: #f59e0b;
        --typewriter: #98c9d8;
        --function: #f2c575;
        --enter: #f5f1b8;
        --system: #eba5a5;
        --numpad: #9eadd0;
        --other: #ffffff;
        --application: #a97cad;
        --cursor: #a8d2b2;
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

    .container { max-width: 1800px; margin: 0 auto; }

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
        padding: 12px 2px 6px;
    }

    .keyboard-shell {
        width: max-content;
        min-width: 1740px;
        margin: 0 auto;
        padding: 54px 64px 44px;
        border-radius: 46px;
        background: #f4f5f7;
        color: #28282d;
        user-select: none;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.85), 0 28px 70px rgba(0,0,0,.32);
    }

    .keyboard-layout {
        display: grid;
        grid-template-columns: repeat(26, 58px);
        grid-template-rows: repeat(6, 56px);
        gap: 8px 10px;
        align-items: stretch;
    }

    .key {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 0;
        min-height: 0;
        padding: 5px 7px;
        border: 0;
        border-radius: 7px;
        color: #29272b;
        font-size: 16px;
        line-height: 1.08;
        text-align: center;
        cursor: pointer;
        background: var(--typewriter);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.34), 0 2px 0 rgba(0,0,0,.03);
        transition: transform .07s ease, box-shadow .07s ease, filter .16s ease, outline-color .16s ease;
    }

    .key .primary { display: block; font-size: 27px; line-height: 1; }
    .key .sub { display: block; margin-top: 4px; font-size: 16px; }
    .key .small { font-size: 14px; }
    .key .corner { position: absolute; left: 7px; bottom: 6px; font-size: 16px; }
    .key .corner.top { top: 6px; bottom: auto; }
    .key .corner.right { right: 7px; left: auto; }
    .key.left-label { align-items: flex-start; justify-content: flex-start; text-align: left; }
    .key.right-label { align-items: flex-end; justify-content: flex-end; text-align: right; }
    .key.big-symbol { font-size: 34px; }
    .key.two-line { font-size: 16px; }

    .function { background: var(--function); }
    .enter-key { background: var(--enter); }
    .system { background: var(--system); }
    .numpad { background: var(--numpad); }
    .other { background: var(--other); }
    .application { background: var(--application); }
    .cursor { background: var(--cursor); }
    .blank { color: transparent; }

    .key.pressed {
        transform: translateY(3px);
        box-shadow: inset 0 4px 9px rgba(0,0,0,.22);
        filter: saturate(1.2) brightness(.93);
    }

    .key.ok { outline: 3px solid rgba(16,185,129,.62); outline-offset: 2px; }

    .lock-lights {
        grid-column: 22 / 26;
        grid-row: 1;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        gap: 18px;
        padding: 0 0 0 8px;
        font-size: 14px;
        line-height: 1.05;
    }

    .lock-light {
        display: grid;
        grid-template-columns: 14px auto;
        align-items: center;
        gap: 6px;
    }

    .lock-light::before {
        width: 14px;
        height: 7px;
        content: "";
        background: #29292e;
    }

    .keyboard-legend {
        display: grid;
        grid-template-columns: repeat(3, minmax(220px, 1fr));
        gap: 18px 64px;
        max-width: 1120px;
        margin: 34px auto 0;
        color: #f3f4f6;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 1.05rem;
        font-weight: 800;
    }

    .legend-swatch {
        width: 58px;
        height: 54px;
        border-radius: 7px;
        background: var(--typewriter);
    }

    .legend-swatch.function { background: var(--function); }
    .legend-swatch.enter-key { background: var(--enter); }
    .legend-swatch.system { background: var(--system); }
    .legend-swatch.numpad { background: var(--numpad); }
    .legend-swatch.other { background: var(--other); }
    .legend-swatch.application { background: var(--application); }
    .legend-swatch.cursor { background: var(--cursor); }

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
        .keyboard-shell { padding: 32px 34px 30px; border-radius: 32px; }
        .keyboard-legend { grid-template-columns: 1fr; gap: 12px; }
    }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Keyboard & Mouse Diagnostic Dashboard</h1>
        <p class="lead">Test keys, lock indicators, cursor controls, numeric keypad, and mouse buttons with the grouped keyboard layout shown in your reference.</p>

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
            <div id="keyboard" class="keyboard-shell" aria-label="full keyboard layout">
                <div class="keyboard-layout">
                    <div class="key other" data-code="Escape" style="grid-column:1;grid-row:1;">Esc</div>
                    <div class="key function" data-code="F1" style="grid-column:4;grid-row:1;"><span class="primary">F1</span></div>
                    <div class="key function" data-code="F2" style="grid-column:5;grid-row:1;"><span class="primary">F2</span></div>
                    <div class="key function" data-code="F3" style="grid-column:6;grid-row:1;"><span class="primary">F3</span></div>
                    <div class="key function" data-code="F4" style="grid-column:7;grid-row:1;"><span class="primary">F4</span></div>
                    <div class="key function" data-code="F5" style="grid-column:9;grid-row:1;"><span class="primary">F5</span></div>
                    <div class="key function" data-code="F6" style="grid-column:10;grid-row:1;"><span class="primary">F6</span></div>
                    <div class="key function" data-code="F7" style="grid-column:11;grid-row:1;"><span class="primary">F7</span></div>
                    <div class="key function" data-code="F8" style="grid-column:12;grid-row:1;"><span class="primary">F8</span></div>
                    <div class="key function" data-code="F9" style="grid-column:14;grid-row:1;"><span class="primary">F9</span></div>
                    <div class="key function" data-code="F10" style="grid-column:15;grid-row:1;"><span class="primary">F10</span></div>
                    <div class="key function" data-code="F11" style="grid-column:16;grid-row:1;"><span class="primary">F11</span></div>
                    <div class="key function" data-code="F12" style="grid-column:17;grid-row:1;"><span class="primary">F12</span></div>
                    <div class="key other two-line" data-code="PrintScreen" style="grid-column:19;grid-row:1;">Print<br>Scrn<br>SysRq</div>
                    <div class="key other two-line" data-code="ScrollLock" style="grid-column:20;grid-row:1;">Scroll<br>Lock</div>
                    <div class="key other two-line" data-code="Pause" style="grid-column:21;grid-row:1;">Pause<br>Break</div>
                    <div class="lock-lights" aria-label="keyboard lock indicators">
                        <span class="lock-light">Num<br>Lock</span>
                        <span class="lock-light">Caps<br>Lock</span>
                        <span class="lock-light">Scroll<br>Lock</span>
                    </div>

                    <div class="key left-label" data-code="Backquote" style="grid-column:1;grid-row:2;"><span class="corner top">~</span><span class="corner">`</span></div>
                    <div class="key left-label" data-code="Digit1" style="grid-column:2;grid-row:2;"><span class="corner top">!</span><span class="corner">1</span></div>
                    <div class="key left-label" data-code="Digit2" style="grid-column:3;grid-row:2;"><span class="corner top">@</span><span class="corner">2</span></div>
                    <div class="key left-label" data-code="Digit3" style="grid-column:4;grid-row:2;"><span class="corner top">#</span><span class="corner">3</span></div>
                    <div class="key left-label" data-code="Digit4" style="grid-column:5;grid-row:2;"><span class="corner top">$</span><span class="corner">4</span></div>
                    <div class="key left-label" data-code="Digit5" style="grid-column:6;grid-row:2;"><span class="corner top">%</span><span class="corner">5</span></div>
                    <div class="key left-label" data-code="Digit6" style="grid-column:7;grid-row:2;"><span class="corner top">^</span><span class="corner">6</span></div>
                    <div class="key left-label" data-code="Digit7" style="grid-column:8;grid-row:2;"><span class="corner top">&amp;</span><span class="corner">7</span></div>
                    <div class="key left-label" data-code="Digit8" style="grid-column:9;grid-row:2;"><span class="corner top">*</span><span class="corner">8</span></div>
                    <div class="key left-label" data-code="Digit9" style="grid-column:10;grid-row:2;"><span class="corner top">(</span><span class="corner">9</span></div>
                    <div class="key left-label" data-code="Digit0" style="grid-column:11;grid-row:2;"><span class="corner top">)</span><span class="corner">0</span></div>
                    <div class="key left-label" data-code="Minus" style="grid-column:12;grid-row:2;"><span class="corner top">_</span><span class="corner">-</span></div>
                    <div class="key left-label" data-code="Equal" style="grid-column:13;grid-row:2;"><span class="corner top">+</span><span class="corner">=</span></div>
                    <div class="key left-label" data-code="Backslash" style="grid-column:14;grid-row:2;"><span class="corner top">|</span><span class="corner">\</span></div>
                    <div class="key big-symbol" data-code="Backspace" style="grid-column:15 / span 2;grid-row:2;">←</div>
                    <div class="key other" data-code="Insert" style="grid-column:19;grid-row:2;">Insert</div>
                    <div class="key other" data-code="Home" style="grid-column:20;grid-row:2;">Home</div>
                    <div class="key other two-line" data-code="PageUp" style="grid-column:21;grid-row:2;">Page<br>Up</div>
                    <div class="key numpad two-line" data-code="NumLock" style="grid-column:23;grid-row:2;">Num<br>Lock</div>
                    <div class="key numpad" data-code="NumpadDivide" style="grid-column:24;grid-row:2;">/</div>
                    <div class="key numpad" data-code="NumpadMultiply" style="grid-column:25;grid-row:2;">*</div>
                    <div class="key numpad" data-code="NumpadSubtract" style="grid-column:26;grid-row:2;">-</div>

                    <div class="key left-label" data-code="Tab" style="grid-column:1 / span 2;grid-row:3;">Tab <span class="corner right">↹</span></div>
                    <div class="key" data-code="KeyQ" style="grid-column:3;grid-row:3;"><span class="primary">Q</span></div>
                    <div class="key" data-code="KeyW" style="grid-column:4;grid-row:3;"><span class="primary">W</span></div>
                    <div class="key" data-code="KeyE" style="grid-column:5;grid-row:3;"><span class="primary">E</span></div>
                    <div class="key" data-code="KeyR" style="grid-column:6;grid-row:3;"><span class="primary">R</span></div>
                    <div class="key" data-code="KeyT" style="grid-column:7;grid-row:3;"><span class="primary">T</span></div>
                    <div class="key" data-code="KeyY" style="grid-column:8;grid-row:3;"><span class="primary">Y</span></div>
                    <div class="key" data-code="KeyU" style="grid-column:9;grid-row:3;"><span class="primary">U</span></div>
                    <div class="key" data-code="KeyI" style="grid-column:10;grid-row:3;"><span class="primary">I</span></div>
                    <div class="key" data-code="KeyO" style="grid-column:11;grid-row:3;"><span class="primary">O</span></div>
                    <div class="key" data-code="KeyP" style="grid-column:12;grid-row:3;"><span class="primary">P</span></div>
                    <div class="key left-label" data-code="BracketLeft" style="grid-column:13;grid-row:3;"><span class="corner top">{</span><span class="corner">[</span></div>
                    <div class="key left-label" data-code="BracketRight" style="grid-column:14;grid-row:3;"><span class="corner top">}</span><span class="corner">]</span></div>
                    <div class="key enter-key big-symbol" data-code="Enter" style="grid-column:15 / span 2;grid-row:3 / span 2;align-items:flex-end;justify-content:flex-end;padding:10px;">↵</div>
                    <div class="key other" data-code="Delete" style="grid-column:19;grid-row:3;">Delete</div>
                    <div class="key other" data-code="End" style="grid-column:20;grid-row:3;">End</div>
                    <div class="key other two-line" data-code="PageDown" style="grid-column:21;grid-row:3;">Page<br>Down</div>
                    <div class="key numpad left-label" data-code="Numpad7" style="grid-column:23;grid-row:3;"><span class="corner top">7</span><span class="corner">Home</span></div>
                    <div class="key numpad" data-code="Numpad8" style="grid-column:24;grid-row:3;">8 ↑</div>
                    <div class="key numpad left-label" data-code="Numpad9" style="grid-column:25;grid-row:3;"><span class="corner top">9</span><span class="corner">PgUp</span></div>
                    <div class="key numpad" data-code="NumpadAdd" style="grid-column:26;grid-row:3 / span 2;">+</div>

                    <div class="key two-line" data-code="CapsLock" style="grid-column:1 / span 2;grid-row:4;">Caps<br>Lock</div>
                    <div class="key" data-code="KeyA" style="grid-column:3;grid-row:4;"><span class="primary">A</span></div>
                    <div class="key" data-code="KeyS" style="grid-column:4;grid-row:4;"><span class="primary">S</span></div>
                    <div class="key" data-code="KeyD" style="grid-column:5;grid-row:4;"><span class="primary">D</span></div>
                    <div class="key" data-code="KeyF" style="grid-column:6;grid-row:4;"><span class="primary">F</span></div>
                    <div class="key" data-code="KeyG" style="grid-column:7;grid-row:4;"><span class="primary">G</span></div>
                    <div class="key" data-code="KeyH" style="grid-column:8;grid-row:4;"><span class="primary">H</span></div>
                    <div class="key" data-code="KeyJ" style="grid-column:9;grid-row:4;"><span class="primary">J</span></div>
                    <div class="key" data-code="KeyK" style="grid-column:10;grid-row:4;"><span class="primary">K</span></div>
                    <div class="key" data-code="KeyL" style="grid-column:11;grid-row:4;"><span class="primary">L</span></div>
                    <div class="key left-label" data-code="Semicolon" style="grid-column:12;grid-row:4;"><span class="corner top">:</span><span class="corner">;</span></div>
                    <div class="key left-label" data-code="Quote" style="grid-column:13;grid-row:4;"><span class="corner top">&quot;</span><span class="corner">'</span></div>
                    <div class="key numpad left-label" data-code="Numpad4" style="grid-column:23;grid-row:4;"><span class="corner top">4</span><span class="corner">←</span></div>
                    <div class="key numpad" data-code="Numpad5" style="grid-column:24;grid-row:4;">5</div>
                    <div class="key numpad left-label" data-code="Numpad6" style="grid-column:25;grid-row:4;"><span class="corner top">6</span><span class="corner right">→</span></div>

                    <div class="key right-label" data-code="ShiftLeft" style="grid-column:1 / span 3;grid-row:5;">Shift</div>
                    <div class="key" data-code="KeyZ" style="grid-column:4;grid-row:5;"><span class="primary">Z</span></div>
                    <div class="key" data-code="KeyX" style="grid-column:5;grid-row:5;"><span class="primary">X</span></div>
                    <div class="key" data-code="KeyC" style="grid-column:6;grid-row:5;"><span class="primary">C</span></div>
                    <div class="key" data-code="KeyV" style="grid-column:7;grid-row:5;"><span class="primary">V</span></div>
                    <div class="key" data-code="KeyB" style="grid-column:8;grid-row:5;"><span class="primary">B</span></div>
                    <div class="key" data-code="KeyN" style="grid-column:9;grid-row:5;"><span class="primary">N</span></div>
                    <div class="key" data-code="KeyM" style="grid-column:10;grid-row:5;"><span class="primary">M</span></div>
                    <div class="key left-label" data-code="Comma" style="grid-column:11;grid-row:5;"><span class="corner top">&lt;</span><span class="corner">,</span></div>
                    <div class="key left-label" data-code="Period" style="grid-column:12;grid-row:5;"><span class="corner top">&gt;</span><span class="corner">.</span></div>
                    <div class="key left-label" data-code="Slash" style="grid-column:13;grid-row:5;"><span class="corner top">?</span><span class="corner">/</span></div>
                    <div class="key right-label" data-code="ShiftRight" style="grid-column:14 / span 3;grid-row:5;">Shift</div>
                    <div class="key cursor big-symbol" data-code="ArrowUp" style="grid-column:20;grid-row:5;">↑</div>
                    <div class="key numpad left-label" data-code="Numpad1" style="grid-column:23;grid-row:5;"><span class="corner top">1</span><span class="corner">End</span></div>
                    <div class="key numpad" data-code="Numpad2" style="grid-column:24;grid-row:5;">2 ↓</div>
                    <div class="key numpad left-label" data-code="Numpad3" style="grid-column:25;grid-row:5;"><span class="corner top">3</span><span class="corner">PgDn</span></div>
                    <div class="key enter-key" data-code="NumpadEnter" style="grid-column:26;grid-row:5 / span 2;">Enter</div>

                    <div class="key" data-code="ControlLeft" style="grid-column:1;grid-row:6;">Ctrl</div>
                    <div class="key system blank" data-code="MetaLeft" style="grid-column:2;grid-row:6;">Win</div>
                    <div class="key" data-code="AltLeft" style="grid-column:3;grid-row:6;">Alt</div>
                    <div class="key" data-code="Space" style="grid-column:4 / span 9;grid-row:6;">Space</div>
                    <div class="key" data-code="AltRight" style="grid-column:13;grid-row:6;">Alt Gr</div>
                    <div class="key system blank" data-code="MetaRight" style="grid-column:14;grid-row:6;">Win</div>
                    <div class="key application blank" data-code="ContextMenu" style="grid-column:15;grid-row:6;">Menu</div>
                    <div class="key" data-code="ControlRight" style="grid-column:16;grid-row:6;">Ctrl</div>
                    <div class="key cursor big-symbol" data-code="ArrowLeft" style="grid-column:19;grid-row:6;">←</div>
                    <div class="key cursor big-symbol" data-code="ArrowDown" style="grid-column:20;grid-row:6;">↓</div>
                    <div class="key cursor big-symbol" data-code="ArrowRight" style="grid-column:21;grid-row:6;">→</div>
                    <div class="key numpad left-label" data-code="Numpad0" style="grid-column:23 / span 2;grid-row:6;"><span class="corner top">0</span><span class="corner">Ins</span></div>
                    <div class="key numpad left-label" data-code="NumpadDecimal" style="grid-column:25;grid-row:6;"><span class="corner top">.</span><span class="corner">Del</span></div>
                </div>
            </div>
        </div>

        <div class="keyboard-legend" aria-label="keyboard color legend">
            <div class="legend-item"><span class="legend-swatch"></span>Typewriter keys</div>
            <div class="legend-item"><span class="legend-swatch function"></span>Function keys</div>
            <div class="legend-item"><span class="legend-swatch enter-key"></span>Enter keys</div>
            <div class="legend-item"><span class="legend-swatch system"></span>System keys</div>
            <div class="legend-item"><span class="legend-swatch numpad"></span>Numeric keypad</div>
            <div class="legend-item"><span class="legend-swatch other"></span>Other</div>
            <div class="legend-item"><span class="legend-swatch application"></span>Application key</div>
            <div class="legend-item"><span class="legend-swatch cursor"></span>Cursor control keys</div>
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
