<?php

class Schematic {
    var $id;
    var $world;
    var $username;

    function __construct($id, $world, $username) {
        $this -> id = $id;
        $this -> world = $world;
        $this -> username = str_replace('.schematic', '', $username);
    }

    function getFile(SchematicHandler $handler) {
        return $handler -> getSchematic($this -> id, $this -> world, $this -> username);
    }

    function toArray() {
        return array(
            'id' => $this -> id,
            'world' => $this -> world,
            'username' => $this -> username
        );
    }
}

class SchematicHandler {

    private $folder;

    function __construct($folder = "schematics"){
        $this -> folder = $folder;
    }

    public function getSchematic($id, $plotworld, $username) {
        $file_name = $this -> folder . "/$id,$plotworld.schematic";
        if(!file_exists($file_name)) {
            return false;
        }
        return $file_name;
    }

    public function getSchematicObject($file) {
        $parts = explode(",", $file);
        if(sizeof($parts) < 3)
            return null;
        return new Schematic($parts[0], $parts[1], $parts[2]);
    }

    public function getSchematicFromId($id) {
        $files = array();
        if($folder = opendir($this -> folder)) {
            while(($file = readdir($folder)) !== false) {
                if($file != "." && $file != "..") {
                    $parts = explode(",", $file);
                    if(sizeof($parts) < 3) continue;
                    if(strtolower($parts[0]) === $id) {
                        $files[] = $file;
                    }
                }
            }
        }
        return $files;
    }

    public function getSchematicFromName($username) {
        $files = array();
        if($folder = opendir($this -> folder)) {
            while(($file = readdir($folder)) !== false) {
                if($file != "." && $file != "..") {
                    $parts = explode(",", $file);
                    if(sizeof($parts) < 3) {
                        continue;
                    }
                    if(str_replace('.schematic', '', strtolower($parts[2])) === strtolower($username)) {
                        $files[] = $file;
                    }
                }
            }
        }
        return $files;
    }
    
    public function getSchematicFromFile($filename) {
        $files = array();
        if($folder = opendir($this -> folder)) {
            while(($file = readdir($folder)) !== false) {
                if($file != "." && $file != "..") {
                    $parts = explode(",", $file);
                    if(sizeof($parts) < 3) {
                        continue;
                    }
                    if(str_replace('.schematic', '', strtolower($file)) === strtolower($filename)) {
                        $files[] = $file;
                    }
                }
            }
        }
        return $files;
    }

    public function check_validity() {
        $folder = $this -> folder;
        if(!is_readable($folder)) {
            return array(
                'message' => "Directory '$folder' does not exists / is not writable", # YOU DID BAD!
                'status' => false # Nope.
            );
        }
        if(!is_writable($folder)) {
            return array(
                'message' => "Directory '$folder' is not writable", # ... 777 is key
                'status' => false # Nope
            );
        }
        if(!is_dir($folder)) {
            return array(
                'message' => "Directory '$folder' is not a directory", # I mean, you tried...
                'status' => false # NOPE...
            );
        }
        return array(
            'message' => "Directory '$folder' is a valid directory",
            'status' => true # Sure
        );
    }
}
?>