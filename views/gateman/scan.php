<?php
/** views/gateman/scan.php */
?>
<div class="mb-6">
    <h1 class="font-display text-2xl font-semibold">Scan QR Gate Pass</h1>
    <p class="text-sm text-ink/55 mt-1">Point the camera at the visitor's gate pass, or type the token manually.</p>
</div>

<?php if ($result): ?>
    <div class="mb-6 px-5 py-4 rounded-xl <?= $result['type'] === 'success' ? 'bg-verigreen/10 border border-verigreen/30 text-verigreen' : 'bg-brick/10 border border-brick/30 text-brick' ?>">
        <p class="font-semibold"><?= e($result['message']) ?></p>
        <?php if ($visit): ?>
            <div class="mt-3 text-sm <?= $result['type'] === 'success' ? 'text-verigreen/90' : 'text-brick/90' ?>">
                <p><strong>Visitor:</strong> <?= e($visit['visitor_name']) ?> (<?= e($visit['visitor_phone']) ?>)</p>
                <p><strong>Host:</strong> <?= e($visit['host_name'] ?? '-') ?></p>
                <p><strong>Location:</strong> <?= e($visit['building_name'] ?? '-') ?><?= $visit['room_name'] ? ' · ' . e($visit['room_name']) : '' ?></p>
                <p><strong>Status:</strong> <?= statusBadge($visit['status']) ?></p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="grid md:grid-cols-2 gap-6">
    <!-- Camera scanner -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-3">Camera scan</h2>
        <div id="qr-reader" class="rounded-lg overflow-hidden bg-ink/5" style="min-height:280px;"></div>
        <p id="qr-reader-status" class="text-xs text-ink/50 mt-3">Initialising camera... Allow camera access when prompted.</p>
    </div>

    <!-- Manual entry -->
    <div class="bg-white rounded-xl border border-ink/10 p-5">
        <h2 class="font-display font-semibold mb-3">Manual token entry</h2>
        <p class="text-sm text-ink/55 mb-4">If the camera isn't available, type the gate pass token printed under the QR code.</p>
        <form method="POST" action="<?= url('/gateman/scan') ?>" id="manualForm" class="space-y-4">
            <?= csrfField() ?>
            <input type="hidden" name="action" id="manualAction" value="checkin">
            <div>
                <label class="block text-sm font-medium mb-1.5">Gate pass token</label>
                <input type="text" name="token" id="tokenInput" required placeholder="e.g. A1F92C77BE..." class="w-full px-4 py-2.5 rounded-lg border border-ink/15 text-sm font-mono">
            </div>
            <div class="flex gap-3">
                <button type="submit" onclick="document.getElementById('manualAction').value='checkin'" class="flex-1 bg-verigreen text-white py-2.5 rounded-lg font-semibold text-sm">Check In</button>
                <button type="submit" onclick="document.getElementById('manualAction').value='checkout'" class="flex-1 bg-ink text-white py-2.5 rounded-lg font-semibold text-sm">Check Out</button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var statusEl = document.getElementById('qr-reader-status');
    if (typeof Html5Qrcode === 'undefined') {
        statusEl.textContent = 'Camera scanner library failed to load. Please use manual entry below.';
        return;
    }

    var scanner = new Html5Qrcode('qr-reader');
    var hasScanned = false;

    function onScanSuccess(decodedText) {
        if (hasScanned) return;
        hasScanned = true;
        statusEl.textContent = 'Gate pass detected. Submitting for check-in...';
        document.getElementById('tokenInput').value = decodedText.trim();
        document.getElementById('manualAction').value = 'checkin';
        document.getElementById('manualForm').submit();
    }

    Html5Qrcode.getCameras().then(function (cameras) {
        if (!cameras || !cameras.length) {
            statusEl.textContent = 'No camera found on this device. Please use manual entry below.';
            return;
        }
        var cameraId = cameras[cameras.length - 1].id; // prefer back camera on most devices
        scanner.start(cameraId, { fps: 10, qrbox: 220 }, onScanSuccess)
            .then(function () { statusEl.textContent = 'Camera active. Point at the visitor\'s QR gate pass.'; })
            .catch(function () { statusEl.textContent = 'Could not start camera. Please use manual entry below.'; });
    }).catch(function () {
        statusEl.textContent = 'Camera access denied or unavailable. Please use manual entry below.';
    });
})();
</script>
