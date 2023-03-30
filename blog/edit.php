<?php
declare(strict_types=1);

$pdo = new PDO('sqlite:../database.db', null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);
$error = null;
$success = null;

try
{
    if(isset($_POST['name'], $_POST['content']))
    {
        $query = $pdo->prepare('UPDATE posts SET name = :name, content = :content WHERE id = :id');
        $query->execute([
            'name' => $_POST['name'],
            'content' => $_POST['content'],
            'id' => $_GET['id']
        ]);
        $success = "Votre article a bien été modifié";
    }
    $query = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $query->execute([
        'id' => $_GET['id']
    ]);
    $post = $query->fetch();
}
catch (Exception | Error $e)
{
    $error = $e->getMessage();
}

require_once '../elements/header.php';
?>


<div class="container mt-3">
    <p class="mb-3">
        <a href="index.php">Revenir au listing</a>
    </p>

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else: ?>
        <form action="" method="POST">
            <div class="from-group mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlentities($post->name) ?>">
            </div>

            <div class="form-group mb-3">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control"><?= htmlentities($post->content) ?></textarea>
            </div>

            <button class="btn btn-primary">Submit</button>
        </form>
    <?php endif; ?>
</div>

<?php
require_once '../elements/footer.php';
?>