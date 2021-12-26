<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (!$title) {
        $errors[] = "Product title is required!";
    }
    if (!$price) {
        $errors[] = "Product price is required!";
    }

    if (!is_dir('images')) {
        mkdir('images');
    }

    if (empty($errors)) {

        $imagePath = $products['image'];
        $image = $_FILES['image'] ?? null;

        if ($image && $image['tmp_name']) {

            if ($products['image']) {
                unlink($products['image']);
            }

            $imagePath = 'images/' . randomString(8) . '/' . $image['name'];

            mkdir(dirname($imagePath));

            move_uploaded_file($image['tmp_name'], $imagePath);
        }

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

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}


?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="app.css" />

    <title>Update Product</title>
</head>

<body>
    <p>
        <a href="index.php" class="btn btn-secondary">Go Back to Products</a>
    </p>

    <h1>Update Product <?php echo $title ?></h1>

    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">

        <?php if ($products['image']) : ?>
            <img src="<?php echo $products['image'] ?>" class="update-image" />
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Product Title</label>
            <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Product Description</label>
            <textarea type="text" name="description" class="form-control"><?php echo $description ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Price</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $price ?>">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</body>

</html>