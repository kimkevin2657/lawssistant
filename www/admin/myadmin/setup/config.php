<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Front controller for config view / download and clear
 *
 * @package PhpMyAdmin-Setup
 */

/**
 * Core libraries.
 */
require './lib/common.inc.php';
require_once './libraries/config/Form.class.php';
require_once './libraries/config/FormDisplay.class.php';
require_once './setup/lib/ConfigGenerator.class.php';

require './libraries/config/setup.forms.php';

/**
 * Loads configuration file path
 *
 * Do this in a function to avoid messing up with global $cfg
 *
 * @param string $config_file_path
 *
 * @return array
 */
function loadConfig($config_file_path)
{
    $cfg = array();
    if (file_exists($config_file_path)) {
        include $config_file_path;
    }
    return $cfg;
}

$form_display = new FormDisplay();
$form_display->registerForm('_config.php', $forms['_config.php']);
$form_display->save('_config.php');
$config_file_path = ConfigFile::getInstance()->getFilePath();

if (isset($_POST['eol'])) {
    $_SESSION['eol'] = ($_POST['eol'] == 'unix') ? 'unix' : 'win';
}

if (PMA_ifSetOr($_POST['submit_clear'], '')) {
    //
    // Clear current config and return to main page
    //
    ConfigFile::getInstance()->resetConfigData();
    // drop post data
    header('HTTP/1.1 303 See Other');
    header('Location: index.php');
    exit;
} elseif (PMA_ifSetOr($_POST['submit_download'], '')) {
    //
    // Output generated config file
    //
    PMA_downloadHeader('config.inc.php', 'text/plain');
    echo ConfigGenerator::getConfigFile();
    exit;
} elseif (PMA_ifSetOr($_POST['submit_save'], '')) {
    //
    // Save generated config file on the server
    //
    $result = @file_put_contents(
        $config_file_path,
        ConfigGenerator::getConfigFile()
    );
    if ($result === false) {
        $state = 'config_not_saved';
    } else {
        $state = 'config_saved';
    }
    header('HTTP/1.1 303 See Other');
    header('Location: index.php?action_done=' . $state);
    exit;
} elseif (PMA_ifSetOr($_POST['submit_load'], '')) {
    //
    // Load config file from the server
    //
    ConfigFile::getInstance()->setConfigData(
        loadConfig($config_file_path)
    );
    header('HTTP/1.1 303 See Other');
    header('Location: index.php');
    exit;
} elseif (PMA_ifSetOr($_POST['submit_delete'], '')) {
    //
    // Delete config file on the server
    //
    @unlink($config_file_path);
    header('HTTP/1.1 303 See Other');
    header('Location: index.php');
    exit;
} else {
    //
    // Show generated config file in a <textarea>
    //
    header('HTTP/1.1 303 See Other');
    header('Location: index.php?page=config');
    exit;
}
?>
