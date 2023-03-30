<?php
declare(strict_types=1);

$pdo = new PDO('sqlite:../database.db', null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);
$error = null;

$pdo->beginTransaction();

try
{
    $pdo->exec('UPDATEff posts SET name = "demo" WHERE id = 3');
    $pdo->exec('UPDATE posts SET content = "demo" WHERE id = 3');
    $post = $pdo->query('SELECT * FROM posts WHERE id = 3')->fetch();
    $pdo->commit();
}
catch (Exception | Error $e)
{
    $pdo->rollBack();
    $error = $e->getMessage();
}

require_once '../elements/header.php';
?>

<div class="container">
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
</div>

<?php
require_once '../elements/header.php';
?>