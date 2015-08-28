<?php

class Config {
    private static $info = array();
    public static function set($key, $value) {
        self::$info[$key] = $value;
    }
    public static function get($key) {
        return self::$info[$key];
    }
    public static function getAll() {
        return self::$info;
    }
}
########################################################################################################################
$config = array();                                                                                                     #
                                                                                                                       #
//DO NOT EDIT ABOVE THIS                                                                                               #
                                                                                                                       #
# Edit below this ######################################################################################################
$config['title']                    = 'Plot Web'            ; # Title of the web page                                  #
                                                                                                                       #
$config['schematic']['folder']      = 'schematics'          ; # This is where you keep your schematics                 #
$config['schematic']['upload']      = false                 ; # Change to true to enable uploads                       #
$config['schematic']['max_size']    = 50000                 ; #  Max upload size (bytes)                               #
$config['schematic']['up_dir']      = 'schematics'          ; # This is where uploaded schematics will go              #
                                                              # (e.g. <server root>/plugins/plotsquared/schematics     #
$config['theme']                    = 'default'             ; # see styles directory "styles_{style}.css"              #
                                                                                                                       #
$config['mysql']['host']            = 'localhost'           ; # mysql host                                             #
$config['mysql']['username']        = 'root'                ; # mysql username                                         #
$config['mysql']['password']        = 'password'            ; # mysql port                                             #
$config['mysql']['port']            = '3306'                ; # mysql port                                             #
$config['mysql']['prefix']          = ''                    ; # mysql table                                            #
$config['mysql']['database']        = 'plot_db'             ; # mysql table                                            #
########################################################################################################################

// DO NOT EDIT BELOW THIS
foreach($config as $var => $val) {
    Config::set($var, $val);
}
unset($config);
?>