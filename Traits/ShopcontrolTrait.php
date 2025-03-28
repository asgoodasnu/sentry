<?php

namespace OxidProfessionalServices\Sentry\Traits;

use OxidEsales\Eshop\Core\Registry;

use function Sentry\init;

trait ShopcontrolTrait
{
    public function start($sClass = null, $sFunction = null, $aParams = null, $aViewsChain = null)
    {
        $sentryUrl = Registry::getConfig()->getConfigParam('oxpsSentryPhpUrl');
        if ($sentryUrl != '' && function_exists('Sentry\init')) {
            $level = Registry::getConfig()->getConfigParam('oxpsSentryLogLevel');
            switch ($level) {
                case 'error':
                    $errorTypes = E_ERROR | E_USER_ERROR;
                    break;
                case 'warning':
                    $errorTypes = E_WARNING | E_USER_WARNING;
                    break;
                case 'notice':
                case 'info':
                    $errorTypes = E_NOTICE | E_USER_NOTICE;
                    break;
                case 'debug':
                    $errorTypes = E_ALL;
            }
            init([
                'dsn'         => $sentryUrl,
                'error_types' => $errorTypes,
                'environment' => Registry::getConfig()->getConfigParam('oxpsSentryEnvironment'),
                'http_proxy'  => Registry::getConfig()->getConfigParam('oxpsSentryProxy') ?: null,
            ]);
        }

        parent::start($sClass, $sFunction, $aParams, $aViewsChain);
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _handleCookieException($exception)
    {
        $this->reportToSentry($exception);
        parent::_handleCookieException($exception);
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _handleDbConnectionException($exception)
    {
        $this->reportToSentry($exception);
        parent::_handleDbConnectionException($exception);
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _handleBaseException($exception)
    {
        $this->reportToSentry($exception);
        parent::_handleBaseException($exception);
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _handleSystemException($exception)
    {
        $this->reportToSentry($exception);
        parent::_handleSystemException($exception);
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _handleAccessRightsException($exception)
    {
        $this->reportToSentry($exception);
        parent::_handleAccessRightsException($exception);
    }
}
