<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>404 - Page not found</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#EFF6FF] text-[#1E3A5F] min-h-screen flex items-center justify-center px-5">
    <div class="text-center">
        <p class="text-6xl font-bold mb-2">404</p>
        <h1 class="text-xl font-semibold mb-2">Page not found</h1>
        <p class="text-[#1E3A5F]/55 mb-6">The page you're looking for doesn't exist or may have moved.</p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>" class="inline-block bg-[#1E3A5F] text-white px-5 py-2.5 rounded-lg font-semibold text-sm">Go back home</a>
    </div>
</body>
</html>
