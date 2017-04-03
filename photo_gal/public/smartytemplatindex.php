<?php
// example of smarty template smarty.net -- like templReplOO
// http://www.smarty.net/crash_course

// index.php

include('Smarty.class.php');

// create object
$smarty = new Smarty;

// assign some content -- typically from DB
$smarty->assign('name', 'gearge smith');
$marty->assign('address', '45th & Harris');

// display it
$smarty->display('index.tpl')


// =================
// index.tpl *** BELOW GOES in SEPARATE file 
<html>
<head>
<title>Info</title>
</head>
<body>

<pre>
User Info:

Name: {$name}
Address: {$address}
</pre>

</body>
</html>

// ************** END first example index.tpl
// ** BELOW 2nd examp index.tpl:
{include file="header.tpl" title="Info"}

User Information:<p>

Name: {$name|capitalize}<br>
Address: {$address|escape}<br>

{include file="footer.tpl"}
?>