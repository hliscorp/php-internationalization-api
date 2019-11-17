<?php
namespace Lucinda\Internationalization;

require("Settings.php");
require("TranslationInvalidException.php");
require("DomainNotFoundException.php");

/**
 * Reads translations from JSON files located based on Settings info, each translation being a relationship between an identifying key
 * and a value that stores the translation itself
 */
class Reader
{
    private static $settings;
    private static $instance;
    
    private $translations = array();
    
    /**
     * Injects client-specific internationalization settings to use in finding translations later on.
     *
     * @param Settings $settings Holds user-defined internationalization settings.
     */
    public static function setSettings(Settings $settings)
    {
        self::$settings = $settings;
    }
    
    /**
     * Gets a pointer to statically setup instance.
     *
     * @return Reader
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Reader();
        }
        return self::$instance;
    }
    
    /**
     * Reads all translations from domain file.
     *
     * @param string $domain Translation type (eg: house) reflecting into a file on disk.
     * @throws DomainNotFoundException If no translation file was found
     * @throws TranslationInvalidException If translation file found is not convertible to JSON
     */
    private function setTranslations($domain = null)
    {
        $fileName = self::$settings->getFolder().DIRECTORY_SEPARATOR.self::$settings->getPreferredLocale().DIRECTORY_SEPARATOR.$domain.".".self::$settings->getExtension();
        if (!file_exists($fileName)) {
            $fileName = self::$settings->getFolder().DIRECTORY_SEPARATOR.self::$settings->getDefaultLocale().DIRECTORY_SEPARATOR.$domain.".".self::$settings->getExtension();
            if (!file_exists($fileName)) {
                throw new DomainNotFoundException($domain);
            }
        }
        $translations = json_decode(file_get_contents($fileName), true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new TranslationInvalidException(json_last_error_msg());
        }
        $this->translations[$domain] = $translations;
    }
    
    /**
     * Gets a single translation from domain file.
     *
     * @param string $key Keyword by which translated value is accessible.
     * @param string $domain Translation type (eg: house) reflecting into a file on disk. If not supplied, default domain is used.
     * @throws DomainNotFoundException If no translation file was found
     * @throws TranslationInvalidException If translation file found is not convertible to JSON
     * @return string
     */
    public function getTranslation($key, $domain=null)
    {
        if (!$domain) {
            $domain = self::$settings->getDomain();
        }
        if (!isset($this->translations[$domain])) {
            $this->setTranslations($domain);
        }
        return (isset($this->translations[$domain][$key])?$this->translations[$domain][$key]:$key);
    }
}
