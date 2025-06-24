<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style/style.css" />
  <link rel="stylesheet" href="https://use.typekit.net/rbg7wib.css">
  <link rel="icon" type="image/x-icon" href="media/favicon.png">
  <script src="javascript/javascript.js"></script>

  <title>Spendenformular Wildsphere</title>

  <?php
  $error = [];
  $purpose = "";
  $amount = 50;
  $interval = "";
  $message = "";
  $fname = "";
  $lname = "";
  $mail = "";
  $adress = "";
  $city = "";
  $paymethod = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function sanitize_input($value)
    {
      return htmlspecialchars(trim($value));
    }

    // Prüft, ob ein Spendenzweck ausgewählt wurde
    $purpose = isset($_POST['purpose']) ? sanitize_input($_POST['purpose']) : '';
    if (empty($purpose)) {
      $error[1] = "Bitte wählen Sie einen Spendenzweck.";
    }

    // Prüft, ob ein Wert ausgewählt wurde - wenn nicht: Standardwert von 50
    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 50;
    if ($amount < 10 || $amount > 500) {
      $error[2] = "Der Betrag muss zwischen 10 CHF und 500 CHF liegen.";
    }

    // Prüft, ob ein Intervall ausgewählt wurde
    $interval = isset($_POST['interval']) ? sanitize_input($_POST['interval']) : '';
    if (empty($interval)) {
      $error[3] = "Bitte wählen Sie ein Spendenintervall.";
    }

    // Definiert die Variable
    $message = $_POST['message'] ?? '';

    // Prüft, ob etwas eingegeben wurde + prüft, dass der Name nicht länger als 50 Zeichen ist
    $fname = isset($_POST['fname']) ? sanitize_input($_POST['fname']) : '';
    if (empty($fname)) {
      $error[10] = "Vorname ist erforderlich.";
    } elseif (mb_strlen($fname) > 50) {
      $error[10] = "Vorname darf maximal 50 Zeichen lang sein.";
    }

    // Prüft, ob etwas eingegeben wurde + prüft, dass der Name nicht länger als 50 Zeichen ist
    $lname =
      isset($_POST['lname']) ? sanitize_input($_POST['lname']) : '';
    if (empty($lname)) {
      $error[11] = "Nachname ist erforderlich.";
    } elseif (mb_strlen($lname) > 50) {
      $error[11] = "Nachname darf maximal 50 Zeichen lang
    sein.";
    }

    // Prüft, ob etwas eingegeben wurde + wird durch den E-Mail prüfer getan (dass ein @ Zeichen und ein. vorhanden ist) + prüft, dass die Mail nicht länger als 100 Zeichen ist
    $mail = isset($_POST['mail']) ? sanitize_input($_POST['mail']) :
      '';
    if (empty($mail) || !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
      $error[12] = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
    } elseif (mb_strlen($mail) > 100) {
      $error[12] = "E-Mail-Adresse darf maximal 100 Zeichen lang sein.";
    }

    // Prüft, ob etwas eingegeben wurde + prüft, dass die Adresse nicht länger als 80 Zeichen ist + prüft, ob eine Hausnummer eingegeben wurde
    $adress = isset($_POST['adress']) ? sanitize_input($_POST['adress']) : '';
    if (empty($adress)) {
      $error[13] = "Adresse ist erforderlich.";
    } elseif (mb_strlen($adress) > 80) {
      $error[13] = "Adresse darf maximal 80 Zeichen lang sein.";
    } elseif (!preg_match('/\d+/', $adress)) {
      $error[13] = "Bitte geben Sie eine Adresse mit Hausnummer ein.";
    }

    // Prüft, ob etwas eingegeben wurde + prüft, dass der Ort nicht länger als 50 Zeichen ist
    $city = isset($_POST['city']) ? sanitize_input($_POST['city']) : '';
    if (empty($city)) {
      $error[14] = "Ort ist erforderlich.";
    } elseif (mb_strlen($city) > 50) {
      $error[14] = "Ort darf maximal 50 Zeichen lang
    sein.";
    }

    // Prüft, ob etwas eingegeben wurde + prüft, ob die PLZ aus 4 Nummern besteht
    if (empty($_POST['postalcode']) || !preg_match('/^\d{4}$/', $_POST['postalcode'])) {
      $error[15] = "Bitte geben Sie eine gültige PLZ mit vier Zahlen ein.";
    } elseif ($_POST['postalcode'] < 1000) {
      $error[15] = "Die Postleitzahl muss mindestens 1000 Betragen.";
    }

    // Prüft, ob eine Zahlungsart ausgewählt wurde
    $paymethod = isset($_POST['paymethod']) ? sanitize_input($_POST['paymethod']) : '';
    if (empty($paymethod)) {
      $error[20] = "Bitte wählen Sie eine Zahlungsart.";
    }

    // Wenn "Kreditkarte" gewählt wurde, werden die Kreditkartenangaben geprüft
    // Prüft, ob etwas eingegeben wurde + prüft, dass die Nummer im richtigen Format eingegeben wurde
    if (isset($_POST['paymethod']) && $_POST['paymethod'] === 'kreditkarte') {
      if (
        empty($_POST['cardnumber']) || !preg_match('/^\d{4}([ -])\d{4}\1\d{4}\1\d{4}$/', $_POST['cardnumber'])
      ) {
        $error[21] = "Bitte geben Sie eine gültige Kreditkartennummer im Format 0000 0000 0000 0000 oder 0000-0000-0000-0000 ein.";
      }
    }
    // Prüft, ob ein Ablaufdatum gewählt wurde
    if (isset($_POST['paymethod']) && $_POST['paymethod'] === 'kreditkarte') {
      if (empty($_POST['expdate'])) {
        $error[22] = "Bitte geben Sie das Ablaufdatum an.";
      }
    }
    // Prüft, ob etwas eingegeben wurde + prüft, ob der CVC aus 3 oder 4 Nummern besteht
    if (isset($_POST['paymethod']) && $_POST['paymethod'] === 'kreditkarte') {
      if (
        empty($_POST['cvc']) || !preg_match('/^\d{3,4}$/', $_POST['cvc'])
      ) {
        $error[23] = "Bitte geben Sie eine gültige CVC-Nummer ein.";
      }
    }

    // Prüft, ob die Datenschutzbestimmungen angenommen wurden (pflichtfeld)
    if (!isset($_POST['privacypolicy'])) {
      $error[30] = "Sie müssen den Datenschutzbestimmungen zustimmen.";
    }

    // Werte werden gesammelt und mit $_SESSION['confirmation_data'] an confirmation.php übergeben
    session_start();
    $_SESSION['confirmation_data'] = [
      'fname' => $fname,
      'lname' => $lname,
      'amount' => $amount,
      'purpose' => $purpose
    ];

    if (empty($error)) {
      header('Location: confirmation.php');
      exit();
    }
  }
  ?>

