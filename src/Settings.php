<?php
namespace Lucinda\Internationalization;

/**
 * Encapsulates internationalization settings required in order to write to or read from translation files.
 */
class Settings
{
    private string $domain = "messages";
    private string $folder = "locale";
    private string $extension = "json";
    
    private string $preferredLocale;
    private string $defaultLocale;
    private LocaleDetectionMethod $detectionMethod;

    /**
     * Saves preferred and default locales.
     *
     * @param \SimpleXMLElement $xml
     * @throws ConfigurationException
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->setLocalizationMethod($xml);
        $this->setDefaultLocale($xml);
        $this->setDomain($xml);
        $this->setFolder($xml);
        $this->setExtension($xml);
    }
    
    /**
     * Sets localization method based on "method" attribute of <internationalization> tag.
     *
     * @param \SimpleXMLElement $xml Internationalization tag content.
     * @throws ConfigurationException If XML is improperly configured.
     */
    private function setLocalizationMethod(\SimpleXMLElement $xml): void
    {
        $detectionMethod = (string) $xml["method"];
        if (!$detectionMethod) {
            throw new ConfigurationException("Attribute 'method' is mandatory for 'internationalization' tag");
        }
        $detectionMethod = strtolower($detectionMethod);
        if ($case = LocaleDetectionMethod::tryFrom($detectionMethod)) {
            $this->detectionMethod = $case;
        } else {
            throw new ConfigurationException("Invalid detection method: ".$detectionMethod);
        }
    }
    
    /**
     * Gets localization method
     *
     * @return LocaleDetectionMethod
     */
    public function getLocalizationMethod(): LocaleDetectionMethod
    {
        return $this->detectionMethod;
    }
    
    /**
     * Sets default locale based on "locale" attribute of <internationalization> tag.
     *
     * @param \SimpleXMLElement $xml Internationalization tag content.
     * @throws ConfigurationException If XML is improperly configured.
     */
    private function setDefaultLocale(\SimpleXMLElement $xml): void
    {
        $defaultLocale =  (string) $xml["locale"];
        if (!$defaultLocale) {
            throw new ConfigurationException("Attribute 'locale' is mandatory for 'internationalization' tag");
        }
        $this->defaultLocale = $defaultLocale;
    }
    
    /**
     * Gets default locale to be used when translating
     *
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }
    
    /**
     * Sets domain based on "domain" attribute of <internationalization> tag.
     *
     * @param \SimpleXMLElement $xml
     */
    private function setDomain(\SimpleXMLElement $xml): void
    {
        $domain = (string) $xml["domain"];
        if ($domain) {
            $this->domain = $domain;
        }
    }
    
    /**
     * Gets name  of translation file (by default: messages)
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }
    
    /**
     * Sets folder in which translations are located based on "folder" attribute of <internationalization> tag.
     *
     * @param \SimpleXMLElement $xml
     */
    private function setFolder(\SimpleXMLElement $xml): void
    {
        $folder = (string) $xml["folder"];
        if ($folder) {
            $this->folder = $folder;
        }
    }
    
    /**
     * Gets folder in which translations are located (by default: locale)
     *
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }
    
    /**
     * Sets extension of translation files based on "extension" attribute of <internationalization> tag.
     *
     * @param \SimpleXMLElement $xml
     */
    private function setExtension(\SimpleXMLElement $xml): void
    {
        $extension = (string) $xml["extension"];
        if ($extension) {
            $this->extension = $extension;
        }
    }
    
    /**
     * Gets extension of translation file (aka "domain")
     *
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }
    
    /**
     * Sets preferred locale to be used when translating
     *
     * @param string $locale Country and language ISO codes (2) concatenated by _
     */
    public function setPreferredLocale(string $locale): void
    {
        $this->preferredLocale = $locale;
    }
    
    /**
     * Gets preferred locale to be used when translating
     *
     * @return string
     */
    public function getPreferredLocale(): string
    {
        return $this->preferredLocale;
    }
}
