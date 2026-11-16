
<?php

require_once __DIR__ . '/config/config.php';

$errors = [];
$name = '';
$description = '';
$priority = '3';

// Hantera formulär
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Radera en sak
    if (isset($_POST['delete_id'])) {

        $id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);

        if ($id !== false && $id > 0) {
            $bucketlist->delete($id);

            header('Location: bucketlist.php?deleted=1');
            exit;
        } else {
            $errors[] = "Ogiltigt ID.";
        }
    }

    // Lägg till en sak
    else {

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priority = $_POST['priority'] ?? '';

        // Validering av namn
        if ($name === '') {
            $errors[] = "Du måste ange ett namn.";
        }

        // Validering av beskrivning
        if ($description === '') {
            $errors[] = "Du måste ange en beskrivning.";
        }

        // Validering av prioritet
        $priorityValue = filter_var($priority, FILTER_VALIDATE_INT);

        if (
            $priorityValue === false ||
            $priorityValue < 1 ||
            $priorityValue > 5
        ) {
            $errors[] = "Prioriteten måste vara mellan 1 och 5.";
        }

        // Spara i databasen om formuläret är korrekt
        if (empty($errors)) {

            $bucketlist->add(
                $name,
                $description,
                $priorityValue
            );

            header('Location: bucketlist.php?added=1');
            exit;
        }
    }
}

// Hämta alla saker från databasen
$items = $bucketlist->getAll();

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Min Bucketlist</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header>
        <h1>Min Bucketlist</h1>
        <p>Här samlar jag mina drömmar och mål</p>
    </header>

    <nav>
        <a href="index.php">Hem</a>
        <a href="bucketlist.php">Min Bucketlist</a>
        <a href="ai.php">AI & Utveckling</a>
    </nav>

    <main>

        <h2>Lägg till en sak</h2>

        <!-- Meddelande när en sak lagts till -->
        <?php if (isset($_GET['added'])): ?>
            <p>Saken har lagts till i din bucketlist!</p>
        <?php endif; ?>

        <!-- Meddelande när en sak raderats -->
        <?php if (isset($_GET['deleted'])): ?>
            <p>Saken har raderats från din bucketlist!</p>
        <?php endif; ?>

        <!-- Visa eventuella felmeddelanden -->
        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!-- Formulär för att lägga till saker -->
        <form method="POST" action="bucketlist.php">

            <label for="name">Namn:</label>

            <input
                type="text"
                id="name"
                name="name"
                maxlength="255"
                value="<?= htmlspecialchars($name) ?>"
                required
            >

            <label for="description">Beskrivning:</label>

            <textarea
                id="description"
                name="description"
                required
            ><?= htmlspecialchars($description) ?></textarea>

            <label for="priority">Prioritet:</label>

            <select id="priority" name="priority" required>

                <?php for ($i = 1; $i <= 5; $i++): ?>

                    <option
                        value="<?= $i ?>"
                        <?= (string)$priority === (string)$i ? 'selected' : '' ?>
                    >
                        <?= $i ?>
                    </option>

                <?php endfor; ?>

            </select>

            <button type="submit">Lägg till</button>

        </form>


        <!-- Visa alla saker i bucketlisten -->
        <h2>Mina saker</h2>

        <?php if (empty($items)): ?>

            <p>Din bucketlist är tom just nu.</p>

        <?php else: ?>

            <?php foreach ($items as $item): ?>

                <article>

                    <h3>
                        <?= htmlspecialchars($item['name']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($item['description']) ?>
                    </p>

                    <p>
                        Prioritet:
                        <?= htmlspecialchars((string)$item['priority']) ?>
                    </p>

                    <p>
                        Tillagd:
                        <?= htmlspecialchars($item['created_at']) ?>
                    </p>

                    <!-- Formulär för att radera en sak -->
                    <form method="POST" action="bucketlist.php">

                        <input
                            type="hidden"
                            name="delete_id"
                            value="<?= (int)$item['id'] ?>"
                        >

                        <button type="submit">Radera</button>

                    </form>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>

    <footer>
        <p>Laboration 2 – Introduktion till PHP</p>
    </footer>

</body>

</html>
