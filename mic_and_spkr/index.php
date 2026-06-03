<?php ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Microphone & Speaker Diagnostics</title>
<style>
:root {
  color-scheme: dark;
  --bg: #050816;
  --panel: rgba(15, 23, 42, .76);
  --panel-strong: rgba(15, 23, 42, .94);
  --line: rgba(148, 163, 184, .22);
  --text: #f8fafc;
  --muted: #a8b6c8;
  --cyan: #22d3ee;
  --blue: #60a5fa;
  --violet: #a78bfa;
  --pink: #f472b6;
  --green: #34d399;
  --amber: #fbbf24;
  --red: #fb7185;
}

* { box-sizing: border-box; }

body {
  min-height: 100vh;
  margin: 0;
  overflow-x: hidden;
  color: var(--text);
  font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  background:
    radial-gradient(circle at 18% 12%, rgba(34, 211, 238, .28), transparent 24rem),
    radial-gradient(circle at 85% 0%, rgba(167, 139, 250, .24), transparent 24rem),
    radial-gradient(circle at 50% 100%, rgba(244, 114, 182, .16), transparent 28rem),
    var(--bg);
}

body::before {
  position: fixed;
  inset: 0;
  z-index: -2;
  content: "";
  background-image:
    linear-gradient(rgba(148, 163, 184, .08) 1px, transparent 1px),
    linear-gradient(90deg, rgba(148, 163, 184, .08) 1px, transparent 1px);
  background-size: 44px 44px;
  mask-image: linear-gradient(to bottom, rgba(0,0,0,.85), transparent 88%);
}

.wrap {
  width: min(1180px, calc(100% - 32px));
  margin: 0 auto;
  padding: 30px 0 44px;
}

.hero,
.panel {
  border: 1px solid var(--line);
  background: linear-gradient(145deg, rgba(15, 23, 42, .9), rgba(15, 23, 42, .58));
  box-shadow: 0 24px 80px rgba(0, 0, 0, .38);
  backdrop-filter: blur(18px);
}

.hero {
  position: relative;
  overflow: hidden;
  display: grid;
  grid-template-columns: minmax(0, 1.25fr) minmax(280px, .75fr);
  gap: 22px;
  align-items: center;
  margin-bottom: 18px;
  padding: clamp(24px, 4vw, 42px);
  border-radius: 30px;
}

.hero::after {
  position: absolute;
  right: -7rem;
  bottom: -9rem;
  width: 24rem;
  height: 24rem;
  content: "";
  border-radius: 999px;
  background: conic-gradient(from 80deg, rgba(34,211,238,.34), rgba(244,114,182,.24), rgba(96,165,250,.28), rgba(34,211,238,.34));
  filter: blur(3.5rem);
  animation: spinGlow 18s linear infinite;
}

.hero-copy,
.audio-orb { position: relative; z-index: 1; }

.eyebrow {
  display: inline-flex;
  gap: 8px;
  align-items: center;
  margin: 0 0 14px;
  padding: 8px 12px;
  border: 1px solid rgba(34, 211, 238, .32);
  border-radius: 999px;
  background: rgba(8, 145, 178, .15);
  color: #a5f3fc;
  font-size: .82rem;
  font-weight: 850;
  letter-spacing: .06em;
  text-transform: uppercase;
}

h1 {
  max-width: 740px;
  margin: 0;
  font-size: clamp(2.2rem, 6vw, 4.9rem);
  line-height: .94;
  letter-spacing: -.065em;
}

.gradient-text {
  background: linear-gradient(90deg, var(--cyan), var(--violet), var(--pink), var(--amber));
  background-size: 220% auto;
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
  animation: shimmer 5s linear infinite;
}

.lead {
  max-width: 660px;
  margin: 18px 0 0;
  color: var(--muted);
  font-size: clamp(1rem, 2vw, 1.18rem);
  line-height: 1.7;
}

.hero-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 22px;
}

.pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 9px 12px;
  border: 1px solid rgba(148, 163, 184, .24);
  border-radius: 999px;
  background: rgba(2, 6, 23, .32);
  color: #dbeafe;
  font-weight: 780;
}

.audio-orb {
  display: grid;
  min-height: 285px;
  place-items: center;
}

