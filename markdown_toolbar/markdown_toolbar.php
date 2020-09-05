<?php

/**
 * Shaarli Markdown Toolbar Plugin 
 *
 * @author Immánuel Fodor 
 * @link https://fodor.it
 */

use Shaarli\Router;
use Shaarli\Legacy\LegacyRouter;
use Shaarli\Plugin\PluginManager;
use Shaarli\Config\ConfigManager;

/**
 * This is the plugin's default language.
 */
define("MD_TOOLBAR_DEFAULT_LOCALE", "en");

/**
 * Get the available Shaarli router class.
 * Keeps compatibility with older Shaarlies besides supporting the new Slim rewrite.
 * 
 * @see: https://github.com/shaarli/Shaarli/pull/1511
 * @see: https://github.com/ilesinge/shaarli-related/pull/4/files
 * 
 * @return string - the namespaced router class name
 */
function mdtb_get_router()
{
    /** introduced with the Slim rewrite of the recent Shaarli */
    $newShaarliRouter = 'Shaarli\Legacy\LegacyRouter';
    /** original router class of old Shaarlies */
    $oldShaarliRouter = 'Shaarli\Router';

    if (class_exists($newShaarliRouter)) {
        return $newShaarliRouter;
    }

    return $oldShaarliRouter;
}

/**
 * Get plugin assets base path
 * 
 * @param array $data - hook data
 * 
 * @return string - the basepath of the assets
 */
function mdtb_get_basepath($data) {
    return ($data['_BASE_PATH_'] ?? '') . '/' . PluginManager::$PLUGINS_PATH;
}

/**
 * Getting the locale from the configuration.
 * If unset or not valid, return the default value.
 *
 * @param ConfigManager $conf - configmanager instance
 *
 * @return string - the valid locale code
 */
function mdtb_get_valid_locale($conf)
{
    $locales = [
        "en", "ar", "cs", "da", "de", "fa", "fr", "hu", "it", "ja", "kr", "nb",
        "nl", "pl", "ptBR", "ru", "sl", "sv", "tr", "ua", "zh-tw", "zh"
    ];
    $mdToolbarLocale = $conf->get('plugins.MD_TOOLBAR_LOCALE');

    if (empty($mdToolbarLocale) || !in_array($mdToolbarLocale, $locales)) {
        $mdToolbarLocale = MD_TOOLBAR_DEFAULT_LOCALE;
    }

    return $mdToolbarLocale;
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
    $router = mdtb_get_router();

    if ($data['_PAGE_'] == $router::$PAGE_EDITLINK) {
        $include_dir = mdtb_get_basepath($data) . '/markdown_toolbar/includes';
        $data['css_files'][] = $include_dir . '/bootstrap/dist/css/bootstrap-pruned.min.css';
        $data['css_files'][] = $include_dir . '/font_awesome/css/font-awesome.min.css';
        $data['css_files'][] = $include_dir . '/bootstrap_markdown/css/bootstrap-markdown.min.css';
    }

    return $data;
}

/**
 * When editlink page is displayed, include markdown_toolbar JS files.
 *
 * @param array $data - footer data.
 * @param ConfigManager $conf - configmanager instance
 * 
 * @return mixed - footer data with markdown_toolbar JS files added.
 */
function hook_markdown_toolbar_render_footer($data, $conf)
{
    $router = mdtb_get_router();

    if (!in_array($data['_PAGE_'], [$router::$PAGE_ADDLINK, $router::$PAGE_EDITLINK])) {
        return $data;
    }

    if ($data['_PAGE_'] == $router::$PAGE_ADDLINK) {
        $mdToolbarAutofocus = "true";
    } else {
        $mdToolbarAutofocus = "false";
    }
    // There is a bug in (Legacy)Router::findPage() that is why the above condition is never true, the page is
    // always stated as editlink even if it is addlink. So I'm setting this to always true for now.
    $mdToolbarAutofocus = "true";
    $mdToolbarLocale = mdtb_get_valid_locale($conf);

    $html = file_get_contents(PluginManager::$PLUGINS_PATH . '/markdown_toolbar/markdown_toolbar.html');
    $html = sprintf($html, $mdToolbarLocale, $mdToolbarAutofocus);
    $data['endofpage'][] = $html;

    $include_dir = mdtb_get_basepath($data) . '/markdown_toolbar/includes';
    $data['js_files'][] = $include_dir . '/jquery/jquery-3.2.1.min.js';
    $data['js_files'][] = $include_dir . '/markdown_toolbar.js';
    $data['js_files'][] = $include_dir . '/bootstrap_markdown/js/bootstrap-markdown.js';

    if ($mdToolbarLocale != MD_TOOLBAR_DEFAULT_LOCALE) {
        $data['js_files'][] = $include_dir . '/bootstrap_markdown/locale/bootstrap-markdown.' . $mdToolbarLocale . '.js';
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
    t('Localization of the editor. Default: en');
}
