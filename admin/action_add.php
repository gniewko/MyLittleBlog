<?php

require ("lib/config.php");

$subject = '';
$content = '';
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
        if (entry_add ($subject, $content)) {
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
<input type="submit" name="save" value="Zapisz" />
</div>
</form>
