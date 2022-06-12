<?php

namespace Lucinda\Internationalization;

/**
 * Detects locale based on contents of internationalization tag:
 *
 * <internationalization locale="en_US" method="session"/>
 */
class LocaleDetector
{
    public const PARAMETER_NAME = "locale";
    private ?string $detectedLocale = null;

    /**
     * Determines method to detect locale then performs locale detection by matching XML with client request/headers
     *
     * @param array<string,string>  $requestParameters
     * @param array<string,string>  $sessionParameters
     * @param array<string,string>  $requestHeaders
     * @param LocaleDetectionMethod $detectionMethod
     */
    public function __construct(
        array $requestParameters,
        array $sessionParameters,
        array $requestHeaders,
        LocaleDetectionMethod $detectionMethod
    ) {
        $this->setDetectedLocale($requestParameters, $sessionParameters, $requestHeaders, $detectionMethod);
    }

    /**
     * Sets detected locale based on detection method, client request as well as <session> XML tag (if detection
     * method is session)
     *
     * @param array<string,string>  $requestParameters
     * @param array<string,string>  $sessionParameters
     * @param array<string,string>  $requestHeaders
     * @param LocaleDetectionMethod $detectionMethod
     */
    private function setDetectedLocale(
        array $requestParameters,
        array $sessionParameters,
        array $requestHeaders,
        LocaleDetectionMethod $detectionMethod
    ): void {
        switch ($detectionMethod) {
        case LocaleDetectionMethod::HEADER:
            $this->detectedLocale = $this->detectByHeaders($requestHeaders);
            break;
        case LocaleDetectionMethod::REQUEST:
            $this->detectedLocale = $this->detectByRequestParameters($requestParameters);
            break;
        case LocaleDetectionMethod::SESSION:
            $this->detectedLocale = $this->detectBySessionParameters($sessionParameters, $requestParameters);
            break;
        }
    }

    /**
     * Detects locale by Accept-Language HTTP request header
     *
     * @param  array<string,string> $requestHeaders
     * @return string|null
     */
    private function detectByHeaders(array $requestHeaders): ?string
    {
        if (isset($requestHeaders["Accept-Language"])) {
            $matches = [];
            preg_match_all("/(([a-z]{2})\-([A-Z]{2}))/", $requestHeaders["Accept-Language"], $matches);
            if (!empty($matches[1])) {
                return $matches[2][0]."_".$matches[3][0];
            }
        }
        return null;
    }

    /**
     * Detects locale by value of GET request parameter "locale"
     *
     * @param  array<string,string> $requestParameters
     * @return string|null
     */
    private function detectByRequestParameters(array $requestParameters): ?string
    {
        if (isset($requestParameters[self::PARAMETER_NAME])) {
            return $requestParameters[self::PARAMETER_NAME];
        }
        return null;
    }

    /**
     * Detects locale by value of SESSION & GET request parameter "locale"
     *
     * @param  array<string,string> $sessionParameters
     * @param  array<string,string> $requestParameters
     * @return string|null
     */
    private function detectBySessionParameters(array $sessionParameters, array $requestParameters): ?string
    {
        $detectedLocale = null;
        if (isset($sessionParameters[self::PARAMETER_NAME])) {
            $detectedLocale = $sessionParameters[self::PARAMETER_NAME];
        }
        if (isset($requestParameters[self::PARAMETER_NAME])) {
            $detectedLocale = $requestParameters[self::PARAMETER_NAME];
        }
        return $detectedLocale;
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
