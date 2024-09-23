<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Extended Cache Controls extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoExtendedCacheControls\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\EventListener\MakeResponsePrivateListener as ContaoMakeResponsePrivateListener;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContextAccessor;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\PageModel;
use Contao\StringUtil;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MakeResponsePrivateListener
{
    private string $canonicalUri = '';

    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly ResponseContextAccessor $responseContextAccessor,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * Stores the canonical URL.
     */
    #[AsHook('generatePage', priority: -1000)]
    public function onGeneratePage(PageModel $page): void
    {
        if (!$page->enableCanonical) {
            return;
        }

        if (!$request = $this->requestStack->getMainRequest()) {
            return;
        }

        if (!$responseContext = $this->responseContextAccessor->getResponseContext()) {
            return;
        }

        if (!$responseContext->has(HtmlHeadBag::class)) {
            return;
        }

        /** @var HtmlHeadBag $htmlHeadBag */
        $htmlHeadBag = $responseContext->get(HtmlHeadBag::class);

        $this->canonicalUri = $htmlHeadBag->getCanonicalUriForRequest($request);
    }

    /**
     * Makes the response private if conditions are met.
     */
    #[AsEventListener(priority: -1018)]
    public function onResponse(ResponseEvent $event): void
    {
        // Nothing to be done if this is not the Contao front end main request
        if (!$this->scopeMatcher->isFrontendMainRequest($event)) {
            return;
        }

        $response = $event->getResponse();

        // Nothing to be done if response is already not cacheable
        if (!$response->isCacheable()) {
            return;
        }

        $request = $event->getRequest();

        // Nothing to be done if PageModel cannot be retrieved or cache is disabled
        if (!($page = $this->getPageModel($request)) || !$page->cache) {
            return;
        }

        if ($page->disableCacheForNonCanonical && $this->canonicalUri && $request->getUri() !== $this->canonicalUri) {
            $this->makePrivate($response, 'non-canonical');

            return;
        }

        // Check if request has query parameters
        if ($page->disableCacheForQueryParams && $request->query->count() > 0) {
            $allowedParams = StringUtil::splitCsv($page->cacheAllowedQueryParams);

            if (array_diff(array_keys($request->query->all()), $allowedParams)) {
                $this->makePrivate($response, 'query-params');

                return;
            }
        }

        // Check if request has fragment parameters
        if ($page->disableCacheForFragmentParams && ($fragments = $request->attributes->get('parameters'))) {
            $allowedParams = StringUtil::splitCsv($page->cacheAllowedFragmentParams);
            $fragments = explode('/', ltrim((string) $fragments, '/'));

            // Ignore auto_item
            if (0 !== \count($fragments) % 2) {
                array_shift($fragments);
            }

            // Remove values
            $fragments = array_filter($fragments, static fn ($key) => !($key & 1), ARRAY_FILTER_USE_KEY);

            if ($fragments && array_diff($fragments, $allowedParams)) {
                $this->makePrivate($response, 'fragment-params');

                return;
            }
        }
    }

    private function getPageModel(Request $request): PageModel|null
    {
        if (($pageModel = $request->attributes->get('pageModel')) instanceof PageModel) {
            return $pageModel;
        }

        if (($GLOBALS['objPage'] ?? null) instanceof PageModel) {
            return $GLOBALS['objPage'];
        }

        return null;
    }

    private function makePrivate(Response $response, string $reason): void
    {
        $response->setPrivate();
        $response->headers->set(ContaoMakeResponsePrivateListener::DEBUG_HEADER, $reason);
    }
}
