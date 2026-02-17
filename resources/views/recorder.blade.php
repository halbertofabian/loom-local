<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Loom Clone Laravel</title>
    <style>
        :root {
            --bg-1: #060b16;
            --bg-2: #0f1a2b;
            --panel: rgba(18, 30, 52, 0.72);
            --panel-solid: #121e34;
            --line: rgba(166, 190, 226, 0.2);
            --text: #eaf2ff;
            --muted: #93a8c9;
            --accent: #2cd4be;
            --accent-2: #56a6ff;
            --danger: #ff6b7f;
            --warning: #ffbf47;
            --shadow: 0 22px 45px rgba(0, 0, 0, 0.35);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Avenir Next", "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(75rem 55rem at 10% 0%, rgba(86, 166, 255, 0.18), transparent 48%),
                radial-gradient(60rem 50rem at 90% 100%, rgba(44, 212, 190, 0.12), transparent 45%),
                linear-gradient(130deg, var(--bg-1), var(--bg-2) 62%, #121629);
            padding: 28px 20px;
        }

        .shell {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            gap: 18px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            border: 1px solid var(--line);
            background: var(--panel);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: var(--shadow);
            animation: slide-in 420ms ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: linear-gradient(145deg, var(--accent), var(--accent-2));
            box-shadow: 0 10px 24px rgba(44, 212, 190, 0.28);
            position: relative;
        }

        .logo::after {
            content: "";
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: rgba(8, 18, 34, 0.9);
            position: absolute;
            top: 12px;
            left: 12px;
        }

        .brand h1 {
            margin: 0;
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            letter-spacing: 0.2px;
        }

        .brand p {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 0.86rem;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid rgba(44, 212, 190, 0.35);
            background: rgba(44, 212, 190, 0.12);
            color: #9bf6ea;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1.55fr) minmax(320px, 1fr);
            gap: 18px;
            align-items: start;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--panel);
            backdrop-filter: blur(14px);
            box-shadow: var(--shadow);
        }

        .main {
            padding: 18px;
            animation: slide-in 460ms ease;
        }

        .headline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .headline h2 {
            margin: 0;
            font-size: 1.16rem;
            letter-spacing: 0.2px;
        }

        .meta {
            color: var(--muted);
            font-size: 0.9rem;
            margin: 2px 0 0;
        }

        .status-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .notice {
            display: none;
            margin-bottom: 12px;
            border-radius: 12px;
            padding: 10px 12px;
            border: 1px solid transparent;
            font-size: 0.9rem;
            line-height: 1.35;
        }

        .notice.show {
            display: block;
            animation: slide-in 220ms ease;
        }

        .notice.success {
            border-color: rgba(44, 212, 190, 0.35);
            background: rgba(11, 57, 53, 0.48);
            color: #b8ffef;
        }

        .notice.error {
            border-color: rgba(255, 143, 159, 0.42);
            background: rgba(65, 19, 31, 0.52);
            color: #ffd3d9;
        }

        .notice.warning {
            border-color: rgba(255, 191, 71, 0.42);
            background: rgba(68, 48, 8, 0.52);
            color: #ffe3a4;
        }

        .notice a {
            color: #d7edff;
            margin-left: 6px;
            border-color: rgba(215, 237, 255, 0.32);
            background: rgba(15, 29, 48, 0.65);
        }

        .pill {
            padding: 8px 12px;
            border-radius: 12px;
            border: 1px solid rgba(166, 190, 226, 0.28);
            background: rgba(10, 20, 37, 0.52);
            color: var(--muted);
            font-size: 0.85rem;
            font-weight: 700;
        }

        .pill strong { color: var(--text); }

        .audio-grid {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 10px;
            margin-bottom: 12px;
        }

        .field {
            display: grid;
            gap: 6px;
            min-width: 0;
        }

        .field label {
            font-size: 0.8rem;
            color: var(--muted);
            letter-spacing: 0.35px;
            text-transform: uppercase;
        }

        select {
            width: 100%;
            border: 1px solid rgba(166, 190, 226, 0.25);
            border-radius: 11px;
            background: rgba(8, 15, 28, 0.7);
            color: var(--text);
            padding: 10px 11px;
            font-size: 0.9rem;
            outline: none;
        }

        select:focus {
            border-color: rgba(86, 166, 255, 0.45);
            box-shadow: 0 0 0 2px rgba(86, 166, 255, 0.16);
        }

        select:disabled { opacity: 0.45; }

        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-bottom: 12px;
        }

        button {
            border: 0;
            border-radius: 11px;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, filter 0.15s ease, opacity 0.15s ease;
        }

        button:hover { transform: translateY(-1px); }
        button:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

        .btn-primary {
            color: #02241e;
            background: linear-gradient(140deg, var(--accent), #64e6d8);
        }

        .btn-neutral {
            color: var(--text);
            border: 1px solid rgba(166, 190, 226, 0.35);
            background: rgba(8, 15, 28, 0.56);
        }

        .btn-warning {
            color: #2b1e00;
            background: linear-gradient(140deg, var(--warning), #ffd787);
        }

        .btn-danger {
            color: #3c0912;
            background: linear-gradient(140deg, var(--danger), #ff8f9f);
        }

        #preview {
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(166, 190, 226, 0.2);
            background: #000;
            min-height: 270px;
        }

        .sidebar {
            padding: 16px;
            animation: slide-in 520ms ease;
        }

        .side-title {
            margin: 0;
            font-size: 1rem;
        }

        .side-sub {
            margin: 4px 0 12px;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .list {
            display: grid;
            gap: 9px;
            max-height: 560px;
            overflow: auto;
            padding-right: 4px;
        }

        .item {
            border: 1px solid rgba(166, 190, 226, 0.2);
            background: rgba(10, 20, 37, 0.62);
            border-radius: 12px;
            padding: 10px;
        }

        .item-name {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.25;
            word-break: break-word;
        }

        .item small {
            display: block;
            color: var(--muted);
            margin-top: 4px;
        }

        .links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        a {
            color: #a9d2ff;
            text-decoration: none;
            font-size: 0.83rem;
            font-weight: 700;
            border: 1px solid rgba(169, 210, 255, 0.24);
            border-radius: 8px;
            padding: 4px 8px;
            background: rgba(14, 26, 46, 0.64);
        }

        a:hover { filter: brightness(1.1); }

        .action-btn {
            color: #a9d2ff;
            text-decoration: none;
            font-size: 0.83rem;
            font-weight: 700;
            border: 1px solid rgba(169, 210, 255, 0.24);
            border-radius: 8px;
            padding: 4px 8px;
            background: rgba(14, 26, 46, 0.64);
            cursor: pointer;
        }

        .action-btn:hover { filter: brightness(1.1); }

        .action-btn-danger {
            color: #ffc4cb;
            border-color: rgba(255, 143, 159, 0.45);
            background: rgba(73, 20, 31, 0.6);
        }

        @keyframes slide-in {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 1040px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                order: 2;
            }
        }

        @media (max-width: 700px) {
            body { padding: 16px 12px; }
            .header { padding: 12px; }
            .audio-grid { grid-template-columns: 1fr; }
            .controls button { flex: 1 1 calc(50% - 9px); }
            #preview { min-height: 220px; }
        }
    </style>
</head>
<body>
<div class="shell">
    <section class="header">
        <div class="brand">
            <div class="logo"></div>
            <div>
                <h1>Loom Recorder Studio</h1>
                <p>Graba pantalla, controla audio y comparte en segundos.</p>
            </div>
        </div>
        <div class="badge">Laravel + MediaRecorder</div>
    </section>

    <section class="layout">
        <article class="card main">
            <div class="headline">
                <div>
                    <h2>Nuevo Video</h2>
                    <p class="meta">Cuenta regresiva de 3 segundos, pausa/reanudar, stop, descarga, guardar y Drive.</p>
                </div>
            </div>

            <div class="status-row">
                <div class="pill" id="status">Estado: <strong>Listo</strong></div>
                <div class="pill">Tiempo: <strong id="timer">00:00</strong></div>
            </div>
            <div id="notice" class="notice" role="status" aria-live="polite"></div>

            <div class="audio-grid">
                <div class="field">
                    <label for="audioMode">Audio</label>
                    <select id="audioMode">
                        <option value="both" selected>Sistema + Micrófono</option>
                        <option value="system">Solo sistema (YouTube/pestaña)</option>
                        <option value="mic">Solo micrófono</option>
                        <option value="none">Sin audio</option>
                    </select>
                </div>
                <div class="field">
                    <label for="micSelect">Micrófono</label>
                    <select id="micSelect">
                        <option value="">Predeterminado</option>
                    </select>
                </div>
                <button id="refreshMicsBtn" class="btn-neutral" type="button">Actualizar</button>
            </div>

            <div class="controls">
                <button id="startBtn" class="btn-primary">Iniciar grabación</button>
                <button id="pauseBtn" class="btn-warning" disabled>Pausar</button>
                <button id="stopBtn" class="btn-danger" disabled>Stop</button>
                <button id="downloadBtn" class="btn-neutral" disabled>Descargar</button>
                <button id="saveBtn" class="btn-neutral" disabled>Guardar en servidor</button>
                <button id="driveBtn" class="btn-neutral" disabled>Subir a Google Drive</button>
            </div>

            <video id="preview" controls playsinline></video>
        </article>

        <aside class="card sidebar">
            <h3 class="side-title">Grabaciones recientes</h3>
            <p class="side-sub">Las ultimas 10 guardadas en servidor.</p>

            <div class="list">
                @forelse($recordings as $recording)
                    <div class="item">
                        <p class="item-name">{{ $recording->original_name ?? $recording->stored_name }}</p>
                        <small>{{ number_format($recording->size / 1024 / 1024, 2) }} MB · {{ $recording->created_at->format('Y-m-d H:i:s') }}</small>
                        <div class="links">
                            <a href="{{ route('recordings.show', ['token' => $recording->share_token]) }}" target="_blank">Ver</a>
                            <a href="{{ route('recordings.download', $recording) }}">Descargar</a>
                            @if($recording->google_drive_web_view_link)
                                <a href="{{ $recording->google_drive_web_view_link }}" target="_blank">Drive</a>
                            @endif
                            <button
                                class="action-btn action-btn-danger delete-recording-btn"
                                data-delete-url="{{ route('recordings.destroy', $recording) }}"
                                type="button"
                            >
                                Eliminar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="item">
                        <p class="item-name">Sin grabaciones todavía</p>
                        <small>Cuando guardes tu primer video, aparecerá aqui.</small>
                    </div>
                @endforelse
            </div>
        </aside>
    </section>
</div>

<script>
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const stopBtn = document.getElementById('stopBtn');
    const downloadBtn = document.getElementById('downloadBtn');
    const saveBtn = document.getElementById('saveBtn');
    const driveBtn = document.getElementById('driveBtn');
    const audioMode = document.getElementById('audioMode');
    const micSelect = document.getElementById('micSelect');
    const refreshMicsBtn = document.getElementById('refreshMicsBtn');
    const statusEl = document.getElementById('status');
    const noticeEl = document.getElementById('notice');
    const timerEl = document.getElementById('timer');
    const preview = document.getElementById('preview');
    const recordingList = document.querySelector('.list');

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    let mediaRecorder = null;
    let screenStream = null;
    let micStream = null;
    let mixedStream = null;
    let chunks = [];
    let recordedBlob = null;
    let recordedUrl = null;
    let recordingStartedAt = 0;
    let pausedStartedAt = 0;
    let pausedMs = 0;
    let timerInterval = null;
    let currentRecordingId = null;
    let currentUploadDriveUrl = null;
    let isCountingDown = false;

    function setStatus(text) {
        statusEl.innerHTML = `Estado: <strong>${text}</strong>`;
    }

    function showNotice(type, message, linkUrl = null) {
        noticeEl.className = `notice show ${type}`;
        if (linkUrl) {
            noticeEl.innerHTML = `${message} <a href="${linkUrl}" target="_blank" rel="noopener noreferrer">Abrir URL</a>`;
            return;
        }
        noticeEl.textContent = message;
    }

    function formatSeconds(totalSec) {
        const m = String(Math.floor(totalSec / 60)).padStart(2, '0');
        const s = String(totalSec % 60).padStart(2, '0');
        return `${m}:${s}`;
    }

    function getDurationSeconds() {
        if (!recordingStartedAt) return 0;
        const now = Date.now();
        let elapsed = now - recordingStartedAt - pausedMs;
        if (mediaRecorder?.state === 'paused' && pausedStartedAt) {
            elapsed -= (now - pausedStartedAt);
        }
        return Math.max(0, Math.floor(elapsed / 1000));
    }

    function useSystemAudio() {
        return audioMode.value === 'both' || audioMode.value === 'system';
    }

    function useMicAudio() {
        return audioMode.value === 'both' || audioMode.value === 'mic';
    }

    async function loadMicrophones() {
        try {
            let devices = await navigator.mediaDevices.enumerateDevices();
            let microphones = devices.filter(device => device.kind === 'audioinput');

            if (microphones.length === 0 || microphones.every(mic => !mic.label)) {
                try {
                    const tempStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    tempStream.getTracks().forEach(track => track.stop());
                    devices = await navigator.mediaDevices.enumerateDevices();
                    microphones = devices.filter(device => device.kind === 'audioinput');
                } catch (_) {
                    // Si no acepta permisos, seguimos con fallback visual.
                }
            }

            const previousValue = micSelect.value;
            micSelect.innerHTML = '<option value="">Predeterminado</option>';

            if (microphones.length === 0) {
                micSelect.innerHTML = '<option value="">No se detectaron micrófonos</option>';
                return;
            }

            microphones.forEach((mic, index) => {
                const option = document.createElement('option');
                option.value = mic.deviceId;
                option.textContent = mic.label || `Micrófono ${index + 1}`;
                micSelect.appendChild(option);
            });

            if ([...micSelect.options].some(option => option.value === previousValue)) {
                micSelect.value = previousValue;
            }
        } catch (_) {
            micSelect.innerHTML = '<option value="">No se pudieron leer micrófonos</option>';
            showNotice('error', 'No se pudieron listar los micrófonos. Revisa permisos de audio del navegador.');
        }
    }

    function toggleButtons({ recording = false, hasBlob = false, paused = false, canUploadDrive = false, countingDown = false }) {
        startBtn.disabled = recording || countingDown;
        pauseBtn.disabled = !recording;
        stopBtn.disabled = !recording;
        pauseBtn.textContent = paused ? 'Reanudar' : 'Pausar';

        downloadBtn.disabled = !hasBlob;
        saveBtn.disabled = !hasBlob;
        driveBtn.disabled = !canUploadDrive;

        const lockAudio = recording || countingDown;
        audioMode.disabled = lockAudio;
        micSelect.disabled = lockAudio || !useMicAudio();
        refreshMicsBtn.disabled = lockAudio || !useMicAudio();
    }

    async function runCountdown(seconds = 3) {
        isCountingDown = true;
        toggleButtons({
            hasBlob: Boolean(recordedBlob),
            canUploadDrive: Boolean(currentUploadDriveUrl),
            countingDown: true
        });

        for (let second = seconds; second > 0; second--) {
            setStatus(`Iniciando grabación en ${second}...`);
            await new Promise(resolve => setTimeout(resolve, 1000));
        }

        isCountingDown = false;
    }

    function stopTracks(stream) {
        if (!stream) return;
        stream.getTracks().forEach(track => track.stop());
    }

    function getDisplayConstraints() {
        return {
            video: true,
            audio: useSystemAudio(),
        };
    }

    async function startRecording() {
        try {
            if (isCountingDown) return;

            chunks = [];
            recordedBlob = null;
            currentRecordingId = null;
            currentUploadDriveUrl = null;
            if (recordedUrl) URL.revokeObjectURL(recordedUrl);
            preview.src = '';
            preview.srcObject = null;

            screenStream = await navigator.mediaDevices.getDisplayMedia(getDisplayConstraints());

            if (useSystemAudio() && screenStream.getAudioTracks().length === 0) {
                showNotice(
                    'warning',
                    'No se detectó audio del sistema en la fuente elegida. Continuamos grabando video (y micrófono si está activado).'
                );
            }

            micStream = null;
            if (useMicAudio()) {
                try {
                    const constraints = micSelect.value
                        ? { audio: { deviceId: { exact: micSelect.value } } }
                        : { audio: true };
                    micStream = await navigator.mediaDevices.getUserMedia(constraints);
                } catch (_) {
                    micStream = null;
                }
            }

            await runCountdown(3);

            const videoTracks = screenStream.getVideoTracks();
            const audioTracks = [
                ...screenStream.getAudioTracks(),
                ...(micStream ? micStream.getAudioTracks() : [])
            ];
            mixedStream = new MediaStream([...videoTracks, ...audioTracks]);

            const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp9')
                ? 'video/webm;codecs=vp9'
                : 'video/webm';

            mediaRecorder = new MediaRecorder(mixedStream, { mimeType });
            mediaRecorder.ondataavailable = event => {
                if (event.data && event.data.size > 0) chunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                recordedBlob = new Blob(chunks, { type: 'video/webm' });
                recordedUrl = URL.createObjectURL(recordedBlob);
                preview.src = recordedUrl;
                preview.controls = true;

                stopTracks(screenStream);
                stopTracks(micStream);
                stopTracks(mixedStream);

                clearInterval(timerInterval);
                timerInterval = null;

                setStatus('Grabación finalizada');
                toggleButtons({ hasBlob: true, canUploadDrive: false });
            };

            mediaRecorder.start(1000);
            recordingStartedAt = Date.now();
            pausedStartedAt = 0;
            pausedMs = 0;
            timerEl.textContent = '00:00';

            timerInterval = setInterval(() => {
                timerEl.textContent = formatSeconds(getDurationSeconds());
            }, 500);

            screenStream.getVideoTracks()[0].onended = () => {
                if (mediaRecorder && mediaRecorder.state !== 'inactive') mediaRecorder.stop();
            };

            setStatus('Grabando');
            toggleButtons({ recording: true, paused: false });
        } catch (error) {
            stopTracks(screenStream);
            stopTracks(micStream);
            stopTracks(mixedStream);
            isCountingDown = false;
            setStatus('Error al iniciar grabación');
            toggleButtons({ hasBlob: Boolean(recordedBlob), canUploadDrive: Boolean(currentUploadDriveUrl) });
            showNotice('error', error.message || 'No se pudo iniciar la grabación. Verifica permisos de pantalla y micrófono.');
            alert(error.message || 'No se pudo iniciar la grabación. Verifica permisos de pantalla y micrófono.');
        }
    }

    function pauseOrResume() {
        if (!mediaRecorder) return;

        if (mediaRecorder.state === 'recording') {
            mediaRecorder.pause();
            pausedStartedAt = Date.now();
            setStatus('Pausado');
            toggleButtons({ recording: true, paused: true });
            return;
        }

        if (mediaRecorder.state === 'paused') {
            mediaRecorder.resume();
            if (pausedStartedAt) pausedMs += Date.now() - pausedStartedAt;
            pausedStartedAt = 0;
            setStatus('Grabando');
            toggleButtons({ recording: true, paused: false });
        }
    }

    function stopRecording() {
        if (!mediaRecorder || mediaRecorder.state === 'inactive') return;
        if (mediaRecorder.state === 'paused' && pausedStartedAt) {
            pausedMs += Date.now() - pausedStartedAt;
            pausedStartedAt = 0;
        }
        mediaRecorder.stop();
    }

    function downloadRecording() {
        if (!recordedBlob) return;
        const url = URL.createObjectURL(recordedBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `loom-${new Date().toISOString().replace(/[:.]/g, '-')}.webm`;
        a.click();
        URL.revokeObjectURL(url);
    }

    async function saveToServer() {
        if (!recordedBlob) return;

        saveBtn.disabled = true;
        setStatus('Guardando en servidor...');

        const formData = new FormData();
        const filename = `loom-${new Date().toISOString().replace(/[:.]/g, '-')}.webm`;
        formData.append('video', recordedBlob, filename);
        formData.append('duration_seconds', String(getDurationSeconds()));

        try {
            const response = await fetch('{{ route('recordings.store') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                body: formData
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'No se pudo guardar.');

            currentRecordingId = data.recording.id;
            currentUploadDriveUrl = data.upload_drive_url;
            setStatus('Guardado en servidor');
            toggleButtons({ hasBlob: true, canUploadDrive: true });
            if (data.view_url) {
                preview.src = data.view_url;
            }

            if (data.view_url) {
                let copied = false;
                try {
                    await navigator.clipboard.writeText(data.view_url);
                    copied = true;
                } catch (_) {
                    copied = false;
                }
                showNotice(
                    'success',
                    copied
                        ? 'Tu video se guardó correctamente. URL lista y copiada al portapapeles.'
                        : 'Tu video se guardó correctamente. Aquí tienes su URL.',
                    data.view_url
                );
            } else {
                showNotice('success', 'Tu video se guardó correctamente.');
            }
        } catch (error) {
            setStatus('Error al guardar');
            saveBtn.disabled = false;
            showNotice('error', error.message || 'Error al guardar en servidor');
            alert(error.message || 'Error al guardar en servidor');
        }
    }

    async function uploadToDrive() {
        if (!currentRecordingId || !currentUploadDriveUrl) {
            alert('Primero guarda la grabación en el servidor.');
            return;
        }

        driveBtn.disabled = true;
        setStatus('Subiendo a Google Drive...');

        try {
            const response = await fetch(currentUploadDriveUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Error al subir');

            setStatus('Subido a Google Drive');
            if (data.google_drive_web_view_link) {
                window.open(data.google_drive_web_view_link, '_blank');
            } else {
                alert('Subido. No se recibió un link público.');
            }
            window.location.reload();
        } catch (error) {
            setStatus('Error en Google Drive');
            driveBtn.disabled = false;
            alert(error.message || 'Error subiendo a Google Drive');
        }
    }

    async function deleteRecording(deleteUrl) {
        const confirmDelete = confirm('Esto eliminara el video por completo (servidor, base de datos y Google Drive si aplica). Esta accion no se puede deshacer. ¿Continuar?');
        if (!confirmDelete) return;

        setStatus('Eliminando video...');

        try {
            const response = await fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            if (!response.ok) {
                const errorMsg = data.errors ? data.errors.join(' | ') : data.message;
                throw new Error(errorMsg || 'No se pudo eliminar');
            }

            setStatus('Video eliminado por completo');
            showNotice('success', 'Tu video se borró por completo.');
            setTimeout(() => window.location.reload(), 1200);
        } catch (error) {
            setStatus('Error al eliminar');
            showNotice('error', error.message || 'Error eliminando el video');
            alert(error.message || 'Error eliminando el video');
        }
    }

    startBtn.addEventListener('click', startRecording);
    pauseBtn.addEventListener('click', pauseOrResume);
    stopBtn.addEventListener('click', stopRecording);
    downloadBtn.addEventListener('click', downloadRecording);
    saveBtn.addEventListener('click', saveToServer);
    driveBtn.addEventListener('click', uploadToDrive);
    refreshMicsBtn.addEventListener('click', loadMicrophones);
    audioMode.addEventListener('change', () => {
        toggleButtons({
            recording: mediaRecorder?.state === 'recording' || mediaRecorder?.state === 'paused',
            hasBlob: Boolean(recordedBlob),
            paused: mediaRecorder?.state === 'paused',
            canUploadDrive: Boolean(currentUploadDriveUrl),
            countingDown: isCountingDown
        });
    });
    recordingList?.addEventListener('click', (event) => {
        const target = event.target.closest('.delete-recording-btn');
        if (!target) return;
        deleteRecording(target.dataset.deleteUrl);
    });
    if (navigator.mediaDevices?.addEventListener) {
        navigator.mediaDevices.addEventListener('devicechange', loadMicrophones);
    }

    loadMicrophones();
</script>
</body>
</html>
