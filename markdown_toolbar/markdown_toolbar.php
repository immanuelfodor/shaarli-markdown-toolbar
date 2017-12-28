<?php
/**
 * Shaarli Markdown Toolbar Plugin 
 *
 * @author Immánuel Fodor 
 * @link https://fodor.it
 */

use Shaarli\Config\ConfigManager;

/**
 * Injecting our Javascript code to the editlink page.
 * 
 * Hook render_editlink.
 *
 * Template placeholders:
 *   - edit_link_plugin: add fields after tags.
 *
 * @param $data array         data passed to plugin
 * @param $conf ConfigManager instance
 *
 * @return array altered $data.
 */
function hook_markdown_toolbar_render_editlink($data, $conf)
{


    return $data;
}


/**
 * When linklist is displayed, include markdown_toolbar JS files.
 *
 * @param array $data - footer data.
 *
 * @return mixed - footer data with markdown_toolbar JS files added.
 */
function hook_markdown_toolbar_render_footer($data)
{
    if ($data['_PAGE_'] == Router::$PAGE_EDITLINK) {
        $data['js_files'][] = PluginManager::$PLUGINS_PATH . '/markdown_toolbar/shaarli-markdown_toolbar.js';
    }

    return $data;
}

/**
 * When linklist is displayed, include markdown_toolbar CSS file.
 *
 * @param array $data - header data.
 *
 * @return mixed - header data with markdown_toolbar CSS file added.
 */
function hook_markdown_toolbar_render_includes($data)
{
    if ($data['_PAGE_'] == Router::$PAGE_EDITLINK) {
        $data['css_files'][] = PluginManager::$PLUGINS_PATH . '/markdown_toolbar/markdown_toolbar.css';
    }

    return $data;
}


/**
 * This function is never called, but contains translation calls for GNU gettext extraction.
 */
function markdown_toolbar_dummy_translation()
{
    // meta
    t('Easily insert markdown syntax into the Description field when editing a link.');
    t('Hight of the Description textarea. Default: 4');
}
