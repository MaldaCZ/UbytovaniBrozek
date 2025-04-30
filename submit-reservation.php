<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recaptchaSecret = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    $responseData = json_decode($verify);

    if (!$responseData->success) {
        http_response_code(403);
        echo "Ověření reCAPTCHA selhalo. Zkuste to prosím znovu.";
        exit;
    }


    // Načti a ošetři vstupy
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST["phone"]));
    $arrival = htmlspecialchars($_POST["arrival_date"]);
    $departure = htmlspecialchars($_POST["departure_date"]);
    $accommodation = htmlspecialchars($_POST["accommodation"]);
    $adults = (int)$_POST["adults"];
    $children = (int)$_POST["children"];
    $pet = isset($_POST["pet"]) ? "Ano" : "Ne";
    $gdpr = isset($_POST["gdpr"]) ? "Souhlasím" : "Nesouhlasím";

    // Validace
    if (!$email || empty($name) || empty($arrival) || empty($departure)) {
        http_response_code(400);
        echo "Neplatná data. Prosím zkontrolujte formulář.";
        exit;
    }

    // Tělo e-mailu
    $message = "Nová rezervace:\n";
    $message .= "Jméno: $name\n";
    $message .= "Email: $email\n";
    $message .= "Telefon: $phone\n";
    $message .= "Příjezd: $arrival\n";
    $message .= "Odjezd: $departure\n";
    $message .= "Typ ubytování: $accommodation\n";
    $message .= "Dospělí: $adults\n";
    $message .= "Děti: $children\n";
    $message .= "Domácí zvíře: $pet\n";
    $message .= "GDPR: $gdpr\n";

    // Nastavení e-mailu
    $to = "info@ubytovanibrozek.cz"; // Změň na svůj e-mail
    $subject = "Nová rezervace z webu";
    $headers = "From: noreply@ubytovanibrozek.cz\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "Děkujeme za Vaši rezervaci. Ozveme se co nejdříve!";
    } else {
        http_response_code(500);
        echo "Odeslání se nezdařilo. Zkuste to prosím znovu později.";
    }
} else {
    http_response_code(405);
    echo "Metoda není povolena.";
}
?>
