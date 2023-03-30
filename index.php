<?php

require 'vendor/autoload.php';

use App\GuestBook\{
    GuestBook,
    Message
};
use App\Contact\Message as ContactMessage;

$errors = null;
$success = false;
$demo = new ContactMessage();
$guestbook = new GuestBook(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'messages');

if(isset($_POST['username'], $_POST['message']))
{
    $message = new Message($_POST['username'], $_POST['message']);
    if($message->isValid())
    {
        $guestbook->addMessage($message);
        $success = true;
        $_POST = [];
    }
    else
    {
        $errors = $message->getErrors();
    }
}
$messages = $guestbook->getMessages();

$title = "Livre d'or";
include 'elements/header.php';
?>

<div class="container">
    <a href="blog/index.php">Lien vers le blog</a>

    <h1>Livre d'or</h1>
    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            Formulaire invalide
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success">
            Merci pour votre message
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="username">Pseudo</label>
            <input type="text" id="username" name="username" placeholder="Votre username" value="<?= isset($_POST['username']) ? htmlentities($_POST['username']) : '' ?>" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>">
            <?php if(isset($errors['username'])): ?>
                <div class="invalid-feedback"><?= $errors['username'] ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" placeholder="Votre message" value="<?= isset($_POST['message']) ? htmlentities($_POST['message']) : '' ?>" class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>"></textarea>
            <?php if(isset($errors['message'])): ?>
                <div class="invalid-feedback"><?= $errors['message'] ?></div>
            <?php endif; ?>
        </div>

        <button class="btn btn-primary" type="submit">Envoyer</button>
    </form>

    <?php if(!empty($messages)): ?>
        <h1 class="mt-4">Vos messages</h1>

        <?php foreach($messages as $message): ?>
            <?= $message->toHTML() ?>
        <?php endforeach ?>
    <?php endif; ?>
</div>

<?php
include 'elements/footer.php';
?>