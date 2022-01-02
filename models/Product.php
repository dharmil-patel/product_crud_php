<?php

namespace app\models;

use app\Database;

class Product
{

    public ?int $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?float $price = null;
    public ?string $imagePath = null;
    public ?array $imageFile = null;

    public function load($data)
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? '';
        $this->price = $data['price'];
        $this->imagePath = $data['image'] ?? null;
        $this->imageFile = $data['imageFile'] ?? null;
    }

    public function save()
    {
        $errors = [];

        if (!$this->title) {
            $errors[] = "Product title is required!";
        }
        if (!$this->price) {
            $errors[] = "Product price is required!";
        }

        if (!is_dir(__DIR__ . '/../public/images')) {
            mkdir(__DIR__ . '/../public/images');
        }


        if (empty($errors)) {
            if ($this->imageFile && $this->imageFile['tmp_name']) {

                $extension = pathinfo($this->imageFile['name'], PATHINFO_EXTENSION);
                $extension = ".".$extension;

                //remove existing image if image recieve on POST request
                if ($this->imagePath) {
                    unlink(__DIR__ . '/../public/' . $this->imagePath);
                }

                //generate random image name with image extention
                $randomImageName = date('Us', time()) . $extension;

                //public folder image directory path
                $this->imagePath = "images/" . $randomImageName;

                //move image from wamp temp location to project public image location
                move_uploaded_file($this->imageFile['tmp_name'], __DIR__ . '/../public/' . $this->imagePath);
            }

            $db = Database::$db;
            if($this->id){               
                $db->updateProduct($this);
            }else{
                $db->createProduct($this);
            }
        }
        return $errors;
    }
    
}
