# Moodle Plugin Template

[![License](https://img.shields.io/badge/License-GPL--3.0-blue.svg)](https://www.gnu.org/licenses/gpl-3.0.en.html)

## Prerequisites 

1. Install [PHP](https://secure.php.net/manual/de/install.windows.manual.php) 
Install the version for your system (most likely x64).
1. Install [Composer](https://getcomposer.org/doc/00-intro.md)  
1. Clone this template
1. `$ composer install --no-dev`
1. `$ composer autofix`

## How to use the template?

The following steps should get you up and running with this module template code.

For the sake of this tutorial, it is assumed that you have a shell (or cmd on Windows) in the directory of this cloned repository. In all following command lines, it is assumed that you are not in any subdirectory. If `/` is used leading a path, it is assumed that this means the directory of this cloned repository and not your systems root directory. 

* Clone the repository and read this file

* Pick a name for your module (e.g. "testmodule").

  **The module name MUST be lower case and can't contain underscores.**

 * Keep in mind Moodle does not like numbers or special characters like `.` or `,` in names or paths. Name your plugin accordingly.

* Edit all the files in this directory and its subdirectories and change
  all the instances of the string "testmodule" to your module name
  (eg "testmodule"). 

  On a Windows system, you can use the following PowerShell commands. Use the command `cd` to change into the directory of your code.  
  `$files = Get-ChildItem . -recurse -include *.* ; foreach ($file in $files) { (Get-Content $file.PSPath) | ForEach-Object { $_ -replace "testmodule", "testmodule" } | Set-Content $file.PSPath }`  
  `$files = Get-ChildItem . -recurse -include *.* ; foreach ($file in $files) { (Get-Content $file.PSPath) | ForEach-Object { $_ -replace "TESTMODULE", "testmodule" } | Set-Content $file.PSPath }`  

  Replace "testmodule" in the commands above with your module name.

* Rename the file `/source/lang/en/testmodule.php` to lang/en/testmodule.php
  where "testmodule" is the name of your module

* Rename all files in `/source/backup/moodle2/` folder by replacing "testmodule" with
  the name of your module

  On a Windows system, you can use the following PowerShell command to perfrom this and the previous step:  
  `$files = Get-ChildItem . -recurse -include *.* | Where-Object {$_.Name -like "*testmodule*"}; foreach ($file in $files) { $newname = ([String]$file).Replace("testmodule", "testmodule"); Rename-Item -Path $file $newname }`

* Version your plugin accordingly. In the file `version.php`, replace the value for the version with a value combined of the current date (e.g. `20180708` for the 7th of July 2018) and the number of releases on this day (in most cases, `00`. If you update your plugin multiple times during one day, simply increase this number). This might look something like this: `2018070800`. Also replace the value of the variable `VERSION` in the second line of the file `db/install.xml`. 

##Deep-Dive Development 

### .ini-file
* Type of Initialization/Configuration File
* The INI file format is an informal standard for configuration files

### *_form.php
* Used for user-input (Views)
* To complete a field the submit-button should be used

### view_*.php-files
* Used for handling forms (Controllers)
* The business logic will take place here

### locallib.php vs lib.php
* All the core Moodle functions, neeeded to allow the module to work integrated in Moodle should be placed here.
* All the  specific functions, needed to implement all the module logic, should go to locallib.php.

### version.php
* Used for version control in moodle

### index.php
* Used by Moodle for Navigation Bar, etc.

### db-folder
* Used to setup the DB of your plugin

### long-folder
* Language file to be used with the get_string()-function

##How to deploy?

* Create a ZIP archive of the `/source` folder and name it according to your app (in this tutorial "testmodule").

* Login in to [our Moodle instance](https://moodle.ganymed.me), navigate to the Management of Moodle and select the Option to install a new plugin.

* Upload your ZIP archive and click the button to proceed. You do not need to edit any other fields in this interface. 

* When asked if you want to update the Moodle database, do so. 

  * If you get a timeout message, then your ZIP Archive is too big. Please run `composer install --no-dev` again, this time with the flag at the end to prevent all unnecessary libs from installing. This should slim down your ZIP archive (that you have to recreate, of course) down a bit, and processing should no longer take longer than 30 seconds.

* Go the main page of Moodle, select a Course and click "Enable Editing" in the options on the upper right. by clicking the option of "Add a resource ...", you should see a list of available plugins including your new module.

*Good luck, you will need it...*
