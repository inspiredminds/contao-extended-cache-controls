<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Extended Cache Controls extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoExtendedCacheControls\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\PageModel;

#[AsHook('loadPageDetails')]
class LoadPageDetailsListener
{
    public function __invoke(array $parentModels, PageModel $page): void
    {
        /** @var PageModel $parent */
        foreach ($parentModels as $parent) {
            if ($parent->includeCache) {
                $page->disableCacheForNonCanonical = $parent->disableCacheForNonCanonical;
                $page->disableCacheForQueryParams = $parent->disableCacheForQueryParams;
                $page->cacheAllowedQueryParams = $parent->cacheAllowedQueryParams;
                $page->disableCacheForFragmentParams = $parent->disableCacheForFragmentParams;
                $page->cacheAllowedFragmentParams = $parent->cacheAllowedFragmentParams;
                break;
            }
        }
    }
}
