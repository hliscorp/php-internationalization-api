<?php
namespace Lucinda\Internationalization;

require_once("Settings.php");
require_once("TranslationInvalidException.php");
require_once("DomainNotFoundException.php");

/**
 * Reads translations from JSON files located based on Settings info, each translation being a relationship between an identifying key 
 * and a value that stores the translation itself
 */
class Reader {
    private static $instance;
    
    private $settings;
    private $useOnlyDefaultLocale;    
    private $translations = array();
    
    /**
     * Sets up reader instance statically based on user-defined internationalization settings.
     *
     * @param Settings $settings Holds user-defined internationalization settings.
     * @param boolean $useOnlyDefaultLocale Signals to use only default locale since preferred is not supported.
     */
    public static function setInstance(Settings $settings, $useOnlyDefaultLocale) {
        self::$instance = new Reader($settings, $useOnlyDefaultLocale);
    }
    
    /**
     * Gets a pointer to statically setup instance.
     * 
     * @return \Lucinda\Internationalization\Reader
     */
    public static function getInstance() {
        return self::$instance;
    }
    
    /**
     * Sets up reader based on user-defined internationalization settings.
     * 
     * @param Settings $settings Holds user-defined internationalization settings.
     * @param boolean $useOnlyDefaultLocale Signals to use only default locale since preferred is not supported.
     */
    public function __construct(Settings $settings, $useOnlyDefaultLocale) {
        $this->settings = $settings;
        $this->useOnlyDefaultLocale = $useOnlyDefaultLocale;
    }
    
    /**
     * Gets translations from domain file.
     * 
     * @param string $domain Translation type (eg: house) reflecting into a file on disk. If not supplied, default domain is used.
     * @throws DomainNotFoundException If no translation file was found
     * @throws TranslationInvalidException If translation file found is not convertible to JSON
     * @return array[string:string] Dictionary of translations found by key.
     */
    public function getTranslations($domain) {
        if(!$domain) {
            $domain = $this->settings->getDomain();
        }
        if(!isset($this->translations[$domain])) {
            if($this->useOnlyDefaultLocale) {
                $fileName = $this->settings->getFolder().DIRECTORY_SEPARATOR.$this->settings->getDefaultLocale().DIRECTORY_SEPARATOR.$domain.".".$this->settings->getExtension();
                if(!file_exists($fileName)) {
                    throw new DomainNotFoundException($domain);
                }
            } else {
                $fileName = $this->settings->getFolder().DIRECTORY_SEPARATOR.$this->settings->getPreferredLocale().DIRECTORY_SEPARATOR.$domain.".".$this->settings->getExtension();
                if(!file_exists($fileName)) {
                    $fileName = $this->settings->getFolder().DIRECTORY_SEPARATOR.$this->settings->getDefaultLocale().DIRECTORY_SEPARATOR.$domain.".".$this->settings->getExtension();
                    if(!file_exists($fileName)) {
                        throw new DomainNotFoundException($domain);
                    }
                }
            }
            $translations = json_decode(file_get_contents($fileName), true);
            if(json_last_error() != JSON_ERROR_NONE) {
                throw new TranslationInvalidException(json_last_error_msg());
            }
            $this->translations[$domain] = $translations;
        }
        return $this->translations[$domain];
    }
}