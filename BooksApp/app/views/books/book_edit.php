<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    
    <div class="mb-4">
        <a href="<?= BASE_URL ?>/index.php" class="text-blue-400 hover:text-blue-300 flex items-center text-sm font-medium transition-colors">
            &larr; Zpět na seznam knih
        </a>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Upravit knihu</h2>
        <p class="text-slate-500 mt-2">Změňte požadované údaje pro knihu: <strong class="text-slate-300"><?= htmlspecialchars($book['title']) ?></strong></p>
    </div>
    
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden shadow-2xl backdrop-blur-sm p-8">
        
        <form action="<?= BASE_URL ?>/index.php?url=book/update/<?= htmlspecialchars($book['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="id_display" class="block text-sm font-medium text-slate-400 mb-1">ID v databázi</label>
                    <input type="text" id="id_display" value="<?= htmlspecialchars($book['id']) ?>" readonly class="w-full bg-slate-900/50 border border-slate-700 rounded-lg px-4 py-2 text-slate-500 cursor-not-allowed">
                </div>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-1">Název knihy <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
                
                <div>
                    <label for="author" class="block text-sm font-medium text-slate-300 mb-1">Autor <span class="text-rose-500">*</span></label>
                    <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
                
                <div>
                    <label for="isbn" class="block text-sm font-medium text-slate-300 mb-1">ISBN</label>
                    <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-slate-300 mb-1">Rok vydání <span class="text-rose-500">*</span></label>
                    <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year']) ?>" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>

                <!-- Kategorie (Editační roletka s předvybranou hodnotou) -->
                <div>
                     <label for="category" class="block text-sm font-medium text-slate-300 mb-1">Kategorie <span class="text-rose-500">*</span></label>
                     <select id="category" name="category" required class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                        <option value="">-- Vyberte kategorii --</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <?php $isSelected = (isset($book['category']) && $book['category'] == $cat['id']) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>" <?= $isSelected ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <!-- Podkategorie (Editační roletka s předvybranou hodnotou) -->
                <div>
                    <label for="subcategory" class="block text-sm font-medium text-slate-300 mb-1">Podkategorie</label>
                    <select id="subcategory" name="subcategory" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                        <option value="">-- Vyberte podkategorii --</option>
                        <?php if (!empty($subcategories)): ?>
                            <?php foreach ($subcategories as $subcat): ?>
                                <?php $isSelected = (isset($book['subcategory']) && $book['subcategory'] == $subcat['id']) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($subcat['id']) ?>" <?= $isSelected ?>>
                                    <?= htmlspecialchars($subcat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-slate-300 mb-1">Cena knihy (Kč)</label>
                    <input type="number" id="price" name="price" step="0.5" value="<?= htmlspecialchars($book['price'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label for="link" class="block text-sm font-medium text-slate-300 mb-1">Odkaz / Zdroj</label>
                    <input type="text" id="link" name="link" value="<?= htmlspecialchars($book['link'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-1">Popis knihy</label>
                <textarea id="description" name="description" rows="4" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:outline-none focus:border-blue-500"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
            </div>    
            
            <?php 
                $existingImages = json_decode($book['images'] ?? '[]', true); 
                if (!empty($existingImages) && is_array($existingImages)): 
            ?>
            <div class="md:col-span-2 bg-slate-900/50 p-5 rounded-lg border border-slate-700 mb-2">
                <label class="block text-xs font-semibold text-slate-400 mb-3 uppercase tracking-wider">Aktuálně nahraný obrázek</label>
                <div class="flex gap-4 flex-wrap">
                    <?php foreach($existingImages as $img): ?>
                        <div class="relative group">
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" alt="Náhled" class="h-40 w-auto object-cover rounded shadow-md border-2 border-slate-600">
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs text-emerald-500/80 mt-3 font-medium">Pokud níže nahrajete nové soubory, tento původní se nahradí.</p>
            </div>
            <?php endif; ?>
            
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Obrázky knihy</label>
                <div class="w-full">
                    <label for="images" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-600 border-dashed rounded-lg cursor-pointer bg-slate-800/30 hover:bg-slate-700/50 hover:border-emerald-400 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <span id="file-title" class="text-sm text-slate-400 font-semibold">Klikni pro výběr souborů</span>
                            <span id="file-info" class="text-xs text-slate-500 mt-1 text-center px-4">Žádné soubory nebyly vybrány</span>
                         </div>
                         <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                     </label>
                 </div>
            </div>

            <div class="pt-4 border-t border-slate-700 flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-medium py-3 px-8 rounded-lg shadow-lg transition-colors border border-blue-500">
                    Uložit změny do DB
                </button>
            </div>
            
        </form>
    </div>
</main>

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
            fileTitle.className = 'text-sm text-emerald-400 font-bold';
            fileInfo.textContent = files[0].name;
        } else {
            fileTitle.textContent = 'Soubory připraveny';
            fileTitle.className = 'text-sm text-emerald-400 font-bold';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
        }
    });
</script>

<?php require_once '../app/views/layout/footer.php'; ?>