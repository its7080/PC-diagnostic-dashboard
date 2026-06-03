<?php
$cards = [
    [
        'title' => 'Microphone & Speaker Diagnostics',
        'description' => 'Run voice waveform capture, echo testing, speaker channel checks, and ambient noise-level detection.',
        'href' => 'mic_and_spkr/index.php',
        'cta' => 'Launch Audio Lab',
        'icon' => '🎙️',
        'accent' => '#22d3ee',
        'metrics' => ['Waveforms', 'Echo loop', 'Stereo tone']
    ],
    [
        'title' => 'Keyboard & Mouse Diagnostics',
        'description' => 'Validate key presses, media keys, mouse clicks, and generate a missing-key report.',
        'href' => 'kb_and_mouse/index.php',
        'cta' => 'Start Input Quest',
        'icon' => '⌨️',
        'accent' => '#f472b6',
        'metrics' => ['Key map', 'Click tracker', 'Reports']
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Device Diagnostic Dashboard</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #050816;
            --panel: rgba(15, 23, 42, 0.78);
            --panel-strong: rgba(15, 23, 42, 0.95);
            --line: rgba(148, 163, 184, 0.22);
            --text: #f8fafc;
            --muted: #b6c3d4;
            --cyan: #22d3ee;
            --pink: #f472b6;
            --green: #34d399;
            --amber: #fbbf24;
            --shadow: 0 24px 70px rgba(0, 0, 0, 0.42);
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 15% 15%, rgba(34, 211, 238, 0.28), transparent 26rem),
                radial-gradient(circle at 85% 12%, rgba(244, 114, 182, 0.22), transparent 22rem),
                radial-gradient(circle at 50% 100%, rgba(52, 211, 153, 0.16), transparent 26rem),
                var(--bg);
            color: var(--text);
        }

        body::before {
            position: fixed;
            inset: 0;
            z-index: -3;
            content: "";
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px);
            background-size: 46px 46px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent 88%);
        }

        .aurora {
            position: fixed;
            inset: auto auto -18rem 50%;
            z-index: -2;
            width: min(56rem, 96vw);
            height: min(56rem, 96vw);
            border-radius: 999px;
            background: conic-gradient(from 120deg, rgba(34, 211, 238, 0.34), rgba(244, 114, 182, 0.22), rgba(52, 211, 153, 0.24), rgba(34, 211, 238, 0.34));
            filter: blur(4rem);
            opacity: 0.68;
            transform: translateX(-50%);
            animation: slowSpin 22s linear infinite;
        }

        .wrap {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 34px 0 44px;
        }

        .hero {
            position: relative;
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 22px;
            align-items: stretch;
            margin-bottom: 22px;
        }

        .hero-copy, .mission-card, .card {
            border: 1px solid var(--line);
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.88), rgba(15, 23, 42, 0.62));
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
        }

        .hero-copy {
            position: relative;
            overflow: hidden;
            min-height: 320px;
            padding: clamp(24px, 4vw, 44px);
            border-radius: 30px;
        }

        .hero-copy::after {
            position: absolute;
            right: -7rem;
            bottom: -8rem;
            width: 20rem;
            height: 20rem;
            content: "";
            border-radius: 999px;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.28), transparent 65%);
            animation: pulseGlow 3.8s ease-in-out infinite;
        }

        .eyebrow {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            margin: 0 0 14px;
            padding: 8px 12px;
            border: 1px solid rgba(34, 211, 238, 0.32);
            border-radius: 999px;
            background: rgba(8, 145, 178, 0.14);
            color: #a5f3fc;
            font-size: 0.88rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        h1 {
            max-width: 780px;
            margin: 0;
            font-size: clamp(2.45rem, 7vw, 5.4rem);
            line-height: 0.92;
            letter-spacing: -0.075em;
        }

        .gradient-text {
            display: inline-block;
            background: linear-gradient(90deg, var(--cyan), #a78bfa, var(--pink), var(--amber));
            background-size: 220% auto;
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            animation: shimmer 5.4s linear infinite;
        }

        .lead {
            max-width: 670px;
            margin: 18px 0 0;
            color: var(--muted);
            font-size: clamp(1rem, 2vw, 1.22rem);
            line-height: 1.75;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 13px;
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.58);
            color: #dbeafe;
            font-weight: 750;
        }

        .mission-card {
            position: relative;
            overflow: hidden;
            display: grid;
            align-content: space-between;
            min-height: 320px;
            padding: 24px;
            border-radius: 30px;
        }

        .orbit {
            position: relative;
            width: min(280px, 100%);
            aspect-ratio: 1;
            place-self: center;
            border: 1px dashed rgba(226, 232, 240, 0.26);
            border-radius: 50%;
            animation: orbitSpin 16s linear infinite;
        }

        .orbit span {
            position: absolute;
            display: grid;
            width: 62px;
            aspect-ratio: 1;
            place-items: center;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 22px;
            background: rgba(2, 6, 23, 0.64);
            box-shadow: 0 0 28px rgba(34, 211, 238, 0.16);
            font-size: 1.85rem;
            animation: counterSpin 16s linear infinite;
        }

        .orbit span:nth-child(1) { top: -18px; left: 50%; transform: translateX(-50%); }
        .orbit span:nth-child(2) { right: -18px; top: 50%; transform: translateY(-50%); }
        .orbit span:nth-child(3) { bottom: -18px; left: 50%; transform: translateX(-50%); }
        .orbit span:nth-child(4) { left: -18px; top: 50%; transform: translateY(-50%); }

        .mission-card h2 { margin: 0; font-size: 1.2rem; }
        .mission-card p { margin: 8px 0 0; color: var(--muted); line-height: 1.6; }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 18px;
        }

        .card {
            --accent: var(--cyan);
            position: relative;
            overflow: hidden;
            min-height: 330px;
            padding: 22px;
            border-radius: 28px;
            transition: transform 260ms ease, border-color 260ms ease, box-shadow 260ms ease;
        }

        .card::before {
            position: absolute;
            inset: 0;
            content: "";
            background: radial-gradient(circle at var(--mx, 50%) var(--my, 0%), color-mix(in srgb, var(--accent) 34%, transparent), transparent 28rem);
            opacity: 0.7;
            transition: opacity 260ms ease;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.01);
            border-color: color-mix(in srgb, var(--accent) 64%, white 6%);
            box-shadow: 0 30px 90px color-mix(in srgb, var(--accent) 24%, transparent);
        }

        .card > * { position: relative; }

        .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .icon {
            display: grid;
            width: 78px;
            aspect-ratio: 1;
            place-items: center;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 25px;
            background: linear-gradient(145deg, color-mix(in srgb, var(--accent) 20%, transparent), rgba(255,255,255,0.05));
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.18), 0 16px 40px rgba(0,0,0,0.24);
            font-size: 2.3rem;
            animation: floaty 3.4s ease-in-out infinite;
        }

        .status-dot {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #bbf7d0;
            font-size: 0.84rem;
            font-weight: 850;
            text-transform: uppercase;
        }

        .status-dot::before {
            width: 10px;
            aspect-ratio: 1;
            content: "";
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 0 6px rgba(52, 211, 153, 0.16), 0 0 24px var(--green);
            animation: ping 1.8s ease-in-out infinite;
        }

        .card h2 {
            margin: 22px 0 12px;
            font-size: clamp(1.45rem, 3vw, 2rem);
            line-height: 1.08;
            letter-spacing: -0.04em;
        }

        .card p {
            margin: 0;
            min-height: 84px;
            color: var(--muted);
            line-height: 1.65;
        }

        .metric-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 20px 0;
        }

        .metric {
            padding: 8px 10px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 999px;
            background: rgba(2, 6, 23, 0.38);
            color: #e0f2fe;
            font-size: 0.88rem;
            font-weight: 750;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 50px;
            padding: 12px 16px;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--accent), color-mix(in srgb, var(--accent) 38%, #8b5cf6));
            color: #020617;
            text-decoration: none;
            font-weight: 950;
            box-shadow: 0 18px 38px color-mix(in srgb, var(--accent) 24%, transparent);
            transition: transform 200ms ease, filter 200ms ease;
        }

        .btn:hover { transform: translateY(-2px); filter: brightness(1.08); }
        .btn::after { content: "→"; font-size: 1.2rem; }

        .sparkle {
            position: fixed;
            z-index: -1;
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--cyan);
            box-shadow: 0 0 22px var(--cyan);
            opacity: 0.75;
            animation: drift var(--duration) linear infinite;
        }

        @keyframes shimmer { to { background-position: 220% center; } }
        @keyframes pulseGlow { 50% { transform: scale(1.13); opacity: 0.72; } }
        @keyframes slowSpin { to { transform: translateX(-50%) rotate(360deg); } }
        @keyframes orbitSpin { to { rotate: 360deg; } }
        @keyframes counterSpin { to { rotate: -360deg; } }
        @keyframes floaty { 50% { transform: translateY(-8px) rotate(3deg); } }
        @keyframes ping { 50% { box-shadow: 0 0 0 9px rgba(52, 211, 153, 0.05), 0 0 30px var(--green); } }
        @keyframes drift {
            from { transform: translate3d(0, 105vh, 0) scale(0.7); }
            to { transform: translate3d(var(--x-drift), -12vh, 0) scale(1.2); }
        }

        @media (max-width: 800px) {
            .hero { grid-template-columns: 1fr; }
            .mission-card { min-height: 260px; }
            .orbit { width: min(220px, 84%); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .sparkle { display: none; }
        }
    </style>
</head>
<body>
<div class="aurora" aria-hidden="true"></div>
<div class="wrap">
    <header class="hero">
        <section class="hero-copy">
            <p class="eyebrow">⚡ Hardware health arcade</p>
            <h1>Device Diagnostic <span class="gradient-text">Dashboard</span></h1>
            <p class="lead">Pick a module, watch the neon meters light up, and turn boring hardware checks into a quick interactive mission.</p>
            <div class="hero-actions" aria-label="dashboard highlights">
                <span class="pill">🧪 Browser-based tests</span>
                <span class="pill">🚀 Fast launch cards</span>
                <span class="pill">✨ Animated interface</span>
            </div>
        </section>
        <aside class="mission-card" aria-label="diagnostic mission status">
            <div class="orbit" aria-hidden="true">
                <span>🎧</span>
                <span>🖱️</span>
                <span>🔊</span>
                <span>⌨️</span>
            </div>
            <div>
                <h2>Ready for launch</h2>
                <p>Audio, keyboard, and mouse checks are standing by with playful visual feedback.</p>
            </div>
        </aside>
    </header>

    <main class="grid" aria-label="diagnostic modules">
        <?php foreach ($cards as $card): ?>
            <section class="card" style="--accent: <?= htmlspecialchars($card['accent']) ?>">
                <div class="card-top">
                    <div class="icon" aria-hidden="true"><?= htmlspecialchars($card['icon']) ?></div>
                    <span class="status-dot">Online</span>
                </div>
                <h2><?= htmlspecialchars($card['title']) ?></h2>
                <p><?= htmlspecialchars($card['description']) ?></p>
                <div class="metric-row" aria-label="module features">
                    <?php foreach ($card['metrics'] as $metric): ?>
                        <span class="metric"><?= htmlspecialchars($metric) ?></span>
                    <?php endforeach; ?>
                </div>
                <a class="btn" href="<?= htmlspecialchars($card['href']) ?>"><?= htmlspecialchars($card['cta']) ?></a>
            </section>
        <?php endforeach; ?>
    </main>
</div>
<script>
(() => {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('pointermove', event => {
            const bounds = card.getBoundingClientRect();
            card.style.setProperty('--mx', `${event.clientX - bounds.left}px`);
            card.style.setProperty('--my', `${event.clientY - bounds.top}px`);
        });
    });

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const colors = ['#22d3ee', '#f472b6', '#34d399', '#fbbf24', '#a78bfa'];
    for (let i = 0; i < 28; i += 1) {
        const sparkle = document.createElement('span');
        sparkle.className = 'sparkle';
        sparkle.style.left = `${Math.random() * 100}%`;
        sparkle.style.setProperty('--duration', `${9 + Math.random() * 10}s`);
        sparkle.style.setProperty('--x-drift', `${-80 + Math.random() * 160}px`);
        sparkle.style.animationDelay = `${Math.random() * -14}s`;
        sparkle.style.background = colors[i % colors.length];
        sparkle.style.boxShadow = `0 0 22px ${colors[i % colors.length]}`;
        document.body.appendChild(sparkle);
    }
})();
</script>
</body>
</html>
