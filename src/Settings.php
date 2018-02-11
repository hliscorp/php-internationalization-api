<?php
namespace Internationalization;

/**
 * Encapsulates internationalization settings required in order to write to or read from MO translation files.
 */
class Settings
{
    private $domain = "messages";
    private $charset = "UTF-8";
    private $language;
    private $country;
    private $folder = "locale";
    
    /**
     * @param string $language ISO language code of 2 characters.
     * @param string $country ISO country code of 2 characters.
     */
    public function __construct($language, $country) {
        $this->setLanguage($language);
        $this->setCountry($country);
    }
    
    /**
     * Sets translation language.
     * 
     * @param string $language ISO language code of 2 characters.
     * @throws LocaleException If language is improperly formatted.
     */
    public function setLanguage($language) {
        if(strlen($language)!=2) throw new LocaleException("Language must be ISO CODE (2)");
        $this->language = strtolower($language);
    }
    
    /**
     * Gets translation language.
     * 
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }
    
    /**
     * Sets country localization.
     *
     * @param string $country ISO country code of 2 characters.
     * @throws LocaleException If country is improperly formatted.
     */
    public function setCountry($country) {
        if(strlen($country)!=2) throw new LocaleException("Language must be ISO CODE (2)");
        $this->country = strtoupper($country);
    }
    
    /**
     * Gets country localization.
     *
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }
    
    /**
     * Sets translation charset.
     * 
     * @param string $charset
     */
    public function setCharset($charset) {
        $this->charset = $charset;
    }
    
    /**
     * Gets translation charset (by default: UTF-8).
     * 
     * @return string
     */
    public function getCharset() {
        return $this->charset;
    }
    
    /**
     * Sets name (aka "domain") of MO translation file.
     * 
     * @param string $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }
    
    /**
     * Gets name  of MO translation file (by default: messages)
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
}

