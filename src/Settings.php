<?php
namespace Lucinda\Internationalization;

/**
 * Encapsulates internationalization settings required in order to write to or read from translation files.
 */
class Settings
{
    private $locale;
    private $domain = "messages";
    private $folder = "locale";
    private $extension = "json";
    
    /**
     * @param string $Locale Country and language ISO codes (2) concatenated by _
     */
    public function __construct($Locale) {
        $this->setLocale($locale);
    }
    
    /**
     * Sets locale to be used when translating
     * 
     * @param string $locale Country and language ISO codes (2) concatenated by _
     */
    public function setLocale($locale) {
        $this->locale = $locale;
    }
    
    /**
     * Gets locale to be used when translating
     * 
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }
    
    /**
     * Sets name (aka "domain") of translation file.
     * 
     * @param string $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }
    
    /**
     * Gets name  of translation file (by default: messages)
     * 
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }
    
    /**
     * Sets folder in which translations are located
     *
     * @param string $folder
     */
    public function setFolder($folder) {
        $this->folder = $folder;
    }
    
    /**
     * Gets folder in which translations are located (by default: locale)
     *
     * @return string
     */
    public function getFolder() {
        return $this->folder;
    }
    
    /**
     * Sets extension of translation file (aka "domain").
     * 
     * @param string $extension
     */
    public function setExtension($extension) {
        $this->extension = $extension;
    }
    
    /**
     * Gets extension of translation file (aka "domain")
     * 
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }
}

