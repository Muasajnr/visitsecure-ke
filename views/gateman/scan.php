<?php
/** views/gateman/scan.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Scan gate pass</h1>
    <p class="text-sm text-ink/55 mt-1">Point the camera at the visitor's QR code, or type their token below.</p>
</div>

<?php if ($result): ?>
    <div class="mb-6 px-5 py-4 rounded-xl border <?= $result['type'] === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' ?>">
        <p class="font-semibold <?= $result['type'] === 'success' ? 'text-green-800' : 'text-red-700' ?>"><?= e($result['message']) ?></p>
        <?php if ($visit): ?>
            <div class="mt-3 text-sm <?= $result['type'] === 'success' ? 'text-green-700' : 'text-red-600' ?> space-y-0.5">
                <p><strong>Visitor:</strong> <?= e($visit['visitor_name']) ?> <?= $visit['visitor_phone'] ? '(' . e($visit['visitor_phone']) . ')' : '' ?></p>
                <p><strong>Host:</strong> <?= e($visit['host_name'] ?? '—') ?></p>
                <p><strong>Location:</strong> <?= e($visit['building_name'] ?? '—') ?><?= $visit['room_name'] ? ' · ' . e($visit['room_name']) : '' ?></p>
                <p><strong>Status:</strong> <?= statusBadge($visit['status']) ?></p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="grid md:grid-cols-2 gap-5">
    <!-- Camera -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-3">Camera scan</h2>
        <div id="qr-reader" class="rounded-lg overflow-hidden bg-ink/5" style="min-height:280px;"></div>
        <div class="mt-3 flex flex-wrap items-center gap-3">
            <button type="button" id="qr-start-btn"
                class="inline-flex items-center gap-2 bg-ink text-white px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition">
                <?= icon('camera', 'w-4 h-4') ?>
                <span>Allow camera access</span>
            </button>
            <p id="qr-reader-status" class="text-xs text-ink/45 leading-relaxed flex-1 min-w-[12rem]">
                Click the button above to enable your camera for QR scanning.
            </p>
        </div>
    </div>

    <!-- Upload -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-1">Upload gate pass</h2>
        <p class="text-sm text-ink/55 mb-5 leading-relaxed">Camera unavailable? Upload a clear image or PDF containing the visitor's QR code.</p>
        <label for="qr-file-input" class="block text-sm font-medium mb-1.5 text-ink/75">Gate pass file</label>
        <input type="file" id="qr-file-input" accept="image/*,.pdf,application/pdf" class="block w-full text-sm text-ink/60 file:mr-3 file:rounded-lg file:border-0 file:bg-ink file:px-4 file:py-2.5 file:font-semibold file:text-white hover:file:bg-brick">
        <p id="qr-file-status" class="mt-3 text-xs text-ink/45 leading-relaxed">Supported: JPG, PNG, WEBP, and PDF.</p>
        <div id="qr-file-reader" class="absolute w-px h-px overflow-hidden opacity-0 pointer-events-none"></div>
    </div>

    <!-- Manual -->
    <div class="bg-white rounded-xl border border-ink/10 p-5 md:col-span-2">
        <h2 class="font-display font-semibold mb-1">Manual token entry</h2>
        <p class="text-sm text-ink/55 mb-5 leading-relaxed">Camera not working? Type the token printed below the QR code on the visitor's gate pass.</p>
        <form method="POST" action="<?= url('/gateman/scan') ?>" id="manualForm" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="action" id="manualAction" value="checkin">
            <div>
                <label class="block text-sm font-medium mb-1.5 text-ink/75">Gate pass token</label>
                <input type="text" name="token" id="tokenInput" required
                    placeholder="e.g. A1F9-22DC-77BE"
                    class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm font-mono tracking-wider focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition uppercase">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <button type="submit" onclick="document.getElementById('manualAction').value='checkin'"
                    class="bg-green-700 text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-green-800 transition flex items-center justify-center gap-2">
                    <?= icon('check', 'w-4 h-4') ?> Check in
                </button>
                <button type="submit" onclick="document.getElementById('manualAction').value='checkout'"
                    class="bg-ink text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-brick transition flex items-center justify-center gap-2">
                    <?= icon('arrow-right', 'w-4 h-4') ?> Check out
                </button>
            </div>
        </form>

        <div class="mt-6 pt-5 border-t border-ink/10 text-sm text-ink/50 space-y-1">
            <p class="font-medium text-ink text-xs uppercase tracking-wide mb-2">Quick tips</p>
            <p>· QR codes scan faster in good light.</p>
            <p>· The token is under the QR code — usually 12–16 characters.</p>
            <p>· If status is "pending", the host hasn't approved yet — the visitor shouldn't enter.</p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
(function () {
    var statusEl = document.getElementById('qr-reader-status');
    var startBtn = document.getElementById('qr-start-btn');
    var scanner = null;
    var hasScanned = false;
    var scanConfig = { fps: 10, qrbox: { width: 220, height: 220 }, aspectRatio: 1.0 };
    var fileInput = document.getElementById('qr-file-input');
    var fileStatus = document.getElementById('qr-file-status');

    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    if (typeof Html5Qrcode === 'undefined') {
        statusEl.textContent = 'Camera library failed to load — use manual entry.';
        startBtn.disabled = true;
    }

    function onScanSuccess(decoded) {
        if (hasScanned) return;
        decoded = normalizeDecodedToken(decoded);
        if (!decoded) {
            fileStatus.textContent = 'A QR code was detected, but it did not contain a valid gate-pass token.';
            return;
        }
        hasScanned = true;
        statusEl.textContent = 'Gate pass detected — checking in...';
        fileStatus.textContent = 'QR code found. Checking in...';
        document.getElementById('tokenInput').value = decoded.trim();
        document.getElementById('manualAction').value = 'checkin';
        document.getElementById('manualForm').submit();
    }

    function normalizeDecodedToken(decoded) {
        var value = String(decoded || '').trim();
        try {
            var url = new URL(value, window.location.origin);
            var marker = '/verify/';
            var markerIndex = url.pathname.indexOf(marker);
            if (markerIndex !== -1) {
                return decodeURIComponent(url.pathname.slice(markerIndex + marker.length)).split('/')[0];
            }
        } catch (error) {
            // The QR payload is a raw token rather than a URL.
        }
        return value;
    }

    function onScanError() {
        // Ignore per-frame decode misses.
    }

    function cameraReady() {
        startBtn.classList.add('hidden');
        statusEl.textContent = 'Camera ready. Point at the visitor\'s QR gate pass.';
    }

    function cameraFailed(message) {
        hasScanned = false;
        startBtn.classList.remove('hidden');
        startBtn.disabled = false;
        startBtn.querySelector('span').textContent = 'Try camera again';
        statusEl.textContent = message;
        if (scanner) {
            scanner.stop().catch(function () {}).finally(function () {
                scanner.clear().catch(function () {});
                scanner = null;
            });
        }
    }

    function stopScanner() {
        if (!scanner) return Promise.resolve();
        return scanner.stop().catch(function () {}).then(function () {
            return scanner.clear().catch(function () {});
        }).then(function () {
            scanner = null;
        });
    }

    function startWithFacingMode() {
        return scanner.start({ facingMode: 'environment' }, scanConfig, onScanSuccess, onScanError);
    }

    function startWithDeviceList() {
        return Html5Qrcode.getCameras().then(function (cameras) {
            if (!cameras || !cameras.length) {
                throw new Error('No camera found on this device.');
            }
            var rear = cameras.find(function (c) {
                return /back|rear|environment/i.test(c.label);
            });
            var cameraId = rear ? rear.id : cameras[cameras.length - 1].id;
            return scanner.start(cameraId, scanConfig, onScanSuccess, onScanError);
        });
    }

    function requestCameraAccess() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            return Promise.reject(new Error('Camera API not supported in this browser.'));
        }
        return navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } } })
            .then(function (stream) {
                stream.getTracks().forEach(function (track) { track.stop(); });
            });
    }

    function startScanner() {
        startBtn.disabled = true;
        statusEl.textContent = 'Requesting camera permission — allow access in your browser prompt...';

        requestCameraAccess()
            .catch(function () {
                // Some browsers still allow Html5Qrcode to prompt on start().
            })
            .then(function () {
                return stopScanner();
            })
            .then(function () {
                scanner = new Html5Qrcode('qr-reader');
                return startWithFacingMode().catch(function () {
                    return stopScanner().then(function () {
                        scanner = new Html5Qrcode('qr-reader');
                        return startWithDeviceList();
                    });
                });
            })
            .then(cameraReady)
            .catch(function (err) {
                var msg = 'Could not access the camera.';
                if (window.isSecureContext === false && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                    msg = 'Camera access requires HTTPS. Open this site over HTTPS, or use the image/PDF upload option below.';
                } else if (err && err.name === 'NotAllowedError') {
                    msg = 'Camera permission was denied. Click "Try camera again" and choose Allow in the browser prompt, or use manual entry below.';
                } else if (err && err.name === 'NotFoundError') {
                    msg = 'No camera found on this device. Use manual entry below.';
                } else if (err && err.message) {
                    msg = err.message + ' Use manual entry below, or click to try again.';
                } else {
                    msg += ' Use manual entry below, or click to try again.';
                }
                cameraFailed(msg);
            });
    }

    function scanImageFile(file) {
        if (typeof Html5Qrcode === 'undefined') {
            return Promise.reject(new Error('QR scanning library failed to load. Refresh the page and try again.'));
        }
        fileStatus.textContent = 'Reading QR code from the uploaded image...';
        return stopScanner().then(function () {
            var fileScanner = new Html5Qrcode('qr-file-reader');
            return fileScanner.scanFile(file, true).then(function (decoded) {
                fileScanner.clear().catch(function () {});
                onScanSuccess(decoded);
            }).catch(function (error) {
                fileScanner.clear().catch(function () {});
                throw error;
            });
        });
    }

    function scanPdf(file) {
        if (typeof pdfjsLib === 'undefined') {
            return Promise.reject(new Error('PDF support could not be loaded.'));
        }
        return file.arrayBuffer().then(function (buffer) {
            return pdfjsLib.getDocument({ data: buffer }).promise;
        }).then(function (pdf) {
            var pageNumber = 1;
            var renderScales = [2, 3, 4];

            function nextPage() {
                if (pageNumber > pdf.numPages) {
                    throw new Error('No QR code was found in this PDF.');
                }
                fileStatus.textContent = 'Checking PDF page ' + pageNumber + ' of ' + pdf.numPages + '...';
                var currentPage = pageNumber++;
                return pdf.getPage(currentPage).then(function (page) {
                    function tryScale(scaleIndex) {
                        if (scaleIndex >= renderScales.length) return nextPage();
                        var viewport = page.getViewport({ scale: renderScales[scaleIndex] });
                        fileStatus.textContent = 'Checking PDF page ' + currentPage + ' at scan quality ' + (scaleIndex + 1) + ' of ' + renderScales.length + '...';
                    var canvas = document.createElement('canvas');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    return page.render({ canvasContext: canvas.getContext('2d'), viewport: viewport }).promise.then(function () {
                        return new Promise(function (resolve) {
                            canvas.toBlob(function (blob) {
                                if (!blob) {
                                    resolve(null);
                                    return;
                                }
                                resolve(new File([blob], 'pdf-page-' + currentPage + '-' + scaleIndex + '.png', { type: 'image/png' }));
                            }, 'image/png');
                        });
                    }).then(function (blob) {
                        if (!blob) return tryScale(scaleIndex + 1);
                        return scanImageFile(blob).then(resolveScan).catch(function () {
                            return tryScale(scaleIndex + 1);
                        });
                    });
                    }
                    return tryScale(0);
                });
            }

            function resolveScan(result) { return result; }
            return nextPage();
        });
    }

    fileInput.addEventListener('change', function () {
        var file = fileInput.files[0];
        if (!file) return;
        hasScanned = false;
        var promise = file.type === 'application/pdf' || file.name.toLowerCase().slice(-4) === '.pdf'
            ? scanPdf(file)
            : scanImageFile(file);
        promise.then(function () {
            fileStatus.textContent = 'QR code found. Checking in...';
        }).catch(function (error) {
            fileStatus.textContent = (error && error.message ? error.message : 'Could not read a QR code from that file.') + ' Try a clearer file or use manual entry.';
        });
    });

    if (typeof Html5Qrcode !== 'undefined') {
        startBtn.addEventListener('click', startScanner);
    }
})();
</script>
