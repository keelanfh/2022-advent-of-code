<?php

require("helpers/helpers.php");

class FileOrFolder
{
    public string $name;
    private ?Folder $parent;

    // setting the parent
    // we also add this to parent->children
    public function setParent(?Folder $parent)
    {
        $this->parent = $parent;
        if (isset($this->parent)) {
            $this->parent->children[$this->name] = $this;
        }
    }

    public function getParent() : Folder
    {
        return $this->parent;
    }

    public function __construct(string $name, ?Folder $parent)
    {
        $this->name = $name;
        $this->setParent($parent);
    }
}

class Folder extends FileOrFolder
{
    public array $children = [];

    // recursively find the size of a folder, calling getSize on all children
    public function getSize() : int
    {
        return array_sum(array_map(fn($a) => $a->getSize(), $this->children));
    }
}

class File extends FileOrFolder
{
    private int $size;

    public function getSize() : int
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function __construct(string $name, Folder $parent, int $size)
    {
        parent::__construct($name, $parent);
        $this->setSize($size);
    }
}

// going through the whole tree to get the size of each folder
function getSizeOfAllChildren(Folder $folder)
{
    global $total_size;
    global $space_required;
    global $smallest_size;

    foreach ($folder->children as $child) {
        if ($child instanceof Folder) {
            $size = $child->getSize();

            // part 1
            if ($size <= 100000)
            {
                $total_size += $size;
            }

            // part 2
            if (($size >= $space_required) and ($size < $smallest_size)) {
                $smallest_size = $size;
            }

            getSizeOfAllChildren($child);
        }

        
    }
}

function handle_cd(array $line_split, Folder $current) : Folder
{
    if ($line_split[2] == "/") {
        return $current;
    }
    if ($line_split[2] == "..") {
        return $current->getParent();
    }
    return $current->children[$line_split[2]];
}

function handle_line(string $line, Folder $current) : Folder
{
    $line_split = explode(" ", $line);

    // commands run
    if ($line_split[0] == "$") {
        if ($line_split[1] == "cd") {
            return handle_cd(line_split: $line_split, current: $current);
        }
        if ($line_split[1] == "ls") {
            return $current;
        }
    }

    // directory appears
    if ($line_split[0] == "dir")
    {
        $dir = new Folder(name: $line_split[1], parent: $current);
        return $current;
    }

    // file appears
    $file = new File(name: $line_split[1], parent: $current, size: $line_split[0]);
    return $current;
}

$lines = read_file_to_array("07/input.txt");

$root = new Folder("/", null);

$current = $root;

foreach ($lines as $line) {
    $current = handle_line(line: $line, current: $current);
}

// part 1
$total_size = 0;

// part 2
$root_size = $root->getSize();
$space_required = $root_size - 40000000;
$smallest_size = $root_size;

getSizeOfAllChildren($root);

// part 1
echo $total_size . "\n";

// part 2
echo $smallest_size . "\n";