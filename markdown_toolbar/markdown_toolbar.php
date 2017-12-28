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
    $html = file_get_contents(PluginManager::$PLUGINS_PATH . '/markdown_toolbar/markdown_toolbar.html');
    $data['edit_link_plugin'][] = $html;

    return $data;
}

/**
 * When editlink page is displayed, include markdown_toolbar CSS files.
 *
 * @param array $data - header data.
 *
 * @return mixed - header data with markdown_toolbar CSS files added.
 */
function hook_markdown_toolbar_render_includes($data)
{
    if ($data['_PAGE_'] == Router::$PAGE_EDITLINK) {
        $include_dir = PluginManager::$PLUGINS_PATH . '/markdown_toolbar/includes';
        $data['css_files'][] = $include_dir . '/bootstrap/dist/css/bootstrap.min.css';
        $data['css_files'][] = $include_dir . '/font_awesome/css/font-awesome.min.css';
        $data['css_files'][] = $include_dir . '/bootstrap_markdown/css/bootstrap-markdown.min.css';
    }

    return $data;
}

/**
 * When editlink page is displayed, include markdown_toolbar JS files.
 *
 * @param array $data - footer data.
 *
 * @return mixed - footer data with markdown_toolbar JS files added.
 */
function hook_markdown_toolbar_render_footer($data)
{
    if ($data['_PAGE_'] == Router::$PAGE_EDITLINK) {
        $include_dir = PluginManager::$PLUGINS_PATH . '/markdown_toolbar/includes';
        $data['js_files'][] = $include_dir . '/jquery/jquery-3.2.1.min.js';
        $data['js_files'][] = $include_dir . '/bootstrap_markdown/js/bootstrap-markdown.js';
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
}
