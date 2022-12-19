<?php

require("helpers/helpers.php");

class FileOrFolder {
    public $name;
    private $parent;

    public function setParent($parent) {
        $this->parent = $parent;
        array_push($this->parent->children, $this);
    }

    public function getParent() {
        return $this->parent;
    }
}

class Folder extends FileOrFolder {
    public $children = [];
}

class File extends FileOrFolder {
    public $size;
}

$lines = read_file_to_array("07/example.txt");

$root = new Folder();
$root->name = "/";

$current = $root;

foreach ($lines as $line) {
    switch ($line) {
        case "$ cd /":
        case "$ ls":
            continue;
        default:
            $line_split = explode($line, " ");

            if ($line_split[0] == "dir") {
                $dir = new Folder();
                $dir->name = $line_split[1];
                $dir->setParent($current);
            } else if ($line_split[1] == "cd") {
                if ($line_split[2] == "..") {
                    $current = $current->getParent;
                    continue;
                } foreach ($current->children as $child) {
                        if ($child->name == line_split[2]) {
                            $current = $child;
                            continue;
                        }
                    }
            } else {
                $file = new File();
                $file->name = $line_split[1];
                $file->setParent($current);
                continue;
            }
    }
}