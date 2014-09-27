javocsoft-phpclib
=================

<h3>JavocSoft PHP Commons Library</h3>

<b>DESCRIPTION<b>

This library is a compendium of utility routines and classes that can save you time when coding in PHP.


<br><b>INSTALLATION & USAGE</b>

To use the library, follow these steps:

1.- <b>Get the library code</b> by using Composer utility. First, create "composer.json" file adding to it these lines:
<pre>
{
    "require": {
        "javocsoft/jvcphpclib": "1.0.0"        
    }
}
</pre>

Second, <b>download Composer</b> utility:

<pre>
    //In Unix/Linux
    curl -sS https://getcomposer.org/installer | php   
    //In Windows system
    Download and install comporser: https://getcomposer.org/Composer-Setup.exe
</pre>

More about Composer:

  See Installation notes:  https://getcomposer.org/download/<br>
  See getting started: https://getcomposer.org/doc/00-intro.md<br>
  See Documentation https://getcomposer.org/doc/03-cli.md<br>

3.- Now, <b>download the library</b> by running composer where we created the file "composer.json".

4.- <b>Configure the library</b> parameters according to your project by setting these parameters:
<pre>
    define ("INIT_APPNAME","your_app_name");
    define ("INIT_BASEDIR_PATH","your_app_folder\\");
    define ("INIT_LOGS_PATH",INIT_BASEDIR_PATH . "your_log_folder\\");
    define ("INIT_OPENSSL_PATH","your_openssl_folder");
    define ("INIT_ERRORS_ENV_MODE","DEV"); //Or PROD
</pre>

5.- <b>Add library classes to your project</b>. To achieve this, add the composer "autoload.php" to your code by including it: 
<pre>
    require 'vendor/autoload.php';
</pre>
    
6.- <b>Initialize the library</b>:
<pre>
    jvcphplib\JVCPHPLib::initLibrary();
</pre>
