<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    
    <div class="mb-6">
        <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Přidat novou knihu</h2>
        <p class="text-slate-500 mt-2">Vyplňte údaje a uložte knihu do databáze.</p>
    </div>
    
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden shadow-2xl backdrop-blur-sm p-8">
        
        <form action="<?= BASE_URL ?>/index.php?url=book/store" method="post" enctype="multipart/form-data" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-1">Název knihy <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" name="title" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="author" class="block text-sm font-medium text-slate-300 mb-1">Autor <span class="text-rose-500">*</span></label>
                    <input type="text" id="author" name="author" placeholder="Příjmení Jméno" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="isbn" class="block text-sm font-medium text-slate-300 mb-1">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-slate-300 mb-1">Rok vydání <span class="text-rose-500">*</span></label>
                    <input type="number" id="year" name="year" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
                
                <!-- Kategorie (Roletka z DB) -->
                <div>
                     <label for="category" class="block text-sm font-medium text-slate-300 mb-1">Kategorie <span class="text-rose-500">*</span></label>
                     <select id="category" name="category" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                        <option value="">-- Vyberte kategorii --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <!-- Podkategorie (Roletka z DB) -->
                <div>
                    <label for="subcategory" class="block text-sm font-medium text-slate-300 mb-1">Podkategorie</label>
                    <select id="subcategory" name="subcategory" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                        <option value="">-- Vyberte podkategorii --</option>
                        <?php if (!empty($subcategories)): ?>
                            <?php foreach ($subcategories as $subcat): ?>
                                <option value="<?= htmlspecialchars($subcat['id']) ?>">
                                    <?= htmlspecialchars($subcat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-slate-300 mb-1">Cena knihy (Kč)</label>
                    <input type="number" id="price" name="price" step="0.5" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="link" class="block text-sm font-medium text-slate-300 mb-1">Odkaz / Zdroj</label>
                    <input type="text" id="link" name="link" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-1">Popis knihy</label>
                <textarea id="description" name="description" rows="4" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500"></textarea>
            </div>    
            
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Obrázky knihy</label>
                <div class="w-full">
                    <label for="images" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-600 border-dashed rounded-lg cursor-pointer bg-slate-800/30 hover:bg-slate-700/50 hover:border-blue-400 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <span id="file-title" class="text-sm text-slate-400 font-semibold">Klikni pro výběr souborů</span>
                            <span id="file-info" class="text-xs text-slate-500 mt-1 text-center px-4">Žádné soubory nebyly vybrány</span>
                        </div>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-700">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-medium py-3 px-8 rounded-lg shadow-lg transition-colors border border-blue-500">
                    Uložit knihu do DB
                </button>
            </div>
            
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('images');
        const fileTitle = document.getElementById('file-title');
        const fileInfo = document.getElementById('file-info');

        fileInput.addEventListener('change', function(event) {
            const files = event.target.files;
            
            if (files.length === 0) {
                fileTitle.textContent = 'Klikněte pro výběr souborů';
                fileTitle.className = 'text-sm text-slate-400 font-semibold';
                fileInfo.textContent = 'Žádné soubory nebyly vybrány';
            } else if (files.length === 1) {
                fileTitle.textContent = 'Soubor připraven';
                fileTitle.className = 'text-sm text-blue-400 font-bold'; 
                fileInfo.textContent = files[0].name;
            } else {
                fileTitle.textContent = 'Soubory připraveny';
                fileTitle.className = 'text-sm text-blue-400 font-bold'; 
                fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
            }
        });
    </script>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>