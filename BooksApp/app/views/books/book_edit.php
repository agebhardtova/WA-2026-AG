<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <title>Upravit knihu</title>
</head>
<body>
    <div>
        <p>
            <a href="<?= BASE_URL ?>/index.php">&larr; Zpět na seznam knih</a>
        </p>

        <div>
            <h2>Upravit knihu (ID záznamu: <?= htmlspecialchars($book['id']) ?>)</h2>
            <p>Upravujete data pro knihu: <strong><?= htmlspecialchars($book['title']) ?></strong></p>
            <p>Změňte požadované údaje a uložte formulář.</p>
        </div>
        
        <div>
            <form action="<?= BASE_URL ?>/index.php?url=book/update/<?= htmlspecialchars($book['id']) ?>" method="post" enctype="multipart/form-data">
                <div>
                    <div>
                        <label for="id_display">ID v databázi</label>
                        <input type="text" id="id_display" value="<?= htmlspecialchars($book['id']) ?>" readonly>
                    </div>
                    <div>
                        <label for="title">Název knihy <span>*</span></label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    <div>
                        <label for="author">Autor <span>*</span></label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                    </div>
                    <div>
                        <label for="isbn">ISBN <span>*</span></label>
                        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>">
                    </div>
                    <div>
                        <label for="category">Kategorie </label>
                        <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category']) ?>">
                    </div>
                    <div>
                        <label for="subcategory">Podkategorie </label>
                        <input type="text" id="subcategory" name="subcategory" value="<?= htmlspecialchars($book['subcategory']) ?>">
                    </div>
                    <div>
                        <label for="year">Rok vydání  <span>*</span></label>
                        <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year']) ?>" required>
                    </div>
                    <div>
                        <label for="price">Cena knihy</label>
                        <input type="number" id="price" name="price" step="0.5" value="<?= htmlspecialchars($book['price']) ?>">
                    </div>
                    <div>
                        <label for="link">Odkaz</label>
                        <input type="text" id="link" name="link" value="<?= htmlspecialchars($book['link']) ?>">
                    </div>
                    <div>
                        <label for="description">Popis knihy</label>
                        <textarea id="description" name="description" rows="5"><?= htmlspecialchars($book['description']) ?></textarea>
                    </div>    
                    <div>
                        <label>Obrázky (zatím neřešíme, můžete ignorovat)</label>
                        <label>
                            <input type="file" id="images" name="images[]" multiple accept="image/*">
                        </label>
                    </div>
                    <div>
                        <button type="submit">Uložit změny do DB</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>