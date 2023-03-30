<?php
declare(strict_types=1);

use App\Post;

require_once '../vendor/autoload.php';

$pdo = new PDO('sqlite:../database.db', null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);
$error = null;

try
{
    if(isset($_POST['name'], $_POST['content']))
    {
        $query = $pdo->prepare('
            INSERT INTO posts (name, content, created_at)
            VALUES (:name, :content, :created)
        ');
        $query->execute([
            'name' => $_POST['name'],
            'content' => $_POST['content'],
            'created' => time()
        ]);
        header('Location: blog/edit.php?id=' . $pdo->lastInsertId());
        $success = "Votre article a bien été modifié";
    }
    $query = $pdo->query('SELECT * FROM posts');
    /**
     * @var Post[]
     */
    $posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
}
catch (Exception | Error $e)
{
    $error = $e->getMessage();
}

require_once '../elements/header.php';
?>

<div class="container">
    <a href="../index.php">Lien vers le livre d'or</a>
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else: ?>
        <?php foreach($posts as $post): ?>
            <?php dump($post) ?>
            <h2><a href="edit.php?id=<?= $post->id ?>"><?= htmlentities($post->name) ?></a></h2>
            <p class="small text-muted">Ecrit le <?= $post->created_at->format('d/m/Y à H:i') ?></p>
            <p>
                <?= $post->getBody() ?>
            </p>
        <?php endforeach; ?>

        <form action="" method="POST">
            <div class="from-group mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter your name">
            </div>

            <div class="form-group mb-3">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" placeholder="Enter your text"></textarea>
            </div>

            <button class="btn btn-primary">Submit</button>
        </form>
    <?php endif; ?>
</div>

<?php
require_once '../elements/footer.php';
?>