</head>

<body>
  <div class="form-container">
    <!-- Kopfzeile -->
    <header>
      <img src="media/wildsphere_logo.png" alt="Wildsphere Logo" class="logo">
    </header>

    <!-- Hauptteil -->
    <main>
      <h1 class="form-title">Spendenformular für Naturschutzprojekte</h1>

      <!-- Forumlar -->
      <form id="donationform" method="POST" action="" autocomplete="on">

        <!-- Spendendetails -->
        <div class="group">
          <h2 class="group-title" for="details">Spendendetails</h2>
          <div id="details">

            <!-- Spendenzweck -->
            <div class="form-group">
              <label for="purpose" class="label">Spendenzweck</label>
              <select id="purpose" name="purpose" class="form-control" autofocus>
                <option value="" <?= $purpose === '' ? 'selected' : '' ?>>Bitte wählen</option>
                <option value="lewasavanne" <?= $purpose === 'lewasavanne' ? 'selected' : '' ?>>Lewa Savanne</option>
                <option value="kenia" <?= $purpose === 'kenia' ? 'selected' : '' ?>>Naturschutzprojekt in Kenia</option>
                <option value="südafrika" <?= $purpose === 'südafrika' ? 'selected' : '' ?>>Naturschutzprojekt in Südafrika</option>
                <option value="zoo" <?= $purpose === 'zoo' ? 'selected' : '' ?>>WildSphere Zoo</option>
                <option value="auffangstation" <?= $purpose === 'auffangstation' ? 'selected' : '' ?>>Auffangstation</option>
              </select>
              <?php if (!empty($error[1])) {
                echo "<div class='error-messages'>$error[1]</div>";
              } ?>
            </div>

            <!-- Spendenbetrag -->
            <div class="form-group">
              <label for="amount" class="label">Spendenbetrag <span id="amountDisplay"><?= $amount ?></span> CHF</label>
              <input type="range" id="amount" name="amount" min="10" max="500" step="10" value="<?= $amount ?>" onchange="show_value(this.value)" />
              <?php if (!empty($error[2])) {
                echo "<div class='error-messages'>$error[2]</div>";
              } ?>
            </div>

            <!-- Spendenintervall -->
            <div class="form-group">
              <label class="label" for="interval">Spendenintervall</label>
              <div id="interval">
                <label class="form-check">
                  <input type="radio" name="interval" id="einmalig" value="einmalig" <?= $interval === 'einmalig' ? 'checked' : '' ?> />
                  Einmalig
                </label>
                <label class="form-check">
                  <input type="radio" name="interval" value="monatlich" <?= $interval === 'monatlich' ? 'checked' : '' ?> />
                  Monatlich
                </label>
                <label class="form-check">
                  <input type="radio" name="interval" value="jaehrlich" <?= $interval === 'jaehrlich' ? 'checked' : '' ?> />
                  Jährlich
                </label>
                <?php if (!empty($error[3])) {
                  echo "<div class='error-messages'>$error[3]</div>";
                } ?>
              </div>
            </div>

            <!-- Nachricht -->
            <div class="form-group">
              <label for="message" class="label">Nachricht (optional)</label>
              <textarea id="message" name="message" class="form-control" rows="5" placeholder="Ihre Nachricht an uns..."><?= htmlspecialchars($message ?? '') ?></textarea>
            </div>
          </div>
        </div>

        <!-- Persönliche Informationen -->
        <div class="group">
          <h2 class="group-title" for="personaldetails">Persönliche Informationen</h2>
          <div id="personaldetails">

            <div class="grid">

              <!-- Vorname -->
              <div class="form-group">
                <label for="fname" class="label">Vorname</label>
                <input type="text" id="fname" name="fname" class="form-control" placeholder="Max" value="<?= $fname ?>" />
                <?php if (!empty($error[10])) {
                  echo "<div class='error-messages'>$error[10]</div>";
                } ?>
              </div>

              <!-- Nachname -->
              <div class=" form-group">
                <label for="lname" class="label">Nachname</label>
                <input type="text" id="lname" name="lname" class="form-control" placeholder="Muster" value="<?= $lname ?>" />
                <?php if (!empty($error[11])) {
                  echo "<div class='error-messages'>$error[11]</div>";
                } ?>
              </div>
            </div>

            <!-- E-Mail Adresse -->
            <div class="form-group">
              <label for="mail" class="label">E-Mail</label>
              <input type="email" id="mail" name="mail" class="form-control" placeholder="beispiel@gmail.com" value="<?= $mail ?>" />
              <?php if (!empty($error[12])) {
                echo "<div class='error-messages'>$error[12]</div>";
              } ?>
            </div>

            <!-- Adresse -->
            <div class="form-group">
              <label for="adress" class="label">Adresse</label>
              <input type="text" id="adress" name="adress" class="form-control" placeholder="Beispielstrasse 12" value="<?= $adress ?>" />
              <?php if (!empty($error[13])) {
                echo "<div class='error-messages'>$error[13]</div>";
              } ?>
            </div>

            <!-- Ort -->
            <div class="grid">
              <div class="form-group">
                <label for="city" class="label">Ort</label>
                <input type="text" id="city" name="city" class="form-control" placeholder="Musterstadt" value="<?= $city ?>" />
                <?php if (!empty($error[14])) {
                  echo "<div class='error-messages'>$error[14]</div>";
                } ?>
              </div>

              <!-- Postleitzahl -->
              <div class="form-group">
                <label for="postalcode" class="label">PLZ</label>
                <input type="number" id="postalcode" name="postalcode" class="form-control" placeholder="6003" value="<?= htmlspecialchars($_POST['postalcode'] ?? '') ?>" />
                <?php if (!empty($error[15])) {
                  echo "<div class='error-messages'>$error[15]</div>";
                } ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Zahlungsinformationen -->
        <div class="group">
          <h2 class="group-title" for="paymentinformations">Zahlungsinformationen</h2>
          <div id="paymentinformations">

            <!-- Zahlungsart -->
            <div class="form-group">
              <label class="label">Zahlungsart</label>
              <div id="paymethod">
                <!-- Kreditkarte -->
                <label class="form-check">
                  <input type="radio" name="paymethod" id="kreditkarte" value="kreditkarte" <?= $paymethod === 'kreditkarte' ? 'checked' : '' ?> onchange="toggleCreditCardFields(this)" />
                  Kreditkarte
                </label>
                <!-- Rechnung -->
                <label class="form-check">
                  <input type="radio" name="paymethod" id="rechnung" value="rechnung" <?= $paymethod === 'rechnung' ? 'checked' : '' ?> onchange="toggleCreditCardFields(this)" />
                  Rechnung
                </label>
                <!-- PayPal -->
                <label class="form-check">
                  <input type="radio" name="paymethod" id="paypal" value="paypal" <?= $paymethod === 'paypal' ? 'checked' : '' ?> onchange="toggleCreditCardFields(this)" />
                  PayPal
                </label>
                <?php if (!empty($error[20])) {
                  echo "<div class='error-messages'>$error[20]</div>";
                } ?>
              </div>
            </div>

            <!-- Kreditkartenangaben -->
            <div class="carddetails" id="creditCard" style="display: <?= $paymethod === 'kreditkarte' ? 'block' : 'none' ?>" ;>
              <!-- Nummer -->
              <div class="form-group">
                <label for="cardnumber" class="label">Kartennummer</label>
                <input type="text" id="cardnumber" name="cardnumber" class="form-control" placeholder="0000 0000 0000 0000" value="<?= htmlspecialchars($_POST['cardnumber'] ?? '') ?>" />
                <?php if (!empty($error[21])) {
                  echo "<div class='error-messages'>$error[21]</div>";
                } ?>
              </div>

              <!-- Ablaufdatum -->
              <div class="grid">
                <div class="form-group">
                  <label for="expdate" class="label">Ablaufdatum</label>
                  <input type="month" id="expdate" name="expdate" class="form-control" value="<?= htmlspecialchars($_POST['expdate'] ?? '') ?>" />
                  <?php if (!empty($error[22])) {
                    echo "<div class='error-messages'>$error[22]</div>";
                  } ?>
                </div>

                <!-- CVC -->
                <div class="form-group">
                  <label for="cvc" class="label">CVC</label>
                  <input type="number" id="cvc" name="cvc" class="form-control" value="<?= htmlspecialchars($_POST['cvc'] ?? '') ?>" />
                  <?php if (!empty($error[23])) {
                    echo "<div class='error-messages'>$error[23]</div>";
                  } ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Zusätzliche Optionen -->
        <div class="group">
          <h2 class="group-title" for="additionaloptions">Zusätzliche Optionen</h2>
          <div id="additionaloptions">

            <!-- Newsletter -->
            <div class="form-group">
              <label class="form-check" for="newsletter">
                <input type="checkbox" id="newsletter" name="newsletter" <?= isset($_POST['newsletter']) ? 'checked' : '' ?> />
                Ja, ich möchte den Newsletter erhalten (optional)
              </label>
            </div>

            <!-- Spenderzertifikat -->
            <div class="form-group">
              <label class="form-check">
                <input type="checkbox" id="certificate" name="certificate" <?= isset($_POST['certificate']) ? 'checked' : '' ?> />
                Ja, ich möchte ein Spenderzertifikat (optional)
              </label>
            </div>

            <!-- Datenschutzbestimmungen -->
            <div class="form-group">
              <label class="form-check">
                <input type="checkbox" id="privacypolicy" name="privacypolicy" <?= isset($_POST['privacypolicy']) ? 'checked' : '' ?> />
                Ich stimme den Datenschutzbestimmungen zu
              </label>
              <?php if (!empty($error[30])) {
                echo "<div class='error-messages'>$error[30]</div>";
              } ?>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">Spende absenden</button>
      </form>
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
    </footer>
  </div>

</body>

</html>