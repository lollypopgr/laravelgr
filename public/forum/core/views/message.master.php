<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Message master view. Displays a simple HTML template. Used for things like fatal errors and the installer.
 *
 * @package esoTalk
 */
?>
<!DOCTYPE html>
<!-- This page was generated by esoTalk (http://esotalk.org) -->
<html>
<head>
<title><?php echo sanitizeHTML($data["pageTitle"]); ?></title>
<meta charset='<?php echo T("charset", "utf-8"); ?>'/>
<style type='text/css'>
body {background:#fff !important; font-size:13px; font-family:helvetica neue,helvetica,arial,sans-serif; -webkit-font-smoothing:antialiased; margin:0}
#container {margin:50px auto; width:900px; background:#eaf5f9; padding:20px; line-height:1.5; -webkit-border-radius:5px}
h1 {margin:0 0 20px; font-size:22px; font-weight:normal; color:#23637b}
#container div.details {border:1px dashed #a8cfdd; padding:10px; -webkit-border-radius:3px; margin-bottom:10px; overflow:auto}
a {text-decoration:none}
a:hover {text-decoration:underline}
a:active {color:#1a3e6d}
a {color:#1260ee}
hr {border-style:solid; border-color:#b2cdd8 !important; border-width:1px 0 0; margin:15px 0;}
pre {margin:0; font-size:90%}
.highlight {font-weight:bold; background:#fff}
span.highlight {color:red}
</style>
<?php if (!empty($data["head"])) echo $data["head"]; ?>
</head>
<body>

<div id='container'>
<?php echo $data["content"]; ?>
</div>

</body>
</html>