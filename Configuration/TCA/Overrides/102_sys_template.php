<?php
defined('TYPO3_MODE') || die();

call_user_func(function()
{
    /**
     * Default TypoScript for T3Ce
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'responsive_images',
        'Configuration/TypoScript',
        'Responsive Images'
    );
});
