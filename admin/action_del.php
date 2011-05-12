<?php

if (!isset ($_GET['slug'])) {
    $_SESSION['msg'] = 'Brak identyfikatora wpisu';
    header ('Location: /admin/');
    exit;
}

$path = slug_to_path ($_GET['slug']);
if (!file_exists ($path)) {
    $_SESSION['msg'] = 'Nie znaleziono wpisu o identyfikatorze "'. html ($_GET['slug']) .'"';
    header ('Location: /admin/');
    exit;
}

<<<<<<< HEAD
action_del ($path);
$_SESSION['msg'] = 'Wpis o identyfikatorze "'. html ($_GET['slug']) .'" został usunięty';
=======
# todo: uzyc action_del()
if (entry_del ($path)) {
    $_SESSION['msg'] = 'Wpis o identyfikatorze "'. html ($_GET['slug']) .'" został usunięty';
}
else {
    $_SESSION['msg'] = 'Nieudana próba usunięcia wpisu o identyfikatorze '. html ($_GET['slug']);
}
>>>>>>> origin/group2

header ('Location: /admin/');
exit;

