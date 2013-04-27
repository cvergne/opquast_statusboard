<?php
    require_once('./config.php');

    if ($handle = opendir(CONFIG_DATATARGET_DIR)) {
        echo '<ul>';
        while (false !== ($entry = readdir($handle))) {
            if ($entry != '.' && $entry != '..') {
                echo '<li><a href="panicboard://?url=' . urlencode(getURL($entry)) . '&panel=rss
&sourceDisplayName=' . urlencode(CONFIG_SOURCE_PROVIDER) . '">' . $entry . '</a></li>';
            }
        }
        echo '</ul>';
    }