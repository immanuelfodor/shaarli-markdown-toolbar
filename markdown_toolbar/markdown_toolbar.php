<?php
/**
 * Shaarli Markdown Toolbar Plugin 
 *
 * @author Immánuel Fodor 
 * @link https://fodor.it
 */

use Shaarli\Config\ConfigManager;

/*
 * This is the plugin's default language.
 */
define("MD_TOOLBAR_DEFAULT_LOCALE", "en");

/**
 * Getting the locale from the configuration.
 * If unset or not valid, return the default value.
 *
 * @param $conf ConfigManager instance
 *
 * @return string the valid locale code.
 */
function get_valid_locale($conf) {
    $locales = ["en", "ar", "cs", "da", "de", "fa", "fr", "hu", "it", "ja", "kr", "nb", 
                "nl", "pl", "ptBR", "ru", "sl", "sv", "tr", "ua", "zh-tw", "zh"];
    $mdToolbarLocale = $conf->get('plugins.MD_TOOLBAR_LOCALE');

    if (empty($mdToolbarLocale) || ! in_array($mdToolbarLocale, $locales)) {
        $mdToolbarLocale = MD_TOOLBAR_DEFAULT_LOCALE;
    }

    return $mdToolbarLocale;
}

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
    $mdToolbarLocale = get_valid_locale($conf);

    if ($data['_PAGE_'] == Router::$PAGE_ADDLINK) {
        $data['edit_link_plugin'][] = "<!-- addlink -->";
    }

    $html = file_get_contents(PluginManager::$PLUGINS_PATH .'/markdown_toolbar/markdown_toolbar.html');
    $html = sprintf($html, $mdToolbarLocale);
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
        $data['css_files'][] = $include_dir . '/bootstrap/dist/css/bootstrap-pruned.min.css';
        $data['css_files'][] = $include_dir . '/font_awesome/css/font-awesome.min.css';
        $data['css_files'][] = $include_dir . '/bootstrap_markdown/css/bootstrap-markdown.min.css';
    }

    return $data;
}

/**
 * When editlink page is displayed, include markdown_toolbar JS files.
 *
 * @param $data array         footer data.
 * @param $conf ConfigManager instance
 * 
 * @return mixed - footer data with markdown_toolbar JS files added.
 */
function hook_markdown_toolbar_render_footer($data, $conf)
{
    if ($data['_PAGE_'] == Router::$PAGE_EDITLINK) {
        $mdToolbarLocale = get_valid_locale($conf);
        $include_dir = PluginManager::$PLUGINS_PATH . '/markdown_toolbar/includes';

        $data['js_files'][] = $include_dir . '/jquery/jquery-3.2.1.min.js';
        $data['js_files'][] = $include_dir . '/bootstrap_markdown/js/bootstrap-markdown.js';
        $data['js_files'][] = $include_dir . '/markdown_toolbar.js';
        
        if ($mdToolbarLocale != MD_TOOLBAR_DEFAULT_LOCALE) {
            $data['js_files'][] = $include_dir . '/bootstrap_markdown/locale/bootstrap-markdown.' . $mdToolbarLocale . '.js';
        }
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
    t('Language code for the markdown editor. Default: en');
}
