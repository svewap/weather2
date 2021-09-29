<?php
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.weather2',
    'Current',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.current.title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.weather2',
    'day',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.day.title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.weather2',
    'Forecast',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.forecast.title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.weather2',
    'Weatheralert',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.weatheralert.title'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['weather2_current'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'weather2_current',
    'FILE:EXT:weather2/Configuration/FlexForms/flexform_weather.xml'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['weather2_day'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'weather2_day',
    'FILE:EXT:weather2/Configuration/FlexForms/flexform_day.xml'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['weather2_weatheralert'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'weather2_weatheralert',
    'FILE:EXT:weather2/Configuration/FlexForms/flexform_weatheralert.xml'
);
