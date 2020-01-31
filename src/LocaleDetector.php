<?php
namespace Lucinda\Internationalization;

/**
 * Detects locale based on contents of internationalization tag:
 *
 * <internationalization locale="en_US" method="session"/>
 */
class LocaleDetector
{
    const PARAMETER_NAME = "locale";
    private $detectedLocale;
    
    /**
     * Determines method to detect locale then performs locale detection by matching XML with client request/headers
     *
     * @param \SimpleXMLElement $xml Internationalization tag content.
     * @param string[string] $requestParameters
     * @param string[string] $requestHeaders
     */
    public function __construct(array $requestParameters, array $requestHeaders, string $detectionMethod)
    {
        $this->setDetectedLocale($requestParameters, $requestHeaders, $detectionMethod);
    }
    
    /**
     * Sets detected locale based on detection method, client request as well as <session> XML tag (if detection method is session)
     *
     * @param string[string] $requestParameters
     * @param string[string] $requestHeaders
     */
    private function setDetectedLocale(array $requestParameters, array $requestHeaders, string $detectionMethod): void
    {
        switch ($detectionMethod) {
            case "header":
                if (isset($requestHeaders["Accept-Language"])) {
                    $header = $requestHeaders["Accept-Language"];
                    $locale = substr($header, 0, strpos($header, ","));
                    $slashPosition = strpos($locale, "-");
                    $this->detectedLocale = strtolower(substr($locale, 0, $slashPosition))."_".strtoupper(substr($locale, $slashPosition+1));
                }
                break;
            case "request":
                if (isset($requestParameters[self::PARAMETER_NAME])) {
                    $this->detectedLocale = $requestParameters[self::PARAMETER_NAME];
                }
                break;
            case "session":
                if (session_id() == "") {
                    throw new ConfigurationException("Session must be already started!");
                }
                if (isset($_SESSION[self::PARAMETER_NAME])) {
                    $this->detectedLocale = $_SESSION[self::PARAMETER_NAME];
                }
                if (isset($requestParameters[self::PARAMETER_NAME])) {
                    $this->detectedLocale = $requestParameters[self::PARAMETER_NAME];
                }
                break;
        }
    }
    
    /**
     * Gets detected locale
     * 
     * @return string|NULL
     */
    public function getDetectedLocale(): ?string
    {
        return $this->detectedLocale;
    }
}
