<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="mb-6">
        <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Můj profil</h2>
        <p class="text-slate-500 mt-2">Zde si můžete upravit své osobní údaje.</p>
    </div>

    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-8 max-w-2xl">
        <form action="<?= BASE_URL ?>/index.php?url=user/update" method="post" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-300 mb-1">Uživatelské jméno <span class="text-rose-500">*</span></label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:border-blue-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1">E-mail <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:border-blue-500">
                </div>

                <div>
                    <label for="first_name" class="block text-sm font-medium text-slate-300 mb-1">Jméno</label>
                    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:border-blue-500">
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-slate-300 mb-1">Příjmení</label>
                    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:border-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label for="nickname" class="block text-sm font-medium text-slate-300 mb-1">Přezdívka</label>
                    <input type="text" id="nickname" name="nickname" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:border-blue-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-700">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg font-medium">Uložit změny</button>
            </div>
        </form>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>