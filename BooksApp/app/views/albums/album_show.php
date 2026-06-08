<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-8 flex-grow w-full">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            &larr; Zpět na seznam alb
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-8">
        <div class="border-b border-slate-100 pb-6 mb-6">
            <h2 class="text-3xl font-bold text-slate-900 mb-2"><?= htmlspecialchars($album['title']) ?></h2>
            <p class="text-xl text-cyan-600 font-medium"><?= htmlspecialchars($album['interpret']) ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kategorie</h3>
                    <p class="text-slate-800">
                        <?= htmlspecialchars($album['category_name'] ?? 'Neurčeno') ?>
                        <?= !empty($album['subcategory_name']) ? ' / ' . htmlspecialchars($album['subcategory_name']) : '' ?>
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Rok vydání</h3>
                    <p class="text-slate-800"><?= htmlspecialchars($album['year']) ?></p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Cena</h3>
                    <p class="text-2xl font-semibold text-slate-900"><?= htmlspecialchars($album['price']) ?> Kč</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Katalogové číslo</h3>
                    <p class="text-slate-800"><?= htmlspecialchars($album['catalog_number'] ?? 'Neuvedeno') ?></p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Odkaz</h3>
                    <?php if (!empty($album['link'])): ?>
                        <a href="<?= htmlspecialchars($album['link']) ?>" target="_blank" class="text-indigo-500 hover:text-indigo-700 underline"><?= htmlspecialchars($album['link']) ?></a>
                    <?php else: ?>
                        <p class="text-slate-500 italic">Odkaz není k dispozici</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Popis</h3>
            <div class="text-slate-600 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($album['description'] ?? 'Bez popisu.') ?></div>
        </div>
        
        <div class="mt-8 flex gap-3">
            <a href="<?= BASE_URL ?>/index.php?url=album/edit/<?= $album['id'] ?>" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-colors text-sm">Upravit</a>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm p-8 border border-slate-200">
        <h3 class="text-2xl font-light text-slate-700 mb-6 uppercase tracking-wider border-b pb-3">Komentáře</h3>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="<?= BASE_URL ?>/index.php?url=comment/store" method="post" class="mb-8">
                <input type="hidden" name="album_id" value="<?= htmlspecialchars($album['id']) ?>">
                <textarea name="content" rows="3" required placeholder="Napište svůj názor..." class="w-full bg-slate-50 border border-slate-300 rounded-lg p-3"></textarea>
                <button type="submit" class="mt-2 bg-cyan-600 text-white px-6 py-2 rounded-lg">Přidat komentář</button>
            </form>
        <?php endif; ?>

        <div class="space-y-6">
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-200">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="font-bold text-slate-800">
                                    <?= htmlspecialchars(!empty($comment['first_name']) ? $comment['first_name'] . ' ' . $comment['last_name'] : $comment['username']) ?>
                                </span>
                            </div>
                            <?php 
                            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                            if (isset($_SESSION['user_id'])): 
                            ?>
                                <div class="flex gap-3">
                                    <?php if ($_SESSION['user_id'] == $comment['user_id']): ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=comment/edit/<?= $comment['id'] ?>" class="text-xs text-blue-500 hover:text-blue-700 font-medium">Upravit</a>
                                    <?php endif; ?>
                                    
                                    <?php if ($_SESSION['user_id'] == $comment['user_id'] || $isAdmin): ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=comment/delete/<?= $comment['id'] ?>" onclick="return confirm('Opravdu chcete tento komentář smazat?')" class="text-xs text-rose-500 hover:text-rose-700 font-medium">Smazat</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="text-slate-600 mt-1 whitespace-pre-wrap"><?= htmlspecialchars($comment['content']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-500 italic">Zatím zde nejsou žádné komentáře.</p>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php require_once '../app/views/layout/footer.php'; ?>