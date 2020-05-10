<?php

// core files
require MYBB_ROOT . 'inc/plugins/MinimalEditor/common.php';
require MYBB_ROOT . 'inc/plugins/MinimalEditor/core.php';

// hook files
require MYBB_ROOT . 'inc/plugins/MinimalEditor/hooks_frontend.php';

// init
define('MinimalEditor\DEVELOPMENT_MODE', 0);

// hooks
\MinimalEditor\addHooksNamespace('MinimalEditor\Hooks');

function MinimalEditor_info()
{
    global $lang;

    $lang->load('MinimalEditor');

    return [
        'name'          => 'MinimalEditor',
        'description'   => $lang->MinimalEditor_description,
        'website'       => '',
        'author'        => 'Tomasz \'Devilshakerz\' Mlynski',
        'authorsite'    => 'https://devilshakerz.com/',
        'version'       => '0.1.2',
        'codename'      => 'minimal_editor',
        'compatibility' => '18*',
    ];
}

function MinimalEditor_activate()
{
    global $PL;

    \MinimalEditor\loadPluginLibrary();

    // templates
    $PL->templates(
        'MinimalEditor',
        'MinimalEditor',
        \MinimalEditor\getFilesContentInDirectory(MYBB_ROOT . 'inc/plugins/MinimalEditor/templates', '.tpl')
    );

    // stylesheets
    $stylesheets = [
        'MinimalEditor' => [
            'attached_to' => [
                'calendar.php' => [
                    'addevent',
                    'editevent',
                ],
                'editpost.php' => [],
                'newreply.php' => [],
                'newthread.php' => [],
                'private.php' => [
                    'send',
                    'do_send',
                ],
            ],
        ],
    ];

    foreach ($stylesheets as $stylesheetName => $stylesheet) {
        $PL->stylesheet(
            $stylesheetName,
            file_get_contents(MYBB_ROOT . 'inc/plugins/MinimalEditor/stylesheets/' . $stylesheetName . '.css'),
            $stylesheet['attached_to']
        );
    }
}

function MinimalEditor_deactivate()
{
    global $PL;

    \MinimalEditor\loadPluginLibrary();

    // templates
    $PL->templates_delete('MinimalEditor', true);

    // stylesheets
    $PL->stylesheet_delete('MinimalEditor', true);
}
