<?php
/**
 * API.PHP
 *
 * For Any Archive Request Start Here
 *
 * PHP version 7
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Application
 * @package    Request
 * @author     Kevin Noseworthy <kevin.noseworthy@stjoseph.com>
 * @copyright  1997-2018 St.Joseph Communication
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */ 

session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT');
header("Cache-Control: public");
header("Pragma: public");
       
?>
<!DOCTYPE HTML>
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<meta name="viewport" 
content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="" rel="shortcut icon">
          <title>SJC Web Archive</title>
          
<link rel="stylesheet" 
href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
crossorigin="anonymous">
          <style>
          :root {
  --input-padding-x: .75rem;
  --input-padding-y: .75rem;
}

html,
body {
  height: 100%;
}

body {
  display: -ms-flexbox;
  display: -webkit-box;
  display: flex;
  -ms-flex-align: center;
  -ms-flex-pack: center;
  -webkit-box-align: center;
  align-items: center;
  -webkit-box-pack: center;
  justify-content: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 420px;
  padding: 15px;
  margin: 0 auto;
}

.form-label-group {
  position: relative;
  margin-bottom: 1rem;
}

.form-label-group > input,
.form-label-group > label {
  padding: var(--input-padding-y) var(--input-padding-x);
}

.form-label-group > label {
  position: absolute;
  top: 0;
  left: 0;
  display: block;
  width: 100%;
  margin-bottom: 0; /* Override default `<label>` margin */
  line-height: 1.5;
  color: #495057;
  border: 1px solid transparent;
  border-radius: .25rem;
  transition: all .1s ease-in-out;
}

.form-label-group input::-webkit-input-placeholder {
  color: transparent;
}

.form-label-group input:-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-ms-input-placeholder {
  color: transparent;
}

.form-label-group input::-moz-placeholder {
  color: transparent;
}

.form-label-group input::placeholder {
  color: transparent;
}

.form-label-group input:not(:placeholder-shown) {
  padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
  padding-bottom: calc(var(--input-padding-y) / 3);
}

.form-label-group input:not(:placeholder-shown) ~ label {
  padding-top: calc(var(--input-padding-y) / 3);
  padding-bottom: calc(var(--input-padding-y) / 3);
  font-size: 12px;
  color: #777;
}

          </style>
</head>
<body>

<form 
class="form-signin" 
action="includes/loggedin.php" 
method="post" name="form1" id="form1" 
ENCTYPE = 
"multipart/form-data"  | "application/x-www-form-urlencoded" | "text/plain" 
autocomplete="off">
      <div class="mb-4">
        <img 
        src="/sjcarchiveassets/lib/images/logo.jpg" 
        alt="Logo" 
        height="72px">
        <div id='error' class='error'></div>
      </div>
      <div class="form-label-group">
        <input id="inputEmail" class="form-control" 
        placeholder="Email address" 
        required="" autofocus="" type="email" name="username">
        <label for="inputEmail">Email address</label>
      </div>

      <div class="form-label-group">
        <input id="inputPassword" class="form-control" 
        placeholder="Password" required="" type="password" name="passwd">
        <label for="inputPassword">Password</label>
      </div>
              
      <button class="btn btn-lg btn-secondary btn-block" type="submit">
      Sign in
      </button>
      <p class="mt-5 mb-3 text-muted text-center">© 2017-2018</p>
    </form>

     </body>
</html>
