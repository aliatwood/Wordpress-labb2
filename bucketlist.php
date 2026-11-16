
<?php

require_once __DIR__ . '/config/config.php';

$errors = [];
$name = '';
$description = '';
$priority = '';
$editId = null;

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

    // Lägg till eller uppdatera en sak
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

        // Uppdatera en befintlig sak
        if (isset($_POST['update_id'])) {

            $editId = filter_var($_POST['update_id'], FILTER_VALIDATE_INT);

            if ($editId === false || $editId <= 0) {
                $errors[] = "Ogiltigt ID.";
            }

            if (empty($errors)) {

                $bucketlist->update(
                    $editId,
                    $name,
                    $description,
                    $priorityValue
                );

                header('Location: bucketlist.php?updated=1');
                exit;
            }
        }

        // Lägg till en ny sak
        elseif (empty($errors)) {

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

// Hämta information om saken som ska redigeras
if (isset($_GET['edit_id'])) {

    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);

    if ($editId !== false && $editId > 0) {

        foreach ($items as $item) {

            if ((int)$item['id'] === $editId) {
                $name = $item['name'];
                $description = $item['description'];
                $priority = $item['priority'];
                break;
            }
        }

        if ($name === '') {
            $editId = null;
            $errors[] = "Saken kunde inte hittas.";
        }
    } else {
        $editId = null;
        $errors[] = "Ogiltigt ID.";
    }
}

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

        <h2><?= $editId ? 'Redigera en sak' : 'Lägg till en sak' ?></h2>

        <?php if (isset($_GET['added'])): ?>
            <p>Saken har lagts till i din bucketlist!</p>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <p>Saken har raderats från din bucketlist!</p>
        <?php endif; ?>

        <?php if (isset($_GET['updated'])): ?>
            <p>Saken har uppdaterats!</p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!-- Formulär för att lägga till eller redigera -->
        <form method="POST" action="bucketlist.php">

            <?php if ($editId): ?>
                <input
                    type="hidden"
                    name="update_id"
                    value="<?= (int)$editId ?>"
                >
            <?php endif; ?>

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
                <option value="" disabled <?= $priority === '' ? 'selected' : '' ?>>
                    Välj prioritet
                </option>

                <?php for ($i = 1; $i <= 5; $i++): ?>

                    <option
                        value="<?= $i ?>"
                        <?= (string)$priority === (string)$i ? 'selected' : '' ?>
                    >
                        <?= $i ?>
                    </option>

                <?php endfor; ?>

            </select>

            <button type="submit">
                <?= $editId ? 'Spara ändringar' : 'Lägg till' ?>
            </button>

            <?php if ($editId): ?>
                <a href="bucketlist.php">Avbryt redigering</a>
            <?php endif; ?>

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

                    <!-- Redigera en sak -->
                    <a href="bucketlist.php?edit_id=<?= (int)$item['id'] ?>">
                        Redigera
                    </a>

                    <!-- Radera en sak -->
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
