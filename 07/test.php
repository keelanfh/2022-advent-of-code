<?php

class FileOrFolder {
    public $name;
    private $parent;

    public function setParent($parent) {
        $this->parent = $parent;
        array_push($this->parent->children, $this);
    }
}

class Folder extends FileOrFolder {
    public $children = [];
}

class File extends FileOrFolder {
    public $size;
}

$f1 = new Folder();
$f1->name = "Keelan";

$f2 = new File();
$f2->name = "James";

$f2->setParent($f1);

print_r($f1);
print_r($f2);