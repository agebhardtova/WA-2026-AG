<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="mb-6">
        <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Správa uživatelů</h2>
        <p class="text-slate-500 mt-2">Seznam všech registrovaných uživatelů. Tuto stránku vidí pouze administrátor.</p>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-800 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Uživatelské jméno</th>
                        <th class="px-6 py-4">E-mail</th>
                        <th class="px-6 py-4">Jméno a Příjmení</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Akce</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($users)): foreach ($users as $u): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-400">#<?= htmlspecialchars($u['id']) ?></td>
                            <td class="px-6 py-4 font-medium text-slate-900"><?= htmlspecialchars($u['username']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($u['is_admin'] == 1): ?>
                                    <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded text-xs font-bold">Admin</span>
                                <?php else: ?>
                                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-medium">Uživatel</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <a href="<?= BASE_URL ?>/index.php?url=user/delete/<?= $u['id'] ?>" onclick="return confirm('Opravdu smazat uživatele <?= htmlspecialchars($u['username']) ?>?')" class="text-rose-500 hover:text-rose-700 font-semibold underline">Smazat</a>
                                <?php else: ?>
                                    <span class="text-slate-300 italic">Váš účet</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500">Žádní uživatelé nenalezeni.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>