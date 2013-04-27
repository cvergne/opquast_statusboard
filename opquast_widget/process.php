<?php
    // @LANG
    /*
    $_lang = CONFIG_DEFAULT_LANG;
    if (isset($_GET['lang']) && in_array($_GET['lang'], $config_langs)) {
        $_lang = $_GET['lang'];
    }
    $_langISO = $config_langs_iso[$_lang];
    */

    // @FILE
    /*
    $_filename = CONFIG_DEFAULT_FILENAME . $_lang;
    $_file = CONFIG_DATASOURCES_DIR . $_filename . '.csv';
    if (isset($_GET['src'])) {
        $temp_filename = filter_var($_GET['src'], FILTER_CALLBACK, array('options' => 'filter_filename')) . '-' . $_lang;
        $temp_file = CONFIG_DATASOURCES_DIR . $temp_filename . '.csv';
        if (file_exists($temp_file)) {
            $_file = $temp_file;
            $_filename = $temp_filename;
        }
    }
    */

    // @FOLDER
    if ($handle = opendir(CONFIG_DATASOURCES_DIR)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != '.' && $entry != '..') {
                $_filename = pathinfo($entry);
                $_filename = $_filename['filename'];
                $_file = CONFIG_DATASOURCES_DIR . $entry;

                $_lang = CONFIG_DEFAULT_LANG;
                if (preg_match('/([A-Za-z]{2})$/', $_filename, $matches)) {
                    $_lang = $matches[0];
                }

                $_langISO = $config_langs_iso[$_lang];

                // @TARGET
                $_target = CONFIG_DATATARGET_DIR . $_filename . '.rss';

                // @GROUPBY
                $_groupby = CONFIG_DEFAULT_GROUPBY;
                if (isset($_GET['groupby']) && in_array($_GET['groupby'], array_keys($config_groupbys))) {
                    $_groupby = $config_groupbys[$_GET['groupby']];
                }

                if (isset($_file) && file_exists($_file)) {
                    if ($_sourcefile = fopen($_file, "r")) {
                        while (($data = fgetcsv($_sourcefile, 5000, ",")) !== FALSE) {
                            if (!isset($out[$data[$_groupby]])) {
                                $out[$data[$_groupby]] = array();
                            }

                            $out[$data[$_groupby]][$data[0]] = array(
                                'num' => $data[0],
                                'category' => $data[1],
                                'level' => $data[2],
                                'title' => $data[3],
                                'url_id' => sprintf("%03d", 499 + $data[0])
                            );
                        }
                    }
                }

                $result = '';
                if (isset($out)) {
                    // header("Content-Type: application/rss+xml; charset=UTF-8");
                    $result .= '<?xml version="1.0" encoding="UTF-8"?><rss version="2.0"><channel>';
                        $result .= '<title>Opquast - ' . strtoupper($_lang) . '</title>';
                        $result .= '<link>https://checklists.opquast.com/' . $_lang . '/opquastv2</link>';
                        $result .= '<lang>' . $_langISO . '</lang>';
                        $result .= '<copyright>Opquast - http://creativecommons.org/licenses/by-sa/2.0/fr/</copyright>';
                        foreach ($out as $groupName => $groupContent) {
                            foreach ($groupContent as $num => $content) {
                                $_uri = 'https://checklists.opquast.com/' . $_lang . '/oqsv2/criteria/' . $content['url_id'] . '/';
                                $result .= '
                                    <item>
                                        <title>#' . $content['num'] . ' - ' . $content['category'] . ' - ' . $content['title'] . '</title>
                                        <link>' . $_uri . '</link>
                                        <category>' . $content['category'] . '</category>
                                        <guid isPermaLink="true">' . $_uri . '</guid>
                                    </item>';
                            }
                        }
                    $result .= '</channel></rss>';

                    file_put_contents($_target, $result);
                }
            }
        }
    }

    header('Location: ./index.php');