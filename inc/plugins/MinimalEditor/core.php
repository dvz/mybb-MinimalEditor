<?php

namespace MinimalEditor;

function getEditorHtml(array $options = []): string
{
    global $mybb, $lang, $parser;

    $lang->load('MinimalEditor');

    $optionsJson = htmlspecialchars_uni(json_encode($options));

    eval('$html = "' . \MinimalEditor\tpl('codebuttons') . '";');

    return $html;
}
