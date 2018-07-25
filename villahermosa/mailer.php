<?php
    // My modifications to mailer script from:
    // http://blog.teamtreehouse.com/create-ajax-contact-form
    // Added input sanitizing to prevent injection

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["nombre"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $puesto = trim($_POST["puesto"]);
        $message = trim($_POST["mensaje"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR empty($puesto) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "¡Oops! Hubo un problema al enviar tu mensaje. Por favor, completa la forma de nuevo e intenta otra vez.";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = "jorgezetina@gmail.com";

        // Set the email subject.
        $subject = "Nuevo contacto de $name";

        // Build the email content.
        $email_content = "Nombre: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Puesto: $puesto\n\n\n";
        $email_content .= "Mensaje:\n$message\n";

        // Build the email headers.
        $email_headers = "De: $name <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "¡Gracias! Tu mensaje ha sido enviado.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "¡Oops! Algo ha salido mal y no hemos podido enviar tu mensaje.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "Hubo un problema con tu mensaje y no pudimos enviarlo, intenta de nuevo.";
    }

?>