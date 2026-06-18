<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' - ' . APP_NAME : APP_NAME ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          ink:   '#0F1B2D',
          brick: '#B3492B',
          paper: '#F7F5F0',
          verigreen: '#1F7A53',
        },
        fontFamily: {
          display: ['Space Grotesk', 'sans-serif'],
          sans: ['Inter', 'sans-serif'],
          mono: ['JetBrains Mono', 'monospace'],
        }
      }
    }
  }
</script>
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body class="bg-paper font-sans text-ink antialiased">

<div class="flex min-h-screen">
    <?php require VIEWS_PATH . '/partials/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0">
        <?php require VIEWS_PATH . '/partials/topbar.php'; ?>

        <main class="flex-1 p-4 md:p-6">
            <?php if ($msg = getFlash('success')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-verigreen/10 border border-verigreen/30 text-verigreen text-sm font-medium"><?= e($msg) ?></div>
            <?php endif; ?>
            <?php if ($msg = getFlash('error')): ?>
                <div class="mb-4 px-4 py-3 rounded-lg bg-brick/10 border border-brick/30 text-brick text-sm font-medium"><?= e($msg) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </main>

        <?php require VIEWS_PATH . '/partials/footer.php'; ?>
    </div>
</div>

<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
