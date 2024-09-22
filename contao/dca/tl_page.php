<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Extended Cache Controls extension.
 *
 * (c) INSPIRED MINDS
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_page']['fields']['disableCacheForNonCanonical'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 clr'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_page']['fields']['disableCacheForQueryParams'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 clr'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_page']['fields']['cacheAllowedQueryParams'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50 clr'],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_page']['fields']['disableCacheForFragmentParams'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 clr'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_page']['fields']['cacheAllowedFragmentParams'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50 clr'],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

PaletteManipulator::create()
    ->addField('disableCacheForNonCanonical')
    ->addField('disableCacheForQueryParams')
    ->addField('cacheAllowedQueryParams')
    ->addField('disableCacheForFragmentParams')
    ->addField('cacheAllowedFragmentParams')
    ->applyToSubpalette('includeCache', 'tl_page')
;
