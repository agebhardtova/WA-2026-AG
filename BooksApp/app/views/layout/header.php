<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Knihovna - Výuková aplikace</title>
</head>
<body class="bg-[#1a1c1e] text-slate-200 min-h-screen font-sans flex flex-col">

    <header class="bg-gradient-to-b from-slate-700 to-slate-900 border-b border-slate-600 shadow-xl">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight text-white uppercase italic">
                Aplikace <span class="text-blue-400">Knihovna</span>
            </h1>
            
            <nav class="mt-4 md:mt-0">
                <ul class="flex space-x-6">
                    <li>
                        <a href="<?= BASE_URL ?>/index.php" class="hover:text-blue-400 transition-colors font-medium">Seznam knih</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=book/create" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md transition-all shadow-inner border border-blue-500">
                            + Přidat knihu
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-6 pt-8">
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <div class="space-y-3">
                <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                    <?php 
                        $styles = [
                            'success' => 'bg-emerald-900/30 border-emerald-500 text-emerald-400',
                            'error'   => 'bg-rose-900/30 border-rose-500 text-rose-400',
                            'notice'  => 'bg-amber-900/30 border-amber-500 text-amber-400',
                        ];
                        $style = $styles[$type] ?? 'bg-slate-800 border-slate-500 text-slate-300';
                    ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="<?= $style ?> border-l-4 p-4 rounded-r-lg shadow-md animate-fade-in">
                            <p class="font-semibold text-sm italic"><?= htmlspecialchars($message) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']); ?>
            </div>
        <?php endif; ?>
    </div>