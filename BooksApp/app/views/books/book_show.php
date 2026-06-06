<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Detail knihy - <?= htmlspecialchars($book['title']) ?></title>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">
    
    <header class="bg-slate-900 text-slate-100 shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-5 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Aplikace Knihovna
            </h1>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8 flex-grow w-full">
        
        <div class="mb-6">
            <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                &larr; Zpět na seznam knih
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-8">
            <div class="border-b border-slate-100 pb-6 mb-6">
                <h2 class="text-3xl font-bold text-slate-900 mb-2"><?= htmlspecialchars($book['title']) ?></h2>
                <p class="text-xl text-cyan-600 font-medium"><?= htmlspecialchars($book['author']) ?></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kategorie</h3>
                        <p class="text-slate-800">
                            <?= htmlspecialchars($book['category_name'] ?? 'Neurčeno') ?>
                            <?= !empty($book['subcategory_name']) ? ' / ' . htmlspecialchars($book['subcategory_name']) : '' ?>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Rok vydání</h3>
                        <p class="text-slate-800"><?= htmlspecialchars($book['year']) ?></p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Cena</h3>
                        <p class="text-2xl font-semibold text-slate-900"><?= htmlspecialchars($book['price']) ?> Kč</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">ISBN</h3>
                        <p class="text-slate-800"><?= htmlspecialchars($book['isbn'] ?? 'Neuvedeno') ?></p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Odkaz / Zdroj</h3>
                        <?php if (!empty($book['link'])): ?>
                            <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank" class="text-indigo-500 hover:text-indigo-700 underline break-all">
                                <?= htmlspecialchars($book['link']) ?>
                            </a>
                        <?php else: ?>
                            <p class="text-slate-500 italic">Odkaz není k dispozici</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Popis knihy</h3>
                <div class="text-slate-600 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($book['description'] ?? 'Bez popisu.') ?></div>
            </div>
            
            <div class="mt-8 flex gap-3">
                <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-colors text-sm">Upravit knihu</a>
            </div>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-6 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm">
            <p>&copy; WA 2026 - Výukový projekt | Tým AG</p>
        </div>
    </footer>
</body>
</html>