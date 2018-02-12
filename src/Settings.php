<?php
namespace Lucinda\Internationalization;

/**
 * Encapsulates internationalization settings required in order to write to or read from MO translation files.
 */
class Settings
{
    private $domain = "messages";
    private $charset = "UTF-8";
    private $locale;
    private $folder = "locale";
    
    /**
     * @param string $locale Usually country and language ISO codes (2) concatenated by _
     */
    public function __construct($locale) {
        $this->setLocale($locale);
    }
    
    /**
     * Sets locale.
     * 
     * @param string $locale Usually country and language ISO codes (2) concatenated by _
     */
    public function setLocale($locale) {
        $this->locale = $locale;
    }
    
    /**
     * Gets locale.
     * 
     * @return string
     */
    public function getLocale() {
        return $this->locale;
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

