<x-filament-panels::page>
    <style>
        .incidit-vox {
            --vox-ink: #241812;
            --vox-muted: #6d625c;
            --vox-line: #e5d8cc;
            --vox-panel: #fffaf4;
            --vox-panel-strong: #f7ecdf;
            --vox-accent: #9b4d1b;
            --vox-accent-dark: #71350f;
            --vox-graph: #fbf7f1;
            color: var(--vox-ink);
        }

        .incidit-vox__shell {
            overflow: hidden;
            border: 1px solid var(--vox-line);
            border-radius: 12px;
            background: linear-gradient(180deg, #fffdf9 0%, #fbf5ed 100%);
            box-shadow: 0 18px 50px rgb(66 38 13 / 10%);
        }

        .incidit-vox__header {
            display: grid;
            gap: 18px;
            padding: 26px;
            border-bottom: 1px solid var(--vox-line);
            background:
                linear-gradient(120deg, rgb(155 77 27 / 10%), transparent 52%),
                #fffaf4;
        }

        @media (min-width: 860px) {
            .incidit-vox__header {
                grid-template-columns: 1fr minmax(280px, 420px);
                align-items: end;
            }
        }

        .incidit-vox__title {
            margin: 0;
            font-size: 1.55rem;
            line-height: 1.15;
            font-weight: 700;
            letter-spacing: 0;
        }

        .incidit-vox__subtitle {
            max-width: 58ch;
            margin: 8px 0 0;
            color: var(--vox-muted);
            font-size: .95rem;
            line-height: 1.65;
        }

        .incidit-vox__upload {
            display: grid;
            gap: 10px;
            padding: 16px;
            border: 1px solid rgb(155 77 27 / 20%);
            border-radius: 10px;
            background: rgb(255 255 255 / 72%);
        }

        .incidit-vox__upload-title {
            margin: 0;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--vox-accent-dark);
        }

        .incidit-vox__controls {
            display: grid;
            gap: 10px;
        }

        @media (min-width: 560px) {
            .incidit-vox__controls {
                grid-template-columns: 1fr auto;
                align-items: center;
            }
        }

        .incidit-vox input[type="file"] {
            width: 100%;
            min-width: 0;
            border: 1px solid var(--vox-line);
            border-radius: 8px;
            background: white;
            color: var(--vox-ink);
            font-size: .9rem;
        }

        .incidit-vox input[type="file"]::file-selector-button {
            margin-right: 14px;
            border: 0;
            border-right: 1px solid var(--vox-line);
            background: var(--vox-panel-strong);
            padding: 11px 14px;
            color: var(--vox-accent-dark);
            font-weight: 700;
            cursor: pointer;
        }

        .incidit-vox__button {
            min-height: 42px;
            border: 0;
            border-radius: 8px;
            background: var(--vox-accent);
            padding: 0 18px;
            color: white;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color .16s ease, transform .16s ease;
        }

        .incidit-vox__button:hover {
            background: var(--vox-accent-dark);
            transform: translateY(-1px);
        }

        .incidit-vox__body {
            display: grid;
            gap: 18px;
            padding: 22px;
        }

        .incidit-vox__result-grid {
            display: grid;
            gap: 16px;
        }

        @media (min-width: 900px) {
            .incidit-vox__result-grid {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            }
        }

        .incidit-vox__panel {
            min-height: 132px;
            border: 1px solid var(--vox-line);
            border-radius: 10px;
            background: var(--vox-panel);
            padding: 18px;
        }

        .incidit-vox__panel-title {
            margin: 0 0 12px;
            color: var(--vox-accent-dark);
            font-size: .82rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .incidit-vox__panel ul {
            display: grid;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: .94rem;
            line-height: 1.55;
        }

        .incidit-vox__panel li {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            border-bottom: 1px solid rgb(229 216 204 / 70%);
            padding-bottom: 7px;
        }

        .incidit-vox__panel li:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .incidit-vox__panel strong {
            color: var(--vox-ink);
        }

        .incidit-vox__graph {
            border: 1px solid var(--vox-line);
            border-radius: 10px;
            background: var(--vox-graph);
            padding: 18px;
        }

        .incidit-vox__canvas-wrap {
            overflow-x: auto;
            border-radius: 8px;
            background: white;
            padding: 12px;
        }

        #fft-canvas {
            display: block;
            width: 100%;
            min-width: 720px;
            height: 360px;
            border: 1px solid var(--vox-line);
            border-radius: 6px;
            background: white;
        }

        .incidit-vox__empty {
            margin: 0;
            color: var(--vox-muted);
            font-size: .9rem;
            line-height: 1.55;
        }
    </style>

    <div
        id="incidit-vox-admin"
        class="incidit-vox incidit-vox__shell"
    >
        <header class="incidit-vox__header">
            <div>
                <h2 class="incidit-vox__title">Incidit Vox</h2>
                <p class="incidit-vox__subtitle">Analyse vibratoire artisanale d’archets, avec détection de zone de résonance, pics dominants et amortissement.</p>
            </div>

            <section id="upload-section" class="incidit-vox__upload">
                <h3 class="incidit-vox__upload-title">Fichier de données</h3>
                <div class="incidit-vox__controls">
                    <input type="file" id="file-input" accept=".txt">
                    <button id="analyze-btn" type="button" class="incidit-vox__button">Analyser</button>
                </div>
            </section>
        </header>

        <div class="incidit-vox__body">
            <section id="results-section" class="incidit-vox__result-grid">
                <div class="incidit-vox__panel">
                    <h3 class="incidit-vox__panel-title">Synthèse</h3>
                    <div id="summary">
                        <p class="incidit-vox__empty">Importe un fichier .txt pour calculer les indicateurs principaux.</p>
                    </div>
                </div>

                <div class="incidit-vox__panel">
                    <h3 class="incidit-vox__panel-title">Pics détectés</h3>
                    <div id="peaks-table">
                        <p class="incidit-vox__empty">Les dix pics dominants apparaîtront ici après analyse.</p>
                    </div>
                </div>
            </section>

            <section id="spectrum" class="incidit-vox__graph">
                <h3 class="incidit-vox__panel-title">Spectre FFT</h3>
                <div class="incidit-vox__canvas-wrap">
                    <canvas id="fft-canvas" width="1100" height="420"></canvas>
                </div>
            </section>
        </div>
    </div>

    @once
        <script src="/incidit-vox/libs/fft.min.js"></script>
        <script src="/incidit-vox/js/signal.js"></script>
        <script src="/incidit-vox/js/main.js"></script>
    @endonce
</x-filament-panels::page>
