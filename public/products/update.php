<?php

/** @var pdo \PDO */
require_once "../../functions.php";
require_once "../../database.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$products = $statement->fetch(PDO::FETCH_ASSOC);

$title = $products['title'];
$description = $products['description'];
$price = $products['price'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once "../../validate_product.php";

    if (empty($errors)) {

        $statement =  $pdo->prepare("UPDATE products SET title = :title, image = :image, 
                    description = :description, price = :price WHERE id = :id");

        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':id', $id);
        $statement->execute();

        header('Location:index.php');
    }
}
?>

<!doctype html>
<html lang="en">

<?php include_once "../../views/partials/header.php" ?>

<body>
    <p>
        <a href="index.php" class="btn btn-secondary">Go Back to Products</a>
    </p>

    <h1>Update Product <?php echo $title ?></h1>

    <?php include_once "../../views/products/form.php" ?>

</body>

</html>