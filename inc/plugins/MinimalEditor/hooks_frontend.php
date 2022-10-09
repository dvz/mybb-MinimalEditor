<?php

namespace MinimalEditor\Hooks;

function global_start(): void
{
    global $mybb;

    switch (\THIS_SCRIPT) {
        case 'calendar.php':
            if (in_array($mybb->get_input('action'), [
                'addevent',
                'editevent',
            ])) {
                \MinimalEditor\loadTemplates([
                    'codebuttons',
                ], 'MinimalEditor_');
            }

            break;
        case 'editpost.php':
        case 'newreply.php':
        case 'newthread.php':
            \MinimalEditor\loadTemplates([
                'codebuttons',
            ], 'MinimalEditor_');

            break;
        case 'private.php':
            if (in_array($mybb->get_input('action'), [
                'send',
                'do_send',
            ])) {
                \MinimalEditor\loadTemplates([
                    'codebuttons',
                ], 'MinimalEditor_');
            }

            break;
    }
}

function xmlhttp(): void
{
    global $mybb, $lang, $charset;

    if ($mybb->get_input('action') == 'get_preview') {
        if (!verify_post_check($mybb->get_input('my_post_key'), true)) {
            xmlhttp_error($lang->invalid_post_code);
        }

        require_once MYBB_ROOT . 'inc/class_parser.php';

        $parser = new \postParser;

        $parser_options = [
            'allow_html' => 0,
            'filter_badwords' => 1,
            'allow_mycode' => (bool)($mybb->input['options']['mycode'] ?? 0),
            'allow_smilies' => (bool)($mybb->input['options']['smilies'] ?? 0),
            'allow_imgcode' => (bool)($mybb->input['options']['imgcode'] ?? 0),
            'me_username' => $mybb->user['username'],
        ];

        $output = $parser->parse_message($mybb->get_input('message'), $parser_options);

        $response = [
            'output' => $output,
        ];

        header('Content-type: application/json; charset=' . $charset);

        echo json_encode($response);

        exit;
    }
}

function calendar_addevent_end(): void
{
    global $codebuttons, $calendar;

    if (empty($codebuttons)) {
        $codebuttons .= \MinimalEditor\getEditorHtml([
            'mycode' => $calendar['allowmycode'],
            'smilies' => $calendar['allowsmilies'],
            'imgcode' => $calendar['allowimgcode'],
        ]);
    }
}

function calendar_editevent_end(): void
{
    global $codebuttons, $calendar;

    if (empty($codebuttons)) {
        $codebuttons .= \MinimalEditor\getEditorHtml([
            'mycode' => $calendar['allowmycode'],
            'smilies' => $calendar['allowsmilies'],
            'imgcode' => $calendar['allowimgcode'],
        ]);
    }
}

function editpost_end(): void
{
    global $codebuttons, $forum;

    if (empty($codebuttons)) {
        $codebuttons .= \MinimalEditor\getEditorHtml([
            'mycode' => $forum['allowmycode'],
            'smilies' => $forum['allowsmilies'],
            'imgcode' => $forum['allowimgcode'],
        ]);
    }
}

function newreply_end(): void
{
    global $codebuttons, $forum;

    if (empty($codebuttons)) {
        $codebuttons .= \MinimalEditor\getEditorHtml([
            'mycode' => $forum['allowmycode'],
            'smilies' => $forum['allowsmilies'],
            'imgcode' => $forum['allowimgcode'],
        ]);
    }
}

function newthread_end(): void
{
    global $codebuttons, $forum;

    if (empty($codebuttons)) {
        $codebuttons .= \MinimalEditor\getEditorHtml([
            'mycode' => $forum['allowmycode'],
            'smilies' => $forum['allowsmilies'],
            'imgcode' => $forum['allowimgcode'],
        ]);
    }
}

function private_send_end(): void
{
    global $codebuttons, $mybb;

    if (empty($codebuttons)) {
        $codebuttons .= \MinimalEditor\getEditorHtml([
            'mycode' => $mybb->settings['pmsallowmycode'],
            'smilies' => $mybb->settings['pmsallowsmilies'],
            'imgcode' => $mybb->settings['pmsallowimgcode'],
        ]);
    }
}
