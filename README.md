javocsoft-phpclib
=================

<b>JavocSoft PHP Commons Library</b>

This library is a compendium of utility routines and classes that can save you time when coding in PHP.



To use the library, follow these steps:

2.- <b>Get the library code</b> by using Composer utility
<pre>
    //In Unix/Linux
    curl -sS https://getcomposer.org/installer | php   
    //In Windows system
    Download and install comporser: https://getcomposer.org/Composer-Setup.exe
</pre>

<u>Composer</u>

  See Installation notes:  https://getcomposer.org/download/<br>
  See getting started: https://getcomposer.org/doc/00-intro.md<br>
  See Documentation https://getcomposer.org/doc/03-cli.md<br>

2.- <b>Configure the library</b> parameters according to your project by setting these parameters:
<pre>
    define ("INIT_APPNAME","your_app_name");
    define ("INIT_BASEDIR_PATH","your_app_folder\\");
    define ("INIT_LOGS_PATH",INIT_BASEDIR_PATH . "your_log_folder\\");
    define ("INIT_OPENSSL_PATH","your_openssl_folder");
    define ("INIT_ERRORS_ENV_MODE","DEV"); //Or PROD
</pre>

3.- <b>Add library classes to your project</b>. To achieve this, add the composer "autoload.php" to your code by including it: 
<pre>
    require 'vendor/autoload.php';
</pre>
    
4.- <b>Initilize the library</b>:
<pre>
    jvcphplib\JVCPHPLib::initLibrary();
</pre>
