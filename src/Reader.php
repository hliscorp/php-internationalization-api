<?php
namespace Lucinda\Internationalization;

require_once("Settings.php");
require_once("LocaleException.php");
require_once("LocaleDetector.php");

/**
 * Performs operations required by GETTEXT utility in order to be able to locate then read from relevant MO translation file. 
 * 
 * Requires: gettext extension (if WIN, download it from http://gnuwin32.sourceforge.net/packages/gettext.htm)
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
     * Sets server locale based on user defined settings and operating system. If operating system is:
     * - unix: locale is lowercase ISO language code concatenated with "_" then uppercase ISO country code (eg: en_US)
     * - windows:  locale is lowercase ISO language code concatenated with "-" then uppercase ISO country code (eg: en-US)
     * 
     * @throws LocaleException If locale doesn't exist on server.
     */
    private function setLocale() {
        $detector = new LocaleDetector($this->settings);
        putenv("LC_ALL=".$detector->getLocale());
        $success = setlocale(LC_ALL, $detector->getLocale());
        if(!$success) throw new LocaleException("Locale not recognized: ".$detector->getLocale());
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
        bindtextdomain($this->settings->getDomain(), $this->settings->getFolder());
        textdomain($this->settings->getDomain());
        if($this->settings->getCharset()) {
            bind_textdomain_codeset($this->settings->getDomain(), $this->settings->getCharset());
        }
    }
}