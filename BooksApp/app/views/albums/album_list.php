<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-6xl mx-auto px-4 py-8 flex-grow w-full">
    
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dostupné alba</h2>
    </div>
    
    <?php if (empty($albums)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-slate-300 p-10 text-center">
            <p class="text-slate-500 text-lg">V databázi se zatím nenachází žádné album.</p>
            <a href="<?= BASE_URL ?>/index.php?url=album/create" class="inline-block mt-4 text-slate-600 hover:text-black font-bold underline transition-colors">
                + Přidat první album
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-sm border border-slate-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b-2 border-slate-800 text-slate-700 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-bold">ID</th>
                            <th class="px-6 py-4 font-bold">Název alba</th>
                            <th class="px-6 py-4 font-bold">Autor</th>
                            <th class="px-6 py-4 font-bold">Kategorie</th>
                            <th class="px-6 py-4 font-bold">Rok</th>
                            <th class="px-6 py-4 font-bold">Cena</th>
                            <th class="px-6 py-4 font-bold text-right">Akce</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        <?php foreach ($albums as $album): ?>
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4 text-slate-400 font-medium">#<?= htmlspecialchars($album['id']) ?></td>
                                <td class="px-6 py-4 font-bold text-slate-900"><?= htmlspecialchars($album['title']) ?></td>
                                <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($album['author']) ?></td>
                                <td class="px-6 py-4 text-emerald-600 font-medium">
                                    <?= htmlspecialchars($album['category_name'] ?? 'Nezařazeno') ?>
                                </td>

                                <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($album['year']) ?></td>
                                <td class="px-6 py-4 text-slate-900 font-semibold"><?= htmlspecialchars($album['price']) ?> Kč</td>
                                
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="<?= BASE_URL ?>/index.php?url=album/show/<?= $album['id'] ?>" class="text-slate-400 hover:text-black font-semibold transition-colors">Detail</a>
                                    
                                    <?php 
                                    $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                                    
                                    if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $album['created_by'] || $isAdmin)): 
                                    ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=album/edit/<?= $album['id'] ?>" class="text-slate-400 hover:text-black font-semibold transition-colors">Upravit</a>
                                        <a href="<?= BASE_URL ?>/index.php?url=album/delete/<?= $album['id'] ?>" onclick="return confirm('Opravdu chcete album smazat?')" class="text-slate-800 hover:text-black font-bold underline transition-colors">Smazat</a>
                                    <?php endif; ?>

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