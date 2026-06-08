<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 py-10 flex-grow flex items-center justify-center">
    <div class="w-full max-w-lg">
        <div class="mb-6">
            <a href="<?= BASE_URL ?>/index.php?url=album/show/<?= $comment['album_id'] ?>" class="text-blue-400 hover:text-blue-300 flex items-center text-sm font-medium transition-colors">
                &larr; Zpět na detail alba
            </a>
        </div>
        
        <div class="bg-slate-800/50 border border-slate-700 rounded-xl shadow-2xl backdrop-blur-sm p-6 md:p-8">
            <h2 class="text-2xl font-light tracking-widest text-slate-300 uppercase mb-4">Upravit komentář</h2>
            
            <form action="<?= BASE_URL ?>/index.php?url=comment/update/<?= htmlspecialchars($comment['id']) ?>" method="post">
                <div class="space-y-4">
                    <div>
                        <textarea name="content" rows="4" required class="w-full bg-slate-900/50 border border-slate-600 rounded-md px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"><?= htmlspecialchars($comment['content']) ?></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-md shadow-lg border border-blue-500 transition-colors uppercase tracking-wider text-sm">
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>