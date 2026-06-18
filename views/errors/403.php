<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>403 - Access denied</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F7F5F0] text-[#0F1B2D] min-h-screen flex items-center justify-center px-5">
    <div class="text-center">
        <p class="text-6xl font-bold mb-2">403</p>
        <h1 class="text-xl font-semibold mb-2">Access denied</h1>
        <p class="text-[#0F1B2D]/55 mb-6">You don't have permission to view this page with your current role.</p>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>" class="inline-block bg-[#0F1B2D] text-white px-5 py-2.5 rounded-lg font-semibold text-sm">Go back home</a>
    </div>
</body>
</html>
