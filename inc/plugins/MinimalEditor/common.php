<?php

namespace MinimalEditor;

function addHooks(array $hooks, string $namespace = null): void
{
    global $plugins;

    if ($namespace) {
        $prefix = $namespace . '\\';
    } else {
        $prefix = null;
    }

    foreach ($hooks as $hook) {
        $plugins->add_hook($hook, $prefix . $hook);
    }
}

function addHooksNamespace(string $namespace): void
{
    global $plugins;

    $namespaceLowercase = strtolower($namespace);
    $definedUserFunctions = get_defined_functions()['user'];

    foreach ($definedUserFunctions as $callable) {
        $namespaceWithPrefixLength = strlen($namespaceLowercase) + 1;
        if (substr($callable, 0, $namespaceWithPrefixLength) == $namespaceLowercase . '\\') {
            $hookName = substr_replace($callable, null, 0, $namespaceWithPrefixLength);

            $priority = substr($callable, -2);

            if (is_numeric(substr($hookName, -2))) {
                $hookName = substr($hookName, 0, -2);
            } else {
                $priority = 10;
            }

            $plugins->add_hook($hookName, $callable, $priority);
        }
    }
}

function loadTemplates(array $templates, string $prefix = null): void
{
    global $templatelist;

    if (!empty($templatelist)) {
        $templatelist .= ',';
    }
    if ($prefix) {
        $templates = preg_filter('/^/', $prefix, $templates);
    }

    $templatelist .= implode(',', $templates);
}

function tpl(string $name): string
{
    global $templates;

    $templateName = 'MinimalEditor_' . $name;
    $directory = MYBB_ROOT . 'inc/plugins/MinimalEditor/templates/';

    if (DEVELOPMENT_MODE) {
        $templateContent = str_replace(
            "\\'",
            "'",
            addslashes(
                file_get_contents($directory . $name . '.tpl')
            )
        );

        if (!isset($templates->cache[$templateName]) && !isset($templates->uncached_templates[$templateName])) {
            $templates->uncached_templates[$templateName] = $templateName;
        }

        return $templateContent;
    } else {
        return $templates->get($templateName);
    }
}

function getFilesContentInDirectory(string $path, string $fileNameSuffix): array
{
    $contents = [];

    $directory = new \DirectoryIterator($path);

    foreach ($directory as $file) {
        if (!$file->isDot() && !$file->isDir()) {
            $templateName = $file->getPathname();
            $templateName = basename($templateName, $fileNameSuffix);
            $contents[$templateName] = file_get_contents($file->getPathname());
        }
    }

    return $contents;
}

function loadPluginLibrary(): void
{
    global $lang, $PL;

    $lang->load('MinimalEditor');

    if (!defined('PLUGINLIBRARY')) {
        define('PLUGINLIBRARY', MYBB_ROOT . 'inc/plugins/pluginlibrary.php');
    }

    if (!file_exists(PLUGINLIBRARY)) {
        flash_message($lang->MinimalEditor_admin_pluginlibrary_missing, 'error');

        admin_redirect('index.php?module=config-plugins');
    } elseif (!$PL) {
        require_once PLUGINLIBRARY;
    }
}
