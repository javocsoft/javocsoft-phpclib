javocsoft-phpclib
=================

JavocSoft PHP Commons Library

To use the library, follow these steps:

1.- Configure the library parameters according to your project by setting these parameters:
<pre>
    define ("INIT_APPNAME","your_app_name");
    define ("INIT_BASEDIR_PATH","your_app_folder\\");
    define ("INIT_LOGS_PATH",INIT_BASEDIR_PATH . "your_log_folder\\");
    define ("INIT_OPENSSL_PATH","your_openssl_folder");
    define ("INIT_ERRORS_ENV_MODE","DEV"); //Or PROD
</pre>

2.- Get code by using composer utility
<pre>
    //In Unix/Linux
    curl -sS https://getcomposer.org/installer | php   
    //In Windows system
    Download and install comporser: https://getcomposer.org/Composer-Setup.exe
</pre>

Composer

  See Installation notes:  https://getcomposer.org/download/<br>
  See getting started: https://getcomposer.org/doc/00-intro.md<br>
  See Documentation https://getcomposer.org/doc/03-cli.md<br>

3.- Add the composer "autoload.php" to your code by including it: 
<pre>
    require 'vendor/autoload.php';
</pre>
    
4.- Initilize the library:
<pre>
    jvcphplib\JVCPHPLib::initLibrary();
</pre>
