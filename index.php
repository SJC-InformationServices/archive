<?php

session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");     
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($url)) . ' GMT');
header("Cache-Control: public");
header("Pragma: public");
     if (!isset($_SESSION['SESSION'])){

if(isset($_SESSION['loggedin'])){

}else{
    
}

   }
     
     if ($_SESSION['LOGGEDIN'] == true) 
  {
            header("Location: work.php");
               
     }
     if (isset($HTTP_GET_VARS["flg"])) {$flg = $HTTP_GET_VARS["flg"];};
     
     switch ($flg) {
          
          case "red":
               $error = "That username/password combination is not in our database.Please Try Again.";
               break;
          case "blue":
               $error = "Your Session has Expired.<br>Please Login Again.";
               break;
          case "black":
               $error = "Your Security Key Failed!";
               break;
          case "purple":
               $error = "Password Reset Complete Please Login!";
          default:
               $error = "   <br>";
     }
     
?>
<!DOCTYPE HTML>
<html>
<head>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="" rel="shortcut icon">
          <title>SJC Web Archive</title>
          
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

<form class="form-signin" action="includes/loggedin.php" method="post" name="form1" id="form1" ENCTYPE = "multipart/form-data"  | "application/x-www-form-urlencoded" | "text/plain" autocomplete="off">
      <div class="mb-4">
        <img src="https://s3.amazonaws.com/sjcarchiveassets/lib/images/logo.jpg" alt="Logo" height="72px">
        <div id='error' class='error'><?php echo $error; ?></div>
      </div>
      <div class="form-label-group">
        <input id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="" type="email" name="username">
        <label for="inputEmail">Email address</label>
      </div>

      <div class="form-label-group">
        <input id="inputPassword" class="form-control" placeholder="Password" required="" type="password" name="passwd">
        <label for="inputPassword">Password</label>
      </div>
       
       <div class="form-label-group">
        <input id="inputSecurityCode" class="form-control" placeholder="Security Code" required="" type="text" name="security_code" >
        <label for="inputSecurityCode">Security Code</label>
          <p><div class="text-center mb-4"><img src="includes/CaptchaSecurityImages.php?width=100&height=40&characters=5&datetime=<?php echo time();?>" /></div></p>
      </div>
       
      <button class="btn btn-lg btn-secondary btn-block" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted text-center">© 2017-2018</p>
    </form>

     </body>
</html>
