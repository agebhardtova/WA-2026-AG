<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-8 flex-grow w-full">
    
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            &larr; Zpět na seznam alb
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
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Popis alba</h3>
            <div class="text-slate-600 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($book['description'] ?? 'Bez popisu.') ?></div>
        </div>
        
        <div class="mt-8 flex gap-3">
            <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-colors text-sm">Upravit album</a>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm p-8 border border-slate-200">
        <h3 class="text-2xl font-light text-slate-700 mb-6 uppercase tracking-wider border-b pb-3">Komentáře k albu</h3>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="<?= BASE_URL ?>/index.php?url=comment/store" method="post" class="mb-8">
                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">
                <div class="mb-4">
                    <label for="content" class="sr-only">Váš komentář</label>
                    <textarea id="content" name="content" rows="3" required placeholder="Napište svůj názor na toto album..." class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-slate-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-colors"></textarea>
                </div>
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white px-6 py-2 rounded-lg shadow font-medium transition-colors">
                    Přidat komentář
                </button>
            </form>
        <?php else: ?>
            <div class="bg-slate-50 border border-slate-200 p-4 rounded-lg mb-8 text-slate-500">
                Pro přidání komentáře se musíte <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-cyan-600 hover:underline font-medium">přihlásit</a>.
            </div>
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
                                <span class="text-xs text-slate-400 ml-2"><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></span>
                            </div>
                            
                            <?php 
                            // Smazat může pouze autor komentáře nebo administrátor
                            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                            if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $comment['user_id'] || $isAdmin)): 
                            ?>
                                <a href="<?= BASE_URL ?>/index.php?url=comment/delete/<?= $comment['id'] ?>" onclick="return confirm('Opravdu chcete tento komentář smazat?')" class="text-xs text-rose-500 hover:text-rose-700 font-medium bg-rose-50 hover:bg-rose-100 px-2 py-1 rounded transition-colors">Smazat</a>
                            <?php endif; ?>
                        </div>
                        <p class="text-slate-600 whitespace-pre-wrap mt-2"><?= htmlspecialchars($comment['content']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-slate-500 italic">Zatím zde nejsou žádné komentáře. Buďte první!</p>
            <?php endif; ?>
        </div>
    </div>

</main>

<?php require_once '../app/views/layout/footer.php'; ?>