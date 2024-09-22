<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Extended Cache Controls extension.
 *
 * (c) INSPIRED MINDS
 */

$GLOBALS['TL_LANG']['tl_page']['disableCacheForNonCanonical'] = ['Do not cache non-canonicals', 'Disables caching for non-canonnical URLs, if canonical URLs are enabled.'];
$GLOBALS['TL_LANG']['tl_page']['disableCacheForQueryParams'] = ['Do not cache query parameters', 'Disables caching for URLs that contain query parameters.'];
$GLOBALS['TL_LANG']['tl_page']['cacheAllowedQueryParams'] = ['Allowed query parameters', 'This will allow caching for the given query parameters. If any query parameters are present in the URL that are not in this comma-separated list, the URL is not cached.'];
$GLOBALS['TL_LANG']['tl_page']['disableCacheForFragmentParams'] = ['Do not cache fragment parameters', 'Disables caching for Contao specific fragment parameters e.g. /&lt;alias>/&lt;key>/&lt;value>'];
$GLOBALS['TL_LANG']['tl_page']['cacheAllowedFragmentParams'] = ['Allowed fragment parameters', 'This will allow caching for the given fragment parameters. If any fragment parameters are present in the URL that are not in this comma-separated list, the URL is not cached.'];
