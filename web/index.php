<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>[Pre-Release] IGG Technik</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/assets/css/index.css">

        <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">
        <link rel="manifest" href="/assets/favicons/site.webmanifest">
        <link rel="mask-icon" href="/assets/favicons/safari-pinned-tab.svg" color="#fe5f55">
        <link rel="shortcut icon" href="/assets/favicons/favicon.ico">
        <meta name="msapplication-TileColor" content="#fe5f55">
        <meta name="msapplication-config" content="/assets/favicons/browserconfig.xml">
        <meta name="theme-color" content="#fe5f55">
    </head>
    <body>
        <!--[if lt IE 11]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <header>
            <div class="header">
                <a id="a__logo" href="https://iggtechnik.de"><img src="/assets/logos/full.png" alt="IGG Technik"></a>
                <nav>
                    <a href="https://cloud.iggtechnik.de">ownCloud</a>
                </nav>
                <button class="hamburger">
                    <div class="bar"></div>
                </button>
            </div>
        </header>
        <div class="mobile_nav">
            <nav>
                <div>
                    <a href="https://cloud.iggtechnik.de">ownCloud</a>
                </div>
            </nav>
        </div>

        <div id="news" class="width_lim">
            <h2>Aktuelles</h2>
            <p>
                Hallo!<br>
                Ich habe im laufe des letzten Jahres diese Website entworfen.<br>
                Die aktuelle Version ist zwar vorläufig, aber funktionsfähig.<br>
                <br>
                Und wie geht das jetzt?<br>
                1. E-Mail-Adresse angeben, unter der Sie erreichbar sind<br>
                2. Veranstaltungstitel angeben<br>
                3. Zeitraum der Veranstaltung angeben<br>
                4. Genauere Details angeben:<br>
                 - Vortrag/Konzert/Sonstiges?<br>
                 - Mikrofone?<br>
                 - Beamer?<br>
                 - Generalprobe? (Bei Konzerten bitte <b>unbedingt</b> angeben!)<br>
                 - Sonst noch irgendwas, an das ich gerade gar nicht gedacht hab?<br>
                <br>
                Warum jetzt diese Website?<br>
                In Zukunft soll diese Website als automatisiertes Aula-Buchungs-Portal agieren. Somit 
                wird die Planung der Veranstaltung in allen technischen und nicht-technischen Aspekten übersichtlicher und einfacher.
            </p>
        </div>
        <div id="calendar" class="width_lim">
            <h2>Kalender</h2>
            <h3>Unsere aktuell geplanten Veranstaltungen</h3>
            <iframe src="https://cloud.iggtechnik.de/index.php/apps/calendar/embed/3P6NO7HIJ0DMNY8K"></iframe>
        </div>
        <div id="form" class="width_lim">
            <h2>Formular</h2>
            <h3>Veranstaltungen anmelden & Technik reservieren</h3>
            <?php
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
            use PHPMailer\PHPMailer\SMTP;

            require 'vendor/autoload.php';

            $sent = false;
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $err = false;
            $err_msg[] = "";
            $email = "";
            $name = "";
            $start = "";
            $end = "";

            if (array_key_exists("email", $_POST) && PHPMailer::validateAddress($_POST["email"])) {
                $email = $_POST["email"];
            } else {
                $err_msg["email"] = "Ungültige E-Mail-Adresse";
                $err = true;
            }

            if (array_key_exists("name", $_POST) && $_POST["name"] !== "") {
                $name = "Event: " . substr(strip_tags($_POST["name"]), 0, 255);
            } else {
                $err_msg["name"] = "Kein Veranstaltungstitel";
                $err = true;
            }

            if (array_key_exists("start", $_POST) && $_POST["start"] !== "") {
                $start = "Start: " . substr(strip_tags($_POST["start"]), 0);
            } else {
                $err_msg["start"] = "Kein Start";
                $err = true;
            }

            if (array_key_exists("end", $_POST) && $_POST["end"] !== "") {
                $end = "End: " . substr(strip_tags($_POST["end"]), 0);
            } else {
                $err_msg["end"] = "Kein Ende";
                $err = true;
            }

            if (array_key_exists("other", $_POST) && $_POST["other"] !== "") {
                $other = "Other: " . substr(strip_tags($_POST["other"]), 0);
            } else {
                $start = "No other description provided.";
            }

            $mail = new PHPMailer(true);
            if (!$err) {
                $mail->isSMTP();
                $mail->Host = "web01.st-srv.eu";
                $mail->SMTPAuth = true;
                $mail->Username = "---"; //TODO: Insert e-mail when deploying
                $mail->Password = "---"; //TODO: Insert password when deploying
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom("form@iggtechnik.de", $email);
                $mail->addAddress("info@iggtechnik.de");
                $mail->addReplyTo($email);

                $mail->isHTML(true);
                $mail->Subject = $name;
                $mail->Body = $name . "<br>" . $start . "<br>" . $end . "<br>" . $other;
                if ($mail->send()) {
                $sent = true;
                }
            }
            }
            if (!$sent) { ?>
            <form id="form" action="#form" method="POST">
                <div>
                <label for="email">E-Mail</label>
                <input type="text" name="email" id="email" maxlength="255" <?php if (array_key_exists("email", $_POST))
                    echo "value=\"" . $_POST["email"] . "\""; ?>>
                <?php if ($err_msg["email"] != "") { ?>
                    <p class="php_error">
                    <?php echo $err_msg["email"]; ?>
                    </p>
                <?php } ?>
                </div>

                <div>
                <label for="name">Name der Veranstaltung</label>
                <input type="text" name="name" id="name" maxlength="255" <?php if (array_key_exists("name", $_POST))
                    echo "value=\"" . $_POST["name"] . "\""; ?>>
                <?php if ($err_msg["name"] != "") { ?>
                    <p class="php_error">
                    <?php echo $err_msg["name"]; ?>
                    </p>
                <?php } ?>
                </div>

                <div>
                <label for="start">Veranstaltungsbeginn</label>
                <input type="datetime-local" name="start" id="start" <?php if (array_key_exists("start", $_POST))
                    echo "value=\"" . $_POST["start"] . "\""; ?>>
                <?php if ($err_msg["start"] != "") { ?>
                    <p class="php_error">
                    <?php echo $err_msg["start"]; ?>
                    </p>
                <?php } ?>
                </div>

                <div>
                <label for="end">Veranstaltungsende</label>
                <input type="time" name="end" id="end" <?php if (array_key_exists("end", $_POST))
                    echo "value=\"" . $_POST["end"] . "\""; ?>>
                <?php if ($err_msg["end"] != "") { ?>
                    <p class="php_error">
                    <?php echo $err_msg["end"]; ?>
                    </p>
                <?php } ?>
                </div>

                <div id="div__other">
                <label for="other">Sonstiges<span class="not-selectable"
                    tooltip="Bitte beschreiben Sie Ihre technischen Anforderungen an uns. (Mikrofone, Beamer, Beleuchtung, Laptop, usw.)">❔</span></label>
                <textarea type="text" name="other" id="other"><?php if (array_key_exists("other", $_POST))
                    echo $_POST["other"]; ?></textarea>
                </div>

                <div id="div__submit">
                <input type="submit" value="Abschicken">
                </div>
            </form>
            <?php } else { ?>
            <p id="form-sent">Wir haben Ihre Anfrage aufgenommen. Sie erhalten von uns in den nächsten Tagen eine schriftliche
                Rückmeldung an die von Ihnen angegebene E-Mail-Adresse.</p>
            <?php } ?>
        </div>

        <footer>
            <div class="footer">
                <nav>
                    <a href="https://iggtechnik.de/imprint">Impressum</a>
                    <a href="https://iggtechnik.de/privacy-policy">Datenschutzerklärung</a>
                </nav>
            </div>
        </footer>

        <script src="/assets/js/index.js" async defer></script>
    </body>
</html>