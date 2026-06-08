<?php

class AlbumDTO {
    public $title;
    public $interpret;       // Opraveno z $author
    public $catalog_number;  // Opraveno z $isbn
    public $category;
    public $subcategory;
    public $year;
    public $price;
    public $link;
    public $description;
    public $images;

    // Konstruktor naplní objekt daty hned při jeho vytvoření
    public function __construct($data) {
        $this->title = $data['title'] ?? '';
        $this->interpret = $data['interpret'] ?? '';           // Opraveno
        $this->catalog_number = $data['catalog_number'] ?? ''; // Opraveno
        $this->category = $data['category'] ?? '';
        $this->subcategory = $data['subcategory'] ?? '';
        $this->year = $data['year'] ?? 0;
        $this->price = $data['price'] ?? 0;
        $this->link = $data['link'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->images = $data['images'] ?? [];
    }
}