<?php
ob_start ();
if (!ini_get ('session.auto_start')) {
    session_start ();
}

define ('ROOT', dirname (dirname (__FILE__)));

define ('DB_PATH', ROOT . '/db/');

require ROOT . '/lib/utils.php';

?><html>
    <head>
        <title>MyLittleBlog</title>
        <link href="/static/mlb.css" media="screen" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <ul>
            <li><a href="/admin/?action=add">dodaj wpis</a></li>
        </ul>
        <div id="main" class="dowolna zmiana">

<?php

if (isset ($_SESSION['msg'])) {
    echo "<ul>";
    if (is_array ($_SESSION['msg'])) {
        foreach ($_SESSION['msg'] as $value) {
            echo "<li>".$value."</li>\n";
        }
    }
    else {
        echo "<li>". $_SESSION['msg'] ."</li>\n";
    }
    echo "</ul>\n";
    unset ($_SESSION['msg']);
}

if (isset ($_GET['action']) && in_array ($_GET['action'], array ('add', 'edit', 'del', ))) {
    require_once 'action_'. $_GET['action'] .'.php';
}
else {
    require_once 'action_list.php';
}

ob_flush ();

?>

        </div>
    </body>
</html>
