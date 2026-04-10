<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-6xl mx-auto px-4 py-8 flex-grow w-full">
    
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

<?php require_once '../app/views/layout/footer.php'; ?>