.orb-core {
  position: relative;
  display: grid;
  width: min(270px, 80vw);
  aspect-ratio: 1;
  place-items: center;
  border: 1px solid rgba(255,255,255,.16);
  border-radius: 999px;
  background:
    radial-gradient(circle at 50% 50%, rgba(34,211,238,.28), transparent 28%),
    radial-gradient(circle, rgba(15, 23, 42, .94), rgba(15, 23, 42, .42));
  box-shadow: 0 0 70px rgba(34, 211, 238, .22), inset 0 0 55px rgba(96, 165, 250, .16);
}

.orb-core::before,
.orb-core::after {
  position: absolute;
  inset: 18px;
  content: "";
  border: 1px dashed rgba(226, 232, 240, .28);
  border-radius: inherit;
  animation: rotate 18s linear infinite;
}

.orb-core::after {
  inset: 44px;
  border-style: solid;
  border-color: rgba(34, 211, 238, .24);
  animation-direction: reverse;
  animation-duration: 12s;
}

.orb-icon {
  position: relative;
  z-index: 1;
  font-size: 4rem;
  filter: drop-shadow(0 0 22px rgba(34, 211, 238, .55));
  animation: floaty 3.4s ease-in-out infinite;
}

.sound-bars {
  position: absolute;
  bottom: 42px;
  display: flex;
  gap: 8px;
  align-items: end;
}

.sound-bars span {
  width: 9px;
  height: 26px;
  border-radius: 999px;
  background: linear-gradient(var(--cyan), var(--blue));
  animation: equalize 900ms ease-in-out infinite alternate;
}

.sound-bars span:nth-child(2) { height: 46px; animation-delay: 120ms; }
.sound-bars span:nth-child(3) { height: 34px; animation-delay: 240ms; }
.sound-bars span:nth-child(4) { height: 52px; animation-delay: 360ms; }
.sound-bars span:nth-child(5) { height: 30px; animation-delay: 480ms; }

.grid {
  display: grid;
  grid-template-columns: minmax(0, 1.1fr) minmax(280px, .9fr);
  gap: 18px;
}

.panel {
  position: relative;
  overflow: hidden;
  padding: 20px;
  border-radius: 24px;
}

.panel::before {
  position: absolute;
  inset: 0;
  content: "";
  background: radial-gradient(circle at var(--mx, 50%) var(--my, 0%), rgba(34, 211, 238, .15), transparent 24rem);
  pointer-events: none;
}

.panel > * { position: relative; }
.panel.wide { grid-row: span 2; }

.panel-top {
  display: flex;
  gap: 14px;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 14px;
}

.panel h2 {
  margin: 0;
  font-size: clamp(1.25rem, 3vw, 1.7rem);
  letter-spacing: -.035em;
}

.panel-icon {
  display: grid;
  flex: 0 0 auto;
  width: 54px;
  aspect-ratio: 1;
  place-items: center;
  border: 1px solid rgba(255,255,255,.16);
  border-radius: 18px;
  background: rgba(2, 6, 23, .42);
  font-size: 1.8rem;
}

.stat {
  margin: 0 0 14px;
  color: var(--muted);
  font-size: .96rem;
  line-height: 1.55;
}

.status-line {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  align-items: center;
}

.status-chip,
.badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  min-height: 28px;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: .8rem;
  font-weight: 850;
  letter-spacing: .04em;
  text-transform: uppercase;
}

.status-chip {
  border: 1px solid rgba(148, 163, 184, .28);
  background: rgba(15, 23, 42, .68);
  color: #dbeafe;
}

.status-chip::before {
  width: 8px;
  aspect-ratio: 1;
  content: "";
  border-radius: 50%;
  background: var(--amber);
  box-shadow: 0 0 18px var(--amber);
}

.status-chip.running::before { background: var(--green); box-shadow: 0 0 18px var(--green); }
.status-chip.stopped::before { background: #94a3b8; box-shadow: none; }
.status-chip.denied::before { background: var(--red); box-shadow: 0 0 18px var(--red); }

.badge.low { background: rgba(6, 95, 70, .86); color: #dcfce7; }
.badge.medium { background: rgba(146, 64, 14, .9); color: #ffedd5; }
.badge.high { background: rgba(153, 27, 27, .92); color: #fee2e2; }

.controls {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin: 14px 0;
}

button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 42px;
  border: 0;
  border-radius: 13px;
  padding: 10px 14px;
  color: #020617;
  font-weight: 900;
  cursor: pointer;
  background: linear-gradient(135deg, var(--cyan), var(--blue));
  box-shadow: 0 14px 30px rgba(34, 211, 238, .18);
  transition: transform 180ms ease, filter 180ms ease;
}

button:hover { transform: translateY(-2px); filter: brightness(1.08); }
button.secondary { color: #f8fafc; background: rgba(51, 65, 85, .92); box-shadow: none; }
button.pink { background: linear-gradient(135deg, var(--pink), var(--violet)); }

canvas {
  display: block;
  width: 100%;
  height: 210px;
  border: 1px solid rgba(148, 163, 184, .24);
  border-radius: 18px;
  background: rgba(2, 6, 23, .88);
  box-shadow: inset 0 1px 0 rgba(255,255,255,.05);
}

.noise-meter {
  overflow: hidden;
  height: 12px;
  margin: 12px 0 4px;
  border: 1px solid rgba(148, 163, 184, .22);
  border-radius: 999px;
  background: rgba(2, 6, 23, .72);
}

.noise-meter span {
  display: block;
  width: var(--noise-width, 8%);
  height: 100%;
  border-radius: inherit;
  background: linear-gradient(90deg, var(--green), var(--amber), var(--red));
  transition: width 120ms ease;
}

.channel-stage {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 14px;
  margin-top: 16px;
}

.speaker {
  position: relative;
  min-height: 130px;
  padding: 18px;
  border: 1px solid rgba(148, 163, 184, .2);
  border-radius: 18px;
  background: rgba(2, 6, 23, .36);
}

.speaker::before,
.speaker::after {
  position: absolute;
  top: 50%;
  content: "";
  border: 2px solid rgba(34, 211, 238, .3);
  border-left: 0;
  border-radius: 0 999px 999px 0;
  transform: translateY(-50%);
}

.speaker.left::before,
.speaker.left::after { right: 18px; }
.speaker.right::before,
.speaker.right::after { left: 18px; right: auto; border-right: 0; border-left: 2px solid rgba(244, 114, 182, .35); border-radius: 999px 0 0 999px; }
.speaker::before { width: 36px; height: 54px; }
.speaker::after { width: 58px; height: 82px; opacity: .56; }
.speaker.active::before,
.speaker.active::after { animation: wavePulse 520ms ease-out 2; }

.speaker strong { display: block; margin-bottom: 8px; font-size: 1.05rem; }
.speaker span { color: var(--muted); font-size: .9rem; }

.echo-card {
  min-height: 170px;
}

.echo-loop {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  min-height: 74px;
  margin-top: 10px;
  color: #dbeafe;
  font-weight: 850;
}

.echo-node {
  display: grid;
  width: 56px;
  aspect-ratio: 1;
  place-items: center;
  border-radius: 18px;
  background: rgba(34, 211, 238, .14);
  font-size: 1.8rem;
}

.echo-arrow { color: var(--cyan); font-size: 1.6rem; animation: nudge 1.4s ease-in-out infinite; }

@keyframes shimmer { to { background-position: 220% center; } }
@keyframes spinGlow { to { rotate: 360deg; } }
@keyframes rotate { to { rotate: 360deg; } }
@keyframes floaty { 50% { transform: translateY(-8px); } }
@keyframes equalize { to { transform: scaleY(.45); opacity: .62; } }
@keyframes wavePulse { 50% { transform: translateY(-50%) scale(1.14); opacity: .95; } }
@keyframes nudge { 50% { transform: translateX(7px); } }

@media (max-width: 860px) {
  .hero,
  .grid { grid-template-columns: 1fr; }
  .audio-orb { min-height: 220px; }
  .channel-stage { grid-template-columns: 1fr; }
}

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    scroll-behavior: auto !important;
    animation-duration: .01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: .01ms !important;
  }
}
</style>
</head>
<body>
<div class="wrap">
  <header class="hero">
    <div class="hero-copy">
      <p class="eyebrow">🎧 Audio diagnostics studio</p>
      <h1>Microphone & <span class="gradient-text">Speaker Lab</span></h1>
      <p class="lead">Visualize live mic input, measure room noise, test echo loopback, and verify left/right speaker channels from one polished control deck.</p>
      <div class="hero-pills" aria-label="audio test features">
        <span class="pill">🎙️ Live waveform</span>
        <span class="pill">📈 Noise meter</span>
        <span class="pill">🔊 Stereo checks</span>
      </div>
    </div>
    <div class="audio-orb" aria-hidden="true">
      <div class="orb-core">
        <span class="orb-icon">🎙️</span>
        <div class="sound-bars"><span></span><span></span><span></span><span></span><span></span></div>
      </div>
    </div>
  </header>

  <main class="grid">
    <section class="panel wide">
      <div class="panel-top">
        <div>
          <h2>Voice Input Waveform + Noise Level Detector</h2>
          <p class="stat status-line">Mic status: <span id="micStatus" class="status-chip">Idle</span> Noise: <strong id="noiseValue">0</strong> dB <span id="noiseBadge" class="badge low">LOW</span></p>
        </div>
        <div class="panel-icon" aria-hidden="true">📡</div>
      </div>
      <div class="controls">
        <button id="startMic">▶ Start Mic</button>
        <button id="stopMic" class="secondary">■ Stop Mic</button>
      </div>
      <canvas id="wave" width="960" height="210"></canvas>
      <div class="noise-meter" aria-hidden="true"><span id="noiseMeter"></span></div>
    </section>

    <section class="panel echo-card">
      <div class="panel-top">
        <div>
          <h2>Echo Test</h2>
          <p class="stat">Speak into the microphone and listen for loopback echo output.</p>
        </div>
        <div class="panel-icon" aria-hidden="true">🔁</div>
      </div>
      <div class="controls">
        <button id="startEcho" class="pink">Start Echo Test</button>
        <button id="stopEcho" class="secondary">Stop Echo Test</button>
      </div>
      <div class="echo-loop" aria-hidden="true">
        <span class="echo-node">🎙️</span><span class="echo-arrow">→</span><span class="echo-node">🎧</span>
      </div>
    </section>

    <section class="panel">
      <div class="panel-top">
        <div>
          <h2>Left / Right Speaker Check</h2>
          <p class="stat">Play tones through each side to confirm your stereo output routing.</p>
        </div>
        <div class="panel-icon" aria-hidden="true">🔊</div>
      </div>
      <div class="controls">
        <button id="leftTone">Left Channel</button>
        <button id="rightTone">Right Channel</button>
        <button id="bothTone" class="secondary">Play Both</button>
      </div>
      <div class="channel-stage" aria-hidden="true">
        <div id="leftSpeaker" class="speaker left"><strong>Left</strong><span>Channel A</span></div>
        <div id="rightSpeaker" class="speaker right"><strong>Right</strong><span>Channel B</span></div>
      </div>
    </section>
  </main>
</div>
<script>
(() => {
let audioCtx, analyser, micStream, micSource, rafId, loopbackGain;
const wave = document.getElementById('wave');
const wctx = wave.getContext('2d');
const micStatus = document.getElementById('micStatus');
const noiseValue = document.getElementById('noiseValue');
const noiseBadge = document.getElementById('noiseBadge');
const noiseMeter = document.getElementById('noiseMeter');
const leftSpeaker = document.getElementById('leftSpeaker');
const rightSpeaker = document.getElementById('rightSpeaker');

document.querySelectorAll('.panel').forEach(panel => {
  panel.addEventListener('pointermove', event => {
    const bounds = panel.getBoundingClientRect();
    panel.style.setProperty('--mx', `${event.clientX - bounds.left}px`);
    panel.style.setProperty('--my', `${event.clientY - bounds.top}px`);
  });
});

function ensureCtx(){ if(!audioCtx) audioCtx = new (window.AudioContext||window.webkitAudioContext)(); return audioCtx; }
function setMicStatus(text, state='') {
  micStatus.textContent = text;
  micStatus.className = `status-chip ${state}`.trim();
}

async function startMic(draw=true){
  const ctx = ensureCtx();
  if (!micStream) micStream = await navigator.mediaDevices.getUserMedia({audio:true});
  if (!micSource) {
    micSource = ctx.createMediaStreamSource(micStream);
    analyser = ctx.createAnalyser();
    analyser.fftSize = 2048;
    micSource.connect(analyser);
  }
  setMicStatus('Running', 'running');
  if (draw) drawWave();
}

function stopMic(){
  if (rafId) cancelAnimationFrame(rafId);
  rafId = null;
  setMicStatus('Stopped', 'stopped');
}

function drawGrid(){
  wctx.strokeStyle='rgba(148,163,184,.13)';
  wctx.lineWidth=1;
  for(let x=0; x<wave.width; x+=80){ wctx.beginPath(); wctx.moveTo(x,0); wctx.lineTo(x,wave.height); wctx.stroke(); }
  for(let y=0; y<wave.height; y+=42){ wctx.beginPath(); wctx.moveTo(0,y); wctx.lineTo(wave.width,y); wctx.stroke(); }
}

function drawWave(){
  const buf = new Uint8Array(analyser.fftSize);
  const loop = () => {
    analyser.getByteTimeDomainData(buf);
    const gradient = wctx.createLinearGradient(0,0,wave.width,0);
    gradient.addColorStop(0,'#22d3ee'); gradient.addColorStop(.52,'#a78bfa'); gradient.addColorStop(1,'#f472b6');
    wctx.fillStyle='#020617'; wctx.fillRect(0,0,wave.width,wave.height);
    drawGrid();
    wctx.strokeStyle=gradient; wctx.lineWidth=3; wctx.beginPath();
    let sumSq = 0;
    for(let i=0;i<buf.length;i++){
      const v = (buf[i]-128)/128;
      sumSq += v*v;
      const x = i / (buf.length-1) * wave.width;
      const y = (1-v)*0.5 * wave.height;
      i===0 ? wctx.moveTo(x,y) : wctx.lineTo(x,y);
    }
    wctx.stroke();
    const rms = Math.sqrt(sumSq / buf.length);
    const db = rms > 0 ? 20*Math.log10(rms) : -100;
    updateNoise(db);
    rafId = requestAnimationFrame(loop);
  };
  loop();
}

function updateNoise(db){
  const rounded = Math.max(-100, Math.min(0, db)).toFixed(1);
  const pct = Math.max(4, Math.min(100, (Number(rounded) + 100)));
  noiseValue.textContent = rounded;
  noiseMeter.style.setProperty('--noise-width', `${pct}%`);
  noiseBadge.className = 'badge';
  if (db < -45) { noiseBadge.textContent = 'LOW'; noiseBadge.classList.add('low'); }
  else if (db < -25) { noiseBadge.textContent = 'MEDIUM'; noiseBadge.classList.add('medium'); }
  else { noiseBadge.textContent = 'HIGH'; noiseBadge.classList.add('high'); }
}

async function startEcho(){
  await startMic(false);
  const ctx = ensureCtx();
  if (!loopbackGain) {
    loopbackGain = ctx.createGain();
    loopbackGain.gain.value = 0.65;
    micSource.connect(loopbackGain);
    loopbackGain.connect(ctx.destination);
  }
}
function stopEcho(){ if (loopbackGain) loopbackGain.disconnect(); loopbackGain=null; }

function flashSpeaker(channel){
  if (channel === 'left' || channel === 'both') leftSpeaker.classList.add('active');
  if (channel === 'right' || channel === 'both') rightSpeaker.classList.add('active');
  setTimeout(() => { leftSpeaker.classList.remove('active'); rightSpeaker.classList.remove('active'); }, 1100);
}

function playChannelTone(channel='both'){
  const ctx = ensureCtx();
  const osc = ctx.createOscillator();
  const gain = ctx.createGain();
  const splitter = ctx.createChannelSplitter(2);
  const merger = ctx.createChannelMerger(2);
  osc.frequency.value = 440; gain.gain.value = 0.15; osc.type='sine';
  osc.connect(gain); gain.connect(splitter);
  const silent = ctx.createGain(); silent.gain.value = 0;
  if (channel==='left') { splitter.connect(merger,0,0); silent.connect(merger,0,1); }
  else if (channel==='right') { silent.connect(merger,0,0); splitter.connect(merger,0,1); }
  else { splitter.connect(merger,0,0); splitter.connect(merger,0,1); }
  merger.connect(ctx.destination);
  flashSpeaker(channel);
  osc.start(); osc.stop(ctx.currentTime + 1.1);
}

document.getElementById('startMic').addEventListener('click', () => startMic(true).catch(() => setMicStatus('Mic denied', 'denied')));
document.getElementById('stopMic').addEventListener('click', stopMic);
document.getElementById('startEcho').addEventListener('click', () => startEcho().catch(() => setMicStatus('Mic denied', 'denied')));
document.getElementById('stopEcho').addEventListener('click', stopEcho);
document.getElementById('leftTone').addEventListener('click', () => playChannelTone('left'));
document.getElementById('rightTone').addEventListener('click', () => playChannelTone('right'));
document.getElementById('bothTone').addEventListener('click', () => playChannelTone('both'));
})();
</script>
</body>
</html>
