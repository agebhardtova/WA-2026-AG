<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Knihovna - Seznam knih</title>
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
            
            <nav>
                <ul class="flex space-x-6 text-sm font-medium">
                    <li>
                        <a href="<?= BASE_URL ?>/index.php" class="text-cyan-400 hover:text-cyan-300 transition-colors border-b-2 border-cyan-400 pb-1">Seznam knih</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=book/create" class="text-slate-300 hover:text-white transition-colors">Přidat novou knihu</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 flex-grow w-full">
        
        <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
            <div class="mb-8 space-y-3">
                <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                    <?php 
                        // Chladné a moderní barvy pro notifikace pomocí Tailwind tříd
                        $alertClass = 'bg-slate-100 border-slate-300 text-slate-800';
                        if ($type === 'success') $alertClass = 'bg-teal-50 border-teal-200 text-teal-800';
                        if ($type === 'error') $alertClass = 'bg-rose-50 border-rose-200 text-rose-800';
                        if ($type === 'notice') $alertClass = 'bg-sky-50 border-sky-200 text-sky-800';
                    ?>
                    
                    <?php foreach ($messages as $message): ?>
                        <div class="border rounded-lg px-4 py-3 shadow-sm <?= $alertClass ?>" role="alert">
                            <span class="block sm:inline font-medium"><?= htmlspecialchars($message) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['messages']); ?>
        <?php endif; ?>

        <div class="flex justify-between items-end mb-6">
            <h2 class="text-2xl font-semibold text-slate-800">Dostupné knihy</h2>
        </div>
        
        <?php if (empty($books)): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
                <p class="text-slate-500 text-lg">V databázi se zatím nenachází žádné knihy.</p>
                <a href="<?= BASE_URL ?>/index.php?url=book/create" class="inline-block mt-4 text-cyan-600 hover:text-cyan-800 font-medium">
                    + Přidat první knihu
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">ID</th>
                                <th class="px-6 py-4 font-semibold">Název knihy</th>
                                <th class="px-6 py-4 font-semibold">Autor</th>
                                <th class="px-6 py-4 font-semibold">Rok</th>
                                <th class="px-6 py-4 font-semibold">Cena</th>
                                <th class="px-6 py-4 font-semibold text-right">Akce</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php foreach ($books as $book): ?>
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4 text-slate-500">#<?= htmlspecialchars($book['id']) ?></td>
                                    <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($book['title']) ?></td>
                                    <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($book['author']) ?></td>
                                    <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($book['year']) ?></td>
                                    <td class="px-6 py-4 text-slate-900 font-medium"><?= htmlspecialchars($book['price']) ?> Kč</td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>" class="text-indigo-500 hover:text-indigo-700 font-medium transition-colors">Detail</a>
                                        <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="text-sky-500 hover:text-sky-700 font-medium transition-colors">Upravit</a>
                                        <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" onclick="return confirm('Opravdu chcete knihu smazat?')" class="text-rose-500 hover:text-rose-700 font-medium transition-colors">Smazat</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-6 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm">
            <p>&copy; WA 2026 - Výukový projekt | Tým AG</p>
        </div>
    </footer>
</body>
</html>