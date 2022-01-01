<?php

$title = $_POST['title'];
$description = $_POST['description'];
$price = $_POST['price'];

if (!$title) {
    $errors[] = "Product title is required!";
}
if (!$price) {
    $errors[] = "Product price is required!";
}

if (!is_dir(__DIR__ . '/public/images')) {
    mkdir(__DIR__ . '/public/images');
}

if (empty($errors)) {

    $imagePath;
    $image = $_FILES['image'] ?? null;
    

    if ($image && $image['tmp_name']) {

        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        
        //remove existing image if image recieve on POST request
        if ($products['image']) {
            unlink(__DIR__.'/public/'.$products['image']);
        }

        //generate random image name with image extention
        $randomImageName = date('Us', time()).$extension;
        
        //public folder image directory path
        $imagePath = "images/" . $randomImageName;

        //move image from wamp temp location to project public image location
        move_uploaded_file($image['tmp_name'], __DIR__ . '/public/' . $imagePath);
    }
}
