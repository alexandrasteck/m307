<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spendenbestätigung</title>
    <link rel="stylesheet" href="style/style.css" />
    <link rel="stylesheet" href="https://use.typekit.net/rbg7wib.css">
    <link rel="icon" type="image/x-icon" href="media/favicon.png">
</head>

<body>
    <div class="form-container">
        <!-- Kopfzeile -->
        <header>
            <img src="media/wildsphere_logo.png" alt="Wildsphere Logo" class="logo">
        </header>

        <!-- Hauptteil -->
        <main>
            <h1 class="form-title">Ihre Spende wurde erfolgreich übermittelt!</h1>

            <?php
            // Falls Daten fehlen wird man wieder zurück zur Startseite geleitet
            session_start();
            if (!isset($_SESSION['confirmation_data'])) {
                header('Location: index.php');
                exit();
            }

            // Daten aus index.php (session) holen
            $fname = htmlspecialchars($_SESSION['confirmation_data']['fname']);
            $lname = htmlspecialchars($_SESSION['confirmation_data']['lname']);
            $amount = htmlspecialchars($_SESSION['confirmation_data']['amount']);
            $purpose = htmlspecialchars($_SESSION['confirmation_data']['purpose']);

            // Passender Text zu den Spendenzwecken schreiben
            $purposeReadable = [
                'lewasavanne' => 'die Lewa Savanne',
                'kenia' => 'das Naturschutzprojekt in Kenia',
                'südafrika' => 'das Naturschutzprojekt in Südafrika',
                'zoo' => 'den WildSphere Zoo',
                'auffangstation' => 'die Auffangstation'
            ][$purpose] ?? 'das ausgewählte Projekt';
            ?>

            <!-- Text mit drei gemachten Eingaben -->
            <p class='confirmation-text'>Vielen Dank <strong><?= $fname ?> <?= $lname ?></strong>, für Ihre Spende von <strong><?= $amount ?> CHF</strong> für <strong><?= $purposeReadable ?></strong>.</p>

            <!-- Button -> weiterleitung zurück zur Startseite -->
            <button onclick="window.location.href='index.php'" type="submit" class="submit-btn">
                Zurück zur Startseite
            </button>
        </main>

        <!-- Fusszeile -->
        <footer>
            <div class="footer-top">
                <div>Modul 307</div>
                <div>S-MMA23b</div>
                <div>Alexandra Steck</div>
            </div>
            <div class="footer-bottom">
                <div><a href="media/Steck_Alexandra_Broschuere.pdf">Broschüre</a></div>
                <div><a href="https://disclaimer.bbzwinf.ch/">Disclaimer</a></div>
            </div>
        </footer>
    </div>
</body>

</html>