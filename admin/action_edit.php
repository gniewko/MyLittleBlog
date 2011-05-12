<?php

if (!isset ($_REQUEST['slug'])) {
    $_SESSION['msg'] = 'Brak identyfikatora wpisu';
    header ('Location: /admin/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entry = array (
        'subject'   => $_POST['subject'],
        'content'   => $_POST['content']
    );
}
else {
    $entry = entry_read ($_REQUEST['slug']);
}

if (!$entry) {
    $_SESSION['msg'] = 'Nie znaleziono wpisu o identyfikatorze "'. html ($_REQUEST['slug']) .'"';
    header ('Location: /admin/');
    exit;
}

$subject = $entry['subject'];
$content = $entry['content'];
$errors  = array ();

if (isset ($_POST['subject'])) {
    $subject = $_POST['subject'];
}

if (isset ($_POST['content'])) {
    $content = $_POST['content'];
}

if (isset ($_POST['save'])) {
    if (strlen ($subject) < TITLE_LENGTH) {
        $errors[] = 'Temat nie może być krótszy niż 3 znaki';
    }
    if (strlen ($content) < BODY_LENGTH) {
        $errors[] = 'Treść nie może być krótsza niż 10 znaków';
    }

    if (!count ($errors)) {
        if (entry_edit ($_REQUEST['slug'], $subject, $content)) {
            $_SESSION['msg'] = 'Post zapisany poprawnie';
            header ('Location: /admin/');
            exit;
        }
        else {
            $errors[] = 'Nie udało się zapisać wpisu';
        }
    }
}

?><form method="post">
<ul>
<?php
foreach ($errors as $error) {
    echo "<li>błąd: $error</li>\n";
}
?>
</ul>
<div>
<label for="subject"><input type="text" name="subject" id="subject" value="<?php echo $subject; ?>" /></label><br />
<label for="content"><textarea name="content" id="content"><?php echo $content; ?></textarea></label><br />
<input type="hidden" name="slug" value="<?php echo html ($_REQUEST['slug']) ?>" />
<input type="submit" name="save" value="Zapisz" />
</div>
</form>
