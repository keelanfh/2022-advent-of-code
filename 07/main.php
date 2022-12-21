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

    public function getParent() : ?Folder
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
            if ($sizeChild <= 100000) {
                $sizeAllSmallFolders += $sizeChild;
            }

            // part 2
            if (($sizeChild >= $spaceRequired) &&
                ($sizeChild < $sizeSmallestFolderToDelete)) {

                $sizeSmallestFolderToDelete = $sizeChild;

            }

            getSizeOfAllChildren($child);
        }

        
    }
}

function handleCD(string $argument, Folder $currentFolder) : Folder
{
    return match($argument) {
        "/" => $currentFolder,
        ".." => $currentFolder->getParent(),
        default => $currentFolder->children[$argument]
    };
}

function handleLine(string $line, Folder $currentFolder) : Folder
{
    $lineSplit = explode(" ", $line);
    
    switch ($lineSplit[0]) {
        // commands
        case "$":
            return match($lineSplit[1]) {
                "cd" => handleCD(argument: $lineSplit[2], currentFolder: $currentFolder),
                "ls" => $currentFolder
            };

        // folder
        case "dir":
            new Folder(name: $lineSplit[1], parent: $currentFolder);
            return $currentFolder;

        // file
        default:
            new File(name: $lineSplit[1], parent: $currentFolder, size: $lineSplit[0]);
            return $currentFolder;
        }
}

$lines = read_file_to_array("07/input.txt");

$root = new Folder("/", null);

$currentFolder = $root;

foreach ($lines as $line) {
    $currentFolder = handleLine(line: $line, currentFolder: $currentFolder);
}

// part 2
$rootSize = $root->getSize();
$spaceRequired = $rootSize - 40000000;
$sizeSmallestFolderToDelete = $rootSize;

getSizeOfAllChildren($root);

// part 1
println($sizeAllSmallFolders);

// part 2
println($sizeSmallestFolderToDelete);