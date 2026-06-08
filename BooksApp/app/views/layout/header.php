<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>albovna - Výuková aplikace</title>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen font-sans flex flex-col">

    <header class="bg-white border-b-2 border-slate-800 shadow-sm">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center">
                <img src="<?= BASE_URL ?>/images/logo.png" alt="Logo" class="h-14 w-auto">
            </a>
            
           <nav class="mt-4 md:mt-0">
                <ul class="flex items-center space-x-6">
                    <li>
                        <a href="<?= BASE_URL ?>/index.php" class="hover:text-blue-400 transition-colors font-medium">Seznam alb</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <li>
                                <a href="<?= BASE_URL ?>/index.php?url=user/index" class="text-emerald-500 hover:text-emerald-400 transition-colors font-medium text-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                    Správa uživatelů
                                </a>
                            </li>
                        <?php endif; ?>

                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=album/create" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md transition-all shadow-inner border border-blue-500">
                                + Přidat album
                            </a>
                        </li>
                        
                        <li class="text-slate-500 text-sm flex items-center gap-3">
                            <span>Ahoj, <span class="text-slate-800 font-semibold tracking-wide"><?= htmlspecialchars($_SESSION['user_name']) ?></span></span>
                            <a href="<?= BASE_URL ?>/index.php?url=user/profile" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1 rounded border border-slate-300 transition-colors shadow-sm">
                                Můj profil
                            </a>
                        </li>

                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="text-rose-500 hover:text-rose-400 transition-colors text-sm uppercase tracking-wider font-medium">
                                Odhlásit
                            </a>
                        </li>

                    <?php else: ?>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="hover:text-blue-400 transition-colors font-medium">Přihlásit</a>
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-md transition-all shadow-inner border border-slate-600">
                                Registrace
                            </a>
                        </li>
                    <?php endif; ?>
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
                            'success' => 'bg-white border-slate-800 text-slate-800',
                            'error'   => 'bg-slate-200 border-black text-black font-bold',
                            'notice'  => 'bg-slate-50 border-slate-500 text-slate-600',
                        ];
                        $style = $styles[$type] ?? 'bg-white border-slate-500 text-slate-700';
                    ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="<?= $style ?> border-l-4 p-4 pr-12 rounded-r-lg shadow-sm animate-fade-in relative flex items-center">
                            <p class="text-sm italic"><?= htmlspecialchars($message) ?></p>
                            <button type="button" onclick="this.parentElement.style.display='none'" class="absolute right-0 top-0 bottom-0 px-4 text-2xl font-bold opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
                                &times;
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']); ?>
            </div>
        <?php endif; ?>
    </div>