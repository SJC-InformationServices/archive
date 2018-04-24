<?php
/** Redbean */
class_alias('\RedBeanPHP\R','\R');

/** Set Error / Debug Modes */
$debugmode = false;

if($debugmode == true){
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    \R::fancyDebug( TRUE );
}
/** Set Twig Configs */
$twigPath = $root."/src/sjcArchiveApi/templates";
$twigCache = $root."/src/sjcArchiveApi/cache/twig";



?>