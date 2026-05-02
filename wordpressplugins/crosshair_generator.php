<?php
/**
 * Plugin Name: CS2 Crosshair Generator
 * Description: A form that lets users configure CS2 crosshair settings and download a .cfg file.
 * Version: 1.0.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// --- Shortcode ---
add_shortcode( 'cs2_crosshair', 'cs2_crosshair_render' );

function cs2_crosshair_render() {
    ob_start();
    ?>
    <div id="cs2cg-wrap">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Barlow+Condensed:wght@400;600;700&display=swap');

        #cs2cg-wrap {
            --bg: #0a0c0f;
            --panel: #111318;
            --border: #1e2330;
            --accent: #e8a020;
            --accent2: #ff4d1a;
            --text: #c8d0dc;
            --muted: #4a5568;
            --green: #39ff14;
            --red: #ff3030;
            --blue: #00aaff;
            --yellow: #ffdd00;
            --cyan: #00ffcc;
            --mono: 'Share Tech Mono', monospace;
            --sans: 'Barlow Condensed', sans-serif;

            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            font-size: 15px;
            padding: 32px 20px;
            border-radius: 4px;
            max-width: 960px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        #cs2cg-wrap *, #cs2cg-wrap *::before, #cs2cg-wrap *::after {
            box-sizing: border-box;
        }

        .cs2cg-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
        }

        .cs2cg-header h2 {
            font-family: var(--sans);
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #fff;
            margin: 0;
            line-height: 1;
        }

        .cs2cg-header h2 span { color: var(--accent); }

        .cs2cg-badge {
            background: var(--accent2);
            color: #fff;
            font-family: var(--mono);
            font-size: 10px;
            letter-spacing: 0.1em;
            padding: 3px 8px;
            border-radius: 2px;
            text-transform: uppercase;
        }

        /* Layout */
        .cs2cg-body {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 680px) {
            .cs2cg-body { grid-template-columns: 1fr; }
        }

        /* Form panels */
        .cs2cg-section {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 3px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .cs2cg-section-title {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--accent);
            padding: 10px 16px;
            background: rgba(232, 160, 32, 0.06);
            border-bottom: 1px solid var(--border);
        }

        .cs2cg-fields {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .cs2cg-field {
            display: grid;
            grid-template-columns: 160px 1fr auto;
            align-items: center;
            gap: 12px;
        }

        @media (max-width: 520px) {
            .cs2cg-field {
                grid-template-columns: 1fr;
                gap: 6px;
            }
        }

        .cs2cg-label {
            font-family: var(--mono);
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.05em;
        }

        .cs2cg-val {
            font-family: var(--mono);
            font-size: 13px;
            color: var(--accent);
            min-width: 36px;
            text-align: right;
        }

        /* Range slider */
        input[type=range].cs2cg-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            outline: none;
            cursor: pointer;
        }

        input[type=range].cs2cg-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            border-radius: 2px;
            background: var(--accent);
            cursor: pointer;
        }

        input[type=range].cs2cg-slider::-moz-range-thumb {
            width: 14px;
            height: 14px;
            border-radius: 2px;
            background: var(--accent);
            border: none;
            cursor: pointer;
        }

        /* Select */
        select.cs2cg-select {
            background: #0d0f14;
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--mono);
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 2px;
            cursor: pointer;
            width: 100%;
            outline: none;
        }

        select.cs2cg-select:focus {
            border-color: var(--accent);
        }

        /* Toggle / checkbox */
        .cs2cg-toggle-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cs2cg-toggle {
            position: relative;
            width: 36px;
            height: 20px;
            flex-shrink: 0;
        }

        .cs2cg-toggle input { opacity: 0; width: 0; height: 0; }

        .cs2cg-toggle-track {
            position: absolute;
            inset: 0;
            background: var(--border);
            border-radius: 2px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .cs2cg-toggle-track::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 3px;
            width: 14px;
            height: 14px;
            background: var(--muted);
            border-radius: 1px;
            transition: transform 0.2s, background 0.2s;
        }

        .cs2cg-toggle input:checked + .cs2cg-toggle-track {
            background: rgba(232, 160, 32, 0.15);
        }

        .cs2cg-toggle input:checked + .cs2cg-toggle-track::after {
            transform: translateX(16px);
            background: var(--accent);
        }

        /* Color swatches */
        .cs2cg-colors {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .cs2cg-swatch {
            width: 22px;
            height: 22px;
            border-radius: 2px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.15s, transform 0.15s;
        }

        .cs2cg-swatch:hover { transform: scale(1.15); }
        .cs2cg-swatch.active { border-color: #fff; }

        .cs2cg-custom-color {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cs2cg-custom-color input[type=color] {
            width: 28px;
            height: 22px;
            border: 1px solid var(--border);
            background: none;
            padding: 0;
            cursor: pointer;
            border-radius: 2px;
        }

        /* Preview panel */
        .cs2cg-preview-panel {
            position: sticky;
            top: 20px;
        }

        .cs2cg-preview-box {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 3px;
            overflow: hidden;
        }

        .cs2cg-preview-title {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--accent);
            padding: 10px 16px;
            background: rgba(232, 160, 32, 0.06);
            border-bottom: 1px solid var(--border);
        }

        #cs2cg-canvas-wrap {
            background: #1a1f2e;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background-image: radial-gradient(circle at center, #232b3d 0%, #0f1218 100%);
        }

        #cs2cg-canvas {
            display: block;
        }

        /* Output */
        .cs2cg-output-box {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 3px;
            margin-top: 16px;
            overflow: hidden;
        }

        .cs2cg-output-title {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--cyan);
            padding: 10px 16px;
            background: rgba(0, 255, 204, 0.04);
            border-bottom: 1px solid var(--border);
        }

        #cs2cg-output {
            font-family: var(--mono);
            font-size: 12px;
            color: #7ec8a0;
            padding: 14px 16px;
            line-height: 1.9;
            white-space: pre;
            overflow-x: auto;
        }

        .cs2cg-btn {
            display: block;
            width: 100%;
            margin-top: 16px;
            padding: 14px;
            background: var(--accent);
            color: #000;
            font-family: var(--sans);
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
        }

        .cs2cg-btn:hover {
            background: #ffb733;
            transform: translateY(-1px);
        }

        .cs2cg-btn:active {
            transform: translateY(0);
        }

        .cs2cg-hint {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--muted);
            margin-top: 10px;
            text-align: center;
            letter-spacing: 0.04em;
        }
    </style>

    <div class="cs2cg-header">
        <h2>CS2 <span>Crosshair</span> Generator</h2>
        <span class="cs2cg-badge">cfg export</span>
    </div>

    <div class="cs2cg-body">
        <!-- LEFT: FORM -->
        <div class="cs2cg-form">

            <!-- Style -->
            <div class="cs2cg-section">
                <div class="cs2cg-section-title">// style</div>
                <div class="cs2cg-fields">
                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairstyle</span>
                        <select class="cs2cg-select" id="cg_style" onchange="cs2cg_update()">
                            <option value="0">0 — Dot</option>
                            <option value="1">1 — Default static</option>
                            <option value="2">2 — Default (+ anim)</option>
                            <option value="3">3 — Classic static</option>
                            <option value="4" selected>4 — Classic (+ anim)</option>
                            <option value="5">5 — Legacy</option>
                        </select>
                        <span class="cs2cg-val" id="cv_style">4</span>
                    </div>
                </div>
            </div>

            <!-- Size & Shape -->
            <div class="cs2cg-section">
                <div class="cs2cg-section-title">// size &amp; shape</div>
                <div class="cs2cg-fields">

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairsize</span>
                        <input type="range" class="cs2cg-slider" id="cg_size" min="0" max="10" step="0.5" value="3" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_size">3</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairthickness</span>
                        <input type="range" class="cs2cg-slider" id="cg_thick" min="0.5" max="3" step="0.5" value="1" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_thick">1</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairgap</span>
                        <input type="range" class="cs2cg-slider" id="cg_gap" min="-5" max="10" step="0.5" value="-2" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_gap">-2</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshair_t</span>
                        <div class="cs2cg-toggle-wrap">
                            <label class="cs2cg-toggle">
                                <input type="checkbox" id="cg_t" onchange="cs2cg_update()">
                                <span class="cs2cg-toggle-track"></span>
                            </label>
                            <span class="cs2cg-label">T-style (no top line)</span>
                        </div>
                        <span class="cs2cg-val" id="cv_t">0</span>
                    </div>

                </div>
            </div>

            <!-- Dot -->
            <div class="cs2cg-section">
                <div class="cs2cg-section-title">// center dot</div>
                <div class="cs2cg-fields">

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairdot</span>
                        <div class="cs2cg-toggle-wrap">
                            <label class="cs2cg-toggle">
                                <input type="checkbox" id="cg_dot" onchange="cs2cg_update()">
                                <span class="cs2cg-toggle-track"></span>
                            </label>
                            <span class="cs2cg-label">Enable center dot</span>
                        </div>
                        <span class="cs2cg-val" id="cv_dot">0</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairdot_size</span>
                        <input type="range" class="cs2cg-slider" id="cg_dotsize" min="1" max="5" step="0.5" value="2" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_dotsize">2</span>
                    </div>

                </div>
            </div>

            <!-- Outline -->
            <div class="cs2cg-section">
                <div class="cs2cg-section-title">// outline</div>
                <div class="cs2cg-fields">

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshair_drawoutline</span>
                        <div class="cs2cg-toggle-wrap">
                            <label class="cs2cg-toggle">
                                <input type="checkbox" id="cg_outline" onchange="cs2cg_update()">
                                <span class="cs2cg-toggle-track"></span>
                            </label>
                            <span class="cs2cg-label">Enable outline</span>
                        </div>
                        <span class="cs2cg-val" id="cv_outline">0</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshair_outlinethickness</span>
                        <input type="range" class="cs2cg-slider" id="cg_outlinethick" min="0.5" max="3" step="0.5" value="1" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_outlinethick">1</span>
                    </div>

                </div>
            </div>

            <!-- Color -->
            <div class="cs2cg-section">
                <div class="cs2cg-section-title">// color &amp; alpha</div>
                <div class="cs2cg-fields">

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshaircolor</span>
                        <div class="cs2cg-colors" id="cg_color_swatches">
                            <div class="cs2cg-swatch active" data-color="1" style="background:#39ff14" title="Green (1)" onclick="cs2cg_setcolor(this,1)"></div>
                            <div class="cs2cg-swatch" data-color="2" style="background:#ffdd00" title="Yellow (2)" onclick="cs2cg_setcolor(this,2)"></div>
                            <div class="cs2cg-swatch" data-color="3" style="background:#00aaff" title="Blue (3)" onclick="cs2cg_setcolor(this,3)"></div>
                            <div class="cs2cg-swatch" data-color="4" style="background:#ff3030" title="Red (4)" onclick="cs2cg_setcolor(this,4)"></div>
                            <div class="cs2cg-swatch" data-color="5" style="background:#00ffcc" title="Cyan (5)" onclick="cs2cg_setcolor(this,5)"></div>
                            <div class="cs2cg-custom-color">
                                <div class="cs2cg-swatch" data-color="0" style="background:#ffffff" title="Custom (0)" onclick="cs2cg_setcolor(this,0)"></div>
                                <input type="color" id="cg_customcolor" value="#ffffff" onchange="cs2cg_customcolor()" title="Custom RGB">
                            </div>
                        </div>
                        <span class="cs2cg-val" id="cv_color">1</span>
                    </div>

                    <div class="cs2cg-field" id="cg_rgb_row" style="display:none;">
                        <span class="cs2cg-label">R / G / B</span>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="range" class="cs2cg-slider" id="cg_r" min="0" max="255" value="255" oninput="cs2cg_update()">
                            <input type="range" class="cs2cg-slider" id="cg_g" min="0" max="255" value="255" oninput="cs2cg_update()">
                            <input type="range" class="cs2cg-slider" id="cg_b" min="0" max="255" value="255" oninput="cs2cg_update()">
                        </div>
                        <span class="cs2cg-val" id="cv_rgb">255/255/255</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairalpha</span>
                        <input type="range" class="cs2cg-slider" id="cg_alpha" min="0" max="255" value="200" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_alpha">200</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshairusealpha</span>
                        <div class="cs2cg-toggle-wrap">
                            <label class="cs2cg-toggle">
                                <input type="checkbox" id="cg_usealpha" checked onchange="cs2cg_update()">
                                <span class="cs2cg-toggle-track"></span>
                            </label>
                            <span class="cs2cg-label">Use alpha</span>
                        </div>
                        <span class="cs2cg-val" id="cv_usealpha">1</span>
                    </div>

                </div>
            </div>

            <!-- Misc -->
            <div class="cs2cg-section">
                <div class="cs2cg-section-title">// misc</div>
                <div class="cs2cg-fields">

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshair_dynamic_splitdist</span>
                        <input type="range" class="cs2cg-slider" id="cg_splitdist" min="1" max="16" step="1" value="7" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_splitdist">7</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_fixedcrosshairgap</span>
                        <input type="range" class="cs2cg-slider" id="cg_fixedgap" min="-10" max="10" step="0.5" value="3" oninput="cs2cg_update()">
                        <span class="cs2cg-val" id="cv_fixedgap">3</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshair_sniper_show</span>
                        <div class="cs2cg-toggle-wrap">
                            <label class="cs2cg-toggle">
                                <input type="checkbox" id="cg_sniper" onchange="cs2cg_update()">
                                <span class="cs2cg-toggle-track"></span>
                            </label>
                            <span class="cs2cg-label">Show with sniper scope</span>
                        </div>
                        <span class="cs2cg-val" id="cv_sniper">0</span>
                    </div>

                    <div class="cs2cg-field">
                        <span class="cs2cg-label">cl_crosshair_recoil</span>
                        <div class="cs2cg-toggle-wrap">
                            <label class="cs2cg-toggle">
                                <input type="checkbox" id="cg_recoil" onchange="cs2cg_update()">
                                <span class="cs2cg-toggle-track"></span>
                            </label>
                            <span class="cs2cg-label">Follow recoil</span>
                        </div>
                        <span class="cs2cg-val" id="cv_recoil">0</span>
                    </div>

                </div>
            </div>

        </div><!-- /form -->

        <!-- RIGHT: PREVIEW + OUTPUT -->
        <div class="cs2cg-preview-panel">

            <div class="cs2cg-preview-box">
                <div class="cs2cg-preview-title">// live preview</div>
                <div id="cs2cg-canvas-wrap">
                    <canvas id="cs2cg-canvas" width="180" height="180"></canvas>
                </div>
            </div>

            <div class="cs2cg-output-box">
                <div class="cs2cg-output-title">// generated config</div>
                <pre id="cs2cg-output"></pre>
            </div>

            <button class="cs2cg-btn" onclick="cs2cg_download()">⬇ Download .cfg</button>
            <p class="cs2cg-hint">Drop file in: CS2/game/csgo/cfg/</p>

        </div>
    </div><!-- /body -->

    </div><!-- /wrap -->

    <script>
    (function() {

        var selectedColor = 1;
        var colorMap = {
            1: {r:57,  g:255, b:20},
            2: {r:255, g:221, b:0},
            3: {r:0,   g:170, b:255},
            4: {r:255, g:48,  b:48},
            5: {r:0,   g:255, b:204},
        };

        window.cs2cg_setcolor = function(el, val) {
            selectedColor = val;
            document.querySelectorAll('#cs2cg-wrap .cs2cg-swatch').forEach(function(s){ s.classList.remove('active'); });
            el.classList.add('active');
            document.getElementById('cv_color').textContent = val;
            document.getElementById('cg_rgb_row').style.display = (val === 0) ? '' : 'none';
            cs2cg_update();
        };

        window.cs2cg_customcolor = function() {
            var hex = document.getElementById('cg_customcolor').value;
            var r = parseInt(hex.slice(1,3),16);
            var g = parseInt(hex.slice(3,5),16);
            var b = parseInt(hex.slice(5,7),16);
            document.getElementById('cg_r').value = r;
            document.getElementById('cg_g').value = g;
            document.getElementById('cg_b').value = b;
            cs2cg_update();
        };

        function getVal(id) { return parseFloat(document.getElementById(id).value); }
        function getCheck(id) { return document.getElementById(id).checked ? 1 : 0; }

        function getColor() {
            if (selectedColor !== 0) return colorMap[selectedColor];
            return { r: getVal('cg_r'), g: getVal('cg_g'), b: getVal('cg_b') };
        }

        window.cs2cg_update = function() {
            // update display values
            var ids = ['size','thick','gap','alpha','outlinethick','dotsize','splitdist','fixedgap'];
            ids.forEach(function(k){
                var el = document.getElementById('cv_'+k);
                if (el) el.textContent = document.getElementById('cg_'+k).value;
            });
            document.getElementById('cv_t').textContent = getCheck('cg_t');
            document.getElementById('cv_dot').textContent = getCheck('cg_dot');
            document.getElementById('cv_outline').textContent = getCheck('cg_outline');
            document.getElementById('cv_usealpha').textContent = getCheck('cg_usealpha');
            document.getElementById('cv_sniper').textContent = getCheck('cg_sniper');
            document.getElementById('cv_recoil').textContent = getCheck('cg_recoil');
            document.getElementById('cv_style').textContent = document.getElementById('cg_style').value;

            var rgb = getColor();
            document.getElementById('cv_rgb').textContent = rgb.r+'/'+rgb.g+'/'+rgb.b;

            cs2cg_drawPreview();
            cs2cg_buildOutput();
        };

        function cs2cg_drawPreview() {
            var canvas = document.getElementById('cs2cg-canvas');
            var ctx = canvas.getContext('2d');
            var w = canvas.width, h = canvas.height;
            var cx = w/2, cy = h/2;

            ctx.clearRect(0,0,w,h);

            var style    = parseInt(document.getElementById('cg_style').value);
            var size     = getVal('cg_size') * 5;
            var thick    = getVal('cg_thick') * 2.5;
            var gap      = getVal('cg_gap') * 2.5;
            var tStyle   = getCheck('cg_t');
            var dot      = getCheck('cg_dot');
            var dotSize  = getVal('cg_dotsize') * 2;
            var outline  = getCheck('cg_outline');
            var outT     = getVal('cg_outlinethick');
            var alpha    = getCheck('cg_usealpha') ? (getVal('cg_alpha')/255) : 1;
            var rgb      = getColor();

            var colStr = 'rgba('+rgb.r+','+rgb.g+','+rgb.b+','+alpha+')';
            var outStr = 'rgba(0,0,0,'+(alpha*0.85)+')';

            // helper: draw one line segment
            function drawLine(x1,y1,x2,y2,color,w) {
                ctx.save();
                ctx.strokeStyle = color;
                ctx.lineWidth = w;
                ctx.lineCap = 'square';
                ctx.beginPath();
                ctx.moveTo(x1,y1);
                ctx.lineTo(x2,y2);
                ctx.stroke();
                ctx.restore();
            }

            if (style === 0) {
                // dot only
                if (outline) {
                    ctx.save();
                    ctx.fillStyle = outStr;
                    ctx.beginPath();
                    ctx.arc(cx,cy,dotSize+outT,0,Math.PI*2);
                    ctx.fill();
                    ctx.restore();
                }
                ctx.save();
                ctx.fillStyle = colStr;
                ctx.beginPath();
                ctx.arc(cx,cy,dotSize,0,Math.PI*2);
                ctx.fill();
                ctx.restore();
                return;
            }

            var lineStart = gap + thick/2;
            var lineEnd   = gap + thick/2 + size;

            // outline pass
            if (outline) {
                var ot = thick + outT*2;
                // right
                drawLine(cx+lineStart, cy, cx+lineEnd, cy, outStr, ot+outT*2);
                // left
                drawLine(cx-lineStart, cy, cx-lineEnd, cy, outStr, ot+outT*2);
                // bottom
                drawLine(cx, cy+lineStart, cx, cy+lineEnd, outStr, ot+outT*2);
                // top (if not T-style)
                if (!tStyle) drawLine(cx, cy-lineStart, cx, cy-lineEnd, outStr, ot+outT*2);
            }

            // color pass
            drawLine(cx+lineStart, cy, cx+lineEnd, cy, colStr, thick);
            drawLine(cx-lineStart, cy, cx-lineEnd, cy, colStr, thick);
            drawLine(cx, cy+lineStart, cx, cy+lineEnd, colStr, thick);
            if (!tStyle) drawLine(cx, cy-lineStart, cx, cy-lineEnd, colStr, thick);

            // center dot
            if (dot) {
                if (outline) {
                    ctx.save();
                    ctx.fillStyle = outStr;
                    ctx.beginPath();
                    ctx.arc(cx,cy,dotSize+outT,0,Math.PI*2);
                    ctx.fill();
                    ctx.restore();
                }
                ctx.save();
                ctx.fillStyle = colStr;
                ctx.beginPath();
                ctx.arc(cx,cy,dotSize,0,Math.PI*2);
                ctx.fill();
                ctx.restore();
            }
        }

        function cs2cg_buildOutput() {
            var style     = document.getElementById('cg_style').value;
            var size      = document.getElementById('cg_size').value;
            var thick     = document.getElementById('cg_thick').value;
            var gap       = document.getElementById('cg_gap').value;
            var tStyle    = getCheck('cg_t');
            var dot       = getCheck('cg_dot');
            var dotSize   = document.getElementById('cg_dotsize').value;
            var outline   = getCheck('cg_outline');
            var outT      = document.getElementById('cg_outlinethick').value;
            var alpha     = document.getElementById('cg_alpha').value;
            var useAlpha  = getCheck('cg_usealpha');
            var color     = selectedColor;
            var rgb       = getColor();
            var splitdist = document.getElementById('cg_splitdist').value;
            var fixedgap  = document.getElementById('cg_fixedgap').value;
            var sniper    = getCheck('cg_sniper');
            var recoil    = getCheck('cg_recoil');

            var lines = [
                '// CS2 Crosshair Config',
                '// Generated by CS2 Crosshair Generator',
                '',
                'cl_crosshairstyle '        + style,
                'cl_crosshairsize '         + size,
                'cl_crosshairthickness '    + thick,
                'cl_crosshairgap '          + gap,
                'cl_crosshair_t '           + tStyle,
                'cl_crosshairdot '          + dot,
                'cl_crosshairdot_size '     + dotSize,
                'cl_crosshair_drawoutline ' + outline,
                'cl_crosshair_outlinethickness ' + outT,
                'cl_crosshaircolor '        + color,
                'cl_crosshaircolor_r '      + rgb.r,
                'cl_crosshaircolor_g '      + rgb.g,
                'cl_crosshaircolor_b '      + rgb.b,
                'cl_crosshairalpha '        + alpha,
                'cl_crosshairusealpha '     + useAlpha,
                'cl_crosshair_dynamic_splitdist ' + splitdist,
                'cl_fixedcrosshairgap '     + fixedgap,
                'cl_crosshair_sniper_show ' + sniper,
                'cl_crosshair_recoil '      + recoil,
            ];

            document.getElementById('cs2cg-output').textContent = lines.join('\n');
        }

        window.cs2cg_download = function() {
            var content = document.getElementById('cs2cg-output').textContent;
            var blob = new Blob([content], {type: 'text/plain'});
            var url  = URL.createObjectURL(blob);
            var a    = document.createElement('a');
            a.href     = url;
            a.download = 'crosshair.cfg';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        };

        // init
        cs2cg_update();

    })();
    </script>

    <?php
    return ob_get_clean();
}
