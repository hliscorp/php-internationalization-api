<?php
namespace Lucinda\Internationalization;

/**
 * Encapsulates internationalization settings required in order to write to or read from translation files.
 */
class Settings
{
    private $preferredLocale;
    private $defaultLocale;
    private $domain = "messages";
    private $folder = "locale";
    private $extension = "json";
    
    /**
     * Saves prefferred and default locales.
     *
     * @param string $preferredLocale Country and language ISO codes (2) concatenated by _
     * @param string $defaultLocale Country and language ISO codes (2) concatenated by _
     */
    public function __construct(string $preferredLocale, string $defaultLocale): void
    {
        $this->setPreferredLocale($preferredLocale);
        $this->setDefaultLocale($defaultLocale);
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
    
    /**
     * Sets default locale to be used when translating
     *
     * @param string $locale Country and language ISO codes (2) concatenated by _
     */
    public function setDefaultLocale(string $locale): void
    {
        $this->defaultLocale = $locale;
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
     * Sets name (aka "domain") of translation file.
     *
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
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
     * Sets folder in which translations are located
     *
     * @param string $folder
     */
    public function setFolder(string $folder): void
    {
        $this->folder = $folder;
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
     * Sets extension of translation file (aka "domain").
     *
     * @param string $extension
     */
    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
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
}
