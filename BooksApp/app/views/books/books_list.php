<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knihovna - Seznam knih</title>
</head>
<body>
    <header>
        <h1>Aplikace Knihovna</h1>
        
        <nav>
            <ul>
                <li><a href="<?= BASE_URL ?>/index.php">Seznam knih (Domů)</a></li>
                <li><a href="<?= BASE_URL ?>/index.php?url=book/create">Přidat novou knihu</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Dostupné knihy</h2>
        
        <?php if (empty($books)): ?>
            <p>V databázi se zatím nenachází žádné knihy.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Název knihy</th>
                        <th>Autor</th>
                        <th>Rok vydání</th>
                        <th>Cena</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['id']) ?></td>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= htmlspecialchars($book['year']) ?></td>
                            <td><?= htmlspecialchars($book['price']) ?> Kč</td>
                            <td>
                                <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; WA 2026 - Výukový projekt</p>
    </footer>
</body>
</html>