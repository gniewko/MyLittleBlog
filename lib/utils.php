<?php

function slug ($string, $non_uniq=false) {
    $string = strtr ($string, "ążśźęćńłóĄŻŚŹĘĆŃŁÓ \t\r\n", 'azszecnloAZSZECNLO____');
    $string = preg_replace ('/_{1,}/', '_', $string);
    $string = preg_replace ('/[^a-zA-Z0-9_,.-]/', '', $string);

    if (!$non_uniq) {
        $i = '';
        while (file_exists (slug_to_path ($string . $i))) {
            ++$i;
        }
        if (is_numeric ($i) && $i > 0) {
            $string .= $i;
        }
    }

    return $string;
}

function slug_to_path ($slug) {
    return DB_PATH . $slug . '.txt';
}

function entry_add ($subject, $content, $tags=array ()) {
    $path  = slug_to_path (slug ($subject));
    return file_put_contents ($path, $subject ."\n". (is_array ($tags) ? join (chr (0), $tags) : $tags) . "\n" . $content);
}

function entry_edit ($slug, $subject, $content, $tags=array ()) {
    $path = slug_to_path ($slug);
    $tags = (is_array ($tags) ? join (chr (0), $tags) : $tags);
    if (!file_put_contents ($path, $subject ."\n". $tags . "\n" . $content)) {
        return false;
    }

    $new_slug = slug ($subject, true);
    if ($slug == $new_slug) {
        return 1;
    }

    $new_slug = slug ($subject);
    $new_path = slug_to_path ($new_slug);
    return rename ($path, $new_path);
}

function entry_read ($slug) {
    $path = slug_to_path ($slug);
    if (!file_exists ($path)) {
        return;
    }
    $data = file_get_contents ($path);
    list ($subject, $tags, $content) = explode ("\n", $data, 3);
    return array ('subject' => $subject, 'content' => $content, 'tags' => (strlen ($tags) ? explode (chr (0), $tags) : array ()));
}

function entry_del ($slug) {
    $path = slug_to_path ($slug);
    return @unlink ($path);
}

function entries_sorter ($field) {
    return create_function ('$a, $b', "
        if (\$a['$field'] == \$b['$field']) {
            return 0;
        }
        return \$a['$field'] < \$b['$field'] ? 1 : -1;
    ");
}

function entry_list ($mask=null, $sort_by='slug') {
    $list = glob (DB_PATH. (!is_null ($mask) ? "/$mask.txt" : '/*.txt'), GLOB_MARK);
    $ret = array ();
    foreach ($list as $entry_path) {
        $slug  = substr (basename ($entry_path), 0, -4);
        $entry = entry_read ($slug);
        $ret[] = array (
            'date_add'  => filectime ($entry_path),
            'date_mod'  => filemtime ($entry_path),
            'path'      => $entry_path,
            'size'      => filesize ($entry_path),
            'slug'      => $slug,
            'subject'   => $entry['subject'],
        );
    }

    $reverse = 0;
    if (strpos ($sort_by, '!') === 0) {
        $reverse = 1;
        $sort_by = substr ($sort_by, 1);
    }

    if ($sort_by != 'slug') {
        uasort ($ret, entries_sorter ($sort_by));
    }

    if ($reverse) {
        $ret = array_reverse ($ret);
    }

    return $ret;
}

function html ($string) {
    return htmlspecialchars ($string, ENT_QUOTES, 'UTF-8');
}
