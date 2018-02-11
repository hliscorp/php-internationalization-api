<?php
namespace Lucinda\Internationalization;

/**
 * Detects locale based on user-defined settings and operating system
 */
class LocaleDetector
{
    private $locale;
    
    /**
     * @param Settings $settings User defined localization settings
     */
    public function __construct(Settings $settings) {
        $this->setLocale($settings);
    }
    
    /**
     * Performs locale detection.
     * 
     * @param Settings $settings
     */
    private function setLocale(Settings $settings) {
        if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $this->locale = $settings->getLanguage()."-".$settings->getCountry();
        } else {
            $this->locale = $settings->getLanguage()."_".$settings->getCountry();
            if($settings->getCharset()) {
                $this->locale .= ".".$settings->getCharset();
            }
        }
    }
    
    /**
     * Gets detected locale
     * 
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }
}

