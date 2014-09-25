javocsoft-phpclib
=================

JavocSoft PHP Commons Library

To use the library, follow these steps:

1.- Configure the library parameters according to your project by setting these parameters:
<pre>
    define ("INIT_APPNAME","testingcomposerdist");
    define ("INIT_BASEDIR_PATH","C:\\wamp\\www\\testcomposer\\");
    define ("INIT_LOGS_PATH",INIT_BASEDIR_PATH . "logs\\");
    define ("INIT_EXTERNAL_LIBS_PATH",INIT_BASEDIR_PATH . "logs\\");
    define ("INIT_OPENSSL_PATH","");
    define ("INIT_ERRORS_ENV_MODE","DEV");
</pre>

2.- Add the autoload.php to your code by including it: 
    require 'vendor/autoload.php';
    
3.- Initilize the library:
    jvcphplib\JVCPHPLib::initLibrary();
