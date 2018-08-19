<?php
namespace Lucinda\Internationalization;

require_once("Settings.php");
require_once("Reader.php");

/**
 * Writes translations to JSON files located based on Settings info, each translation being a relationship between an identifying key
 * and a value that stores the translation itself
 */
class Writer
{
    private $translations = array();
    
    /**
     * Sets up writer based on user-defined internationalization settings.
     * 
     * @param Settings $settings
     */
    public function __construct(Settings $settings) {
        $this->readFile($settings);
    }
    
    /**
     * Gets existing translations from JSON file located based on Settings info. Creates folder that will store translations, if former doesn't exist.
     * 
     * @param Settings $settings
     */
    private function readFile(Settings $settings) {
        $this->file = $settings->getFolder().DIRECTORY_SEPARATOR.$settings->getPreferredLocale().DIRECTORY_SEPARATOR.$settings->getDomain().".".$settings->getExtension();
        
        try {
            $reader = new Reader($settings);
            $this->translations = $reader->getTranslations();
        } catch(TranslationNotFoundException $e) {
            $folder = dirname($this->file);
            if(!file_exists($folder)) {
                mkdir(dirname($this->file), 0755, true);
            }
        }
    }
    
    /**
     * Adds or edits a translation
     * 
     * @param string $key Locale unspecific unique identifier of translated text.
     * @param string $value Body of translation itself.
     */
    public function setTranslation($key, $value) {
        $this->translations[$key] = $value;
    }
    
    /**
     * Removes a translation
     *
     * @param string $key Locale unspecific unique identifier of translated text.
     */
    public function unsetTranslation($key) {
        unset($this->translations[$key]);
    }
    
    /**
     * Persists changes to translation file.
     */
    public function saveFile() {
        file_put_contents($this->file, json_encode($this->translations));
    }
}
