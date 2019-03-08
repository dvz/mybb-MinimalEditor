<?php

namespace MinimalEditor;

function getEditorHtml(): string
{
    global $mybb, $lang, $parser;

    $lang->load('MinimalEditor');

    if (!empty($parser)) {
        $options = [
            'mycode' => $parser->options['allow_mycode'] ?? false,
            'smilies' => $parser->options['allow_smilies'] ?? false,
            'imgcode' => $parser->options['allow_imgcode'] ?? false,
        ];
    } else {
        $options = [];
    }

    $optionsJson = htmlspecialchars_uni(json_encode($options));

    eval('$html = "' . \MinimalEditor\tpl('codebuttons') . '";');

    return $html;
}
