<?php

use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Response;

$url     = false;
$siteUrl = $Site->getAttribute('quiqqer.settings.sitetypes.forwarding');

try {
    if (QUI\Projects\Site\Utils::isSiteLink($siteUrl)) {
        $Wanted = \QUI\Projects\Site\Utils::getSiteByLink($siteUrl);

        // so, we get the site with vhosts, and url dir
        $Output = new QUI\Output();

        $url = $Output->getSiteUrl([
            'site' => $Wanted
        ]);
    } else {
        $parts = \parse_url($siteUrl);

        if (!isset($parts['host']) || empty($parts['host'])) {
            $siteUrl = HOST.$siteUrl;
        }

        if (!isset($parts['scheme']) && \strpos($siteUrl, '//') !== 0) {
            $siteUrl = '//'.$siteUrl;
        }

        // external
        $url = $siteUrl;
    }
} catch (QUI\Exception $Exception) {
}

if (\strpos($siteUrl, '#') !== false) {
    $urlParts = \explode('#', $siteUrl);
    $anchor   = $urlParts[1];
    $url      = $urlParts[0].'#'.$anchor;
}

if ($url) {
    $Redirect = new RedirectResponse($url);
    $Redirect->setStatusCode(Response::HTTP_SEE_OTHER);

    echo $Redirect->getContent();
    $Redirect->send();
    exit;
}
