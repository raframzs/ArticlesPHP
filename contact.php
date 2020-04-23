<?php

require 'vendor/PHPMailer/class.phpmailer.php';
require 'vendor/PHPMailer/class.smtp.php';

require 'includes/init.php';

$email = '';
$subject = '';
$message = '';
$sent = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $errors = [];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($subject == '') {
        $errors[] = 'Please enter a Subject.';
    }

    if ($message == '') {
        $errors[] = 'Please enter a message.';
    }

    if (empty($errors)) {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {                     // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host= SMTP_HOST;                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = SMTP_USER;                   // SMTP username
            $mail->Password = SMTP_PASS;                               // SMTP password
            $mail->SMTPSecure = SMTP_ENCRYPTION;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port= SMTP_PORT;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($email);
            $mail->addAddress(SMTP_SENDER);    // Name is optional
            $mail->addReplyTo($email); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();

            $sent = true;

        } catch (Exception $e) {
            $errors [] = $mail->ErrorInfo; 
        }
    }
}

?>

<?php require 'includes/header.php'; ?>
</ul>
</div>
<h2 class="display-4 text-primary">Contact</h2>

<?php if($sent): ?>
    <p class="text-success">Message Sent</p>
<?php else: ?>
    
<?php endif; ?>



<?php if (!empty($errors)) : ?>
    <ul>
        <?php foreach ($errors as $e) : ?>
            <li class="list-group-item-light"><?= $e ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" id="formContact">

    <div class="form-group">
        <label for="email">Your Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Your Email" value="<?= htmlspecialchars($email); ?>">
    </div>

    <div class="form-group">
        <label for="subject">Subject</label>
        <input name="subject" id="subject" class="form-control" placeholder="Subject" value="<?= htmlspecialchars($subject); ?>">
    </div>


    <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" rows="5" class="form-control">
            <?= htmlspecialchars($message); ?>
        </textarea>
    </div>

    <button class="btn btn-success">Send</button>
</form>

<?php require 'includes/footer.php'; ?>