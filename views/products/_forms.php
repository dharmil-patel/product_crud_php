<p>
    <a href="/" class="btn btn-secondary">Go Back to Products</a>
</p>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) : ?>
            <div><?php echo $error ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">

    <?php if ($product['image']) : ?>
        <img src="/<?php echo $product['image'] ?>" class="update-image" />
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Product Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $product['title'] ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Product Description</label>
        <textarea type="text" name="description" class="form-control"><?php echo $product['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Product Price</label>
        <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $product['price'] ?>">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>