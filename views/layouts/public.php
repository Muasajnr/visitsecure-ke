<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' - ' . APP_NAME : APP_NAME . ' - ' . APP_TAGLINE ?></title>
<meta name="description" content="<?= APP_TAGLINE ?>">
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
    <?= $content ?>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
