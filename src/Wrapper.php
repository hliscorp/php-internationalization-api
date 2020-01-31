<?php
namespace Lucinda\Internationalization;

/**
 * Reads internationalization tag in order to detect locale settings to be used by reader/writer
 */
class Wrapper
{
    private $reader;
    private $writer;
    
    /**
     * Sets up Reader instance to use later on in automatic translation based on XML and client headers
     *
     * @param \SimpleXMLElement $xml XML root containing 'internationalization' tag
     * @param string[string] $requestParameters
     * @param string[string] $requestHeaders
     * @throws ConfigurationException If XML is improperly configured or session is not started
     */
    public function __construct(\SimpleXMLElement $xml, array $requestParameters, array $requestHeaders)
    {
        // parses XML
        $xml = $xml->internationalization;
        if (empty($xml)) {
            throw new ConfigurationException("Tag 'internationalization' missing");
        }
        
        // identifies settings based on XML and sets preferred locale
        $settings = new Settings($xml);
        $localeDetector = new LocaleDetector($requestParameters, $requestHeaders, $settings->getLocalizationMethod());
        $preferredLocale = $localeDetector->getDetectedLocale();
        $settings->setPreferredLocale($preferredLocale!==NULL?$preferredLocale:$settings->getDefaultLocale());
        
        // compiles settings
        if (!file_exists($settings->getFolder().DIRECTORY_SEPARATOR.$settings->getPreferredLocale())) {
            // if input locale is not supported, use default
            if (!file_exists($settings->getFolder().DIRECTORY_SEPARATOR.$settings->getDefaultLocale())) {
                throw new ConfigurationException("Translations not set for default locale: ".$settings->getDefaultLocale());
            }
            $settings->setPreferredLocale($settings->getDefaultLocale()); // overrides not supported preferred locale with default
        }
        
        // saves locale in session
        if ($settings->getLocalizationMethod() == "session") {
            $_SESSION[LocaleDetector::PARAMETER_NAME] = $settings->getPreferredLocale();
        }
        
        // injects settings into reader for later translations
        $this->reader = new Reader($settings);
        $this->writer = new Writer($settings);
        
        // saves settings to be used by Writer 
        $this->settings = $settings;
    }
    
    /**
     * Gets translations reader instance
     * 
     * @return Reader
     */
    public function getReader(): Reader
    {
        return $this->reader;
    }
    
    /**
     * Gets translations writer instance
     *
     * @return Writer
     */
    public function getWriter(): Writer
    {
        return $this->writer;
    }
}
