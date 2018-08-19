<?php
namespace Lucinda\Internationalization;

require_once("Settings.php");
require_once("TranslationInvalidException.php");
require_once("TranslationNotFoundException.php");

/**
 * Reads translations from JSON files located based on Settings info, each translation being a relationship between an identifying key 
 * and a value that stores the translation itself
 */
class Reader {
    private $translations = array();
    
    /**
     * Sets up reader based on user-defined internationalization settings.
     * 
     * @param Settings $settings Holds user-defined internationalization settings.
     */
    public function __construct(Settings $settings) {
        $this->setTranslations($settings);
    }
    
    /**
     * Reads translations from JSON files located based on Settings info, each translation being a relationship between an identifying key 
     * and a value that stores the translation itself
     * 
     * @param Settings $settings Object that encapsulates criteria to be used in detecting translation file.
     * @throws TranslationNotFoundException If no translation file was found
     * @throws TranslationInvalidException If translation file found is not convertible to JSON
     */
    private function setTranslations(Settings $settings) {
        $fileName = $settings->getFolder().DIRECTORY_SEPARATOR.$settings->getPreferredLocale().DIRECTORY_SEPARATOR.$settings->getDomain().".".$settings->getExtension();
        if(!file_exists($filename)) {
            throw new TranslationNotFoundException($fileName);        
        }
        $this->translations = json_decode(file_get_contents($fileName), true);
        if(json_last_error() != JSON_ERROR_NONE) {
            throw new TranslationInvalidException(json_last_error_msg());
        }
    }
    
    /**
     * Gets translations found.
     * 
     * @return array[string:string] List of translations by identifying key and value that stores the translation itself.
     */
    public function getTranslations() {
        return $this->translations;
    }
}