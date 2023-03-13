PlotWebPHP
==========

# This project is end of life and has been replaced by [Arkitektonika](https://github.com/IntellectualSites/Arkitektonika).

**Installation:**

1.  Set the schematic upload folder in your PlotSquared `settings.yml` and make sure that the folder is writable (note: this is solely for server -> website)
2.	Configure PlotWeb to use the same folder, through the `/lib/configuration.php` file. The settings should be easy to understand.
3.	Make sure that the folder of choice is writable by the script (`chmod 777`).

If you want to enable schematic uploads, then do the following:

1.  Set `$config['schematic']['upload']` to true.
2.  Choose a folder for the uploads (optimally the folder used by PlotSquared `Server Root/plugins/PlotSquared/schematics` and make sure that it is writable.
