<?php
namespace Lucinda\Internationalization;

require_once("Settings.php");
require_once("LocaleException.php");

/**
 * Performs operations required by GETTEXT utility in order to be able to locate then read from relevant MO translation file. 
 * 
 * Requires: gettext extension
 */
class Reader {
    private $settings;
    
    /**
     * Sets up reader based on user-defined internationalization settings.
     * 
     * @param Settings $settings Holds user-defined internationalization settings.
     */
    public function __construct(Settings $settings) {
        $this->settings = $settings;
        $this->setLocale();
        $this->setDomain();
    }
    
    /**
     * Sets server locale based on user defined settings and operating system.
     * 
     * @throws LocaleException If locale doesn't exist on server.
     */
    private function setLocale() {
        $locale = $this->settings->getLanguage()."_".$this->settings->getCountry();
        if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $success = putenv("LC_ALL=".$locale);
        } else {
            $success = setlocale(LC_ALL, $locale);
        }
        if(!$success) throw new LocaleException("Locale not recognized: ".$locale);
    }
    
    /**
     * Sets name of file (aka DOMAIN) that stores translations for current locale (by default "messages") and translation folder (aka DIRECTORY) 
     * in which that file is located relative to application root (by default "locale"). When gettext runs later on, translation will be located as:
     * {DIRECTORY}/{LOCALE}/LC_MESSAGES/{DOMAIN}.mo
     * Eg: locale/de_DE/LC_MESSAGES/messages.mo
     *
     * @throws LocaleException If translation file wasn't found on server.
     */
    private function setDomain() {
        $file = $this->settings->getFolder().DIRECTORY_SEPARATOR.$this->settings->getLanguage()."_".$this->settings->getCountry().DIRECTORY_SEPARATOR."LC_MESSAGES".DIRECTORY_SEPARATOR.$this->settings->getDomain().".mo";
        if(!file_exists($file)) {
            throw new LocaleException("Locale not found: ".$file);
        }
        
        bindtextdomain($this->settings->getDomain(), $this->settings->getFolder());
        textdomain($this->settings->getDomain());
        if($this->settings->getCharset()) {
            bind_textdomain_codeset($this->settings->getDomain(), $this->settings->getCharset());
        }
    }
}