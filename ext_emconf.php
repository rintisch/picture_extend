<?php

/**
 * Extension Manager/Repository config file for ext "t3_ce".
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Responsive Images, extends EXT:picture',
    'description' => 'Responsive Images for TYPO3',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
            'fluid_styled_content' => '9.5.0-10.4.99',
            'picture' => '0.1.1',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Rintisch\\PictureExtend\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Gerald Rintisch',
    'author_email' => 'gerald.rintisch@posteo.de',
    'author_company' => 'Rintisch',
    'version' => '1.0.0',
];
