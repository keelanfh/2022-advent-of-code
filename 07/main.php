<?php

require("helpers/helpers.php");

class FileOrFolder
{
    private readonly ?Folder $parent;

    public function setParent(?Folder $parent) : void
    {
        $this->parent = $parent;

        // if parent != null, add this to parent's children
        if (isset($this->parent)) {
            $this->parent->children[$this->name] = $this;
        }
    }

    public function getParent() : Folder
    {
        return $this->parent;
    }

    public function __construct(public readonly string $name, ?Folder $parent)
    {
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
    private readonly int $size;

    public function getSize() : int
    {
        return $this->size;
    }

    public function setSize($size) : void
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
    global $sizeAllSmallFolders, $spaceRequired, $sizeSmallestFolderToDelete;

    foreach ($folder->children as $child) {
        if ($child instanceof Folder) {
            $sizeChild = $child->getSize();

            // part 1
            if ($sizeChild <= 100000)
            {
                $sizeAllSmallFolders += $sizeChild;
            }

            // part 2
            if (($sizeChild >= $spaceRequired) && ($sizeChild < $sizeSmallestFolderToDelete)) {
                $sizeSmallestFolderToDelete = $sizeChild;
            }

            getSizeOfAllChildren($child);
        }

        
    }
}

function handleCD(array $lineSplit, Folder $currentFolder) : Folder
{
    if ($lineSplit[2] == "/") {
        return $currentFolder;
    }
    if ($lineSplit[2] == "..") {
        return $currentFolder->getParent();
    }
    return $currentFolder->children[$lineSplit[2]];
}

function handleLine(string $line, Folder $currentFolder) : Folder
{
    $lineSplit = explode(" ", $line);

    // commands
    if ($lineSplit[0] == "$") {
        return match($lineSplit[1]) {
            "cd" => handleCD(lineSplit: $lineSplit, currentFolder: $currentFolder),
            "ls" => $currentFolder
        };
    }

    // folder
    if ($lineSplit[0] == "dir")
    {
        new Folder(name: $lineSplit[1], parent: $currentFolder);
    }

    // file
    else
    {
        new File(name: $lineSplit[1], parent: $currentFolder, size: $lineSplit[0]);
    }

    return $currentFolder;
}

$lines = read_file_to_array("07/input.txt");

$root = new Folder("/", null);

$currentFolder = $root;

foreach ($lines as $line) {
    $currentFolder = handleLine(line: $line, currentFolder: $currentFolder);
}

// part 1
$sizeAllSmallFolders = 0;

// part 2
$rootSize = $root->getSize();
$spaceRequired = $rootSize - 40000000;
$sizeSmallestFolderToDelete = $rootSize;

getSizeOfAllChildren($root);

// part 1
echo $sizeAllSmallFolders . "\n";

// part 2
echo $sizeSmallestFolderToDelete . "\n";