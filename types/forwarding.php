<?php

$url     = URL_DIR;
$siteUrl = $Site->getAttribute( 'quiqqer.settings.sitetypes.forwarding' );

try
{
    if ( \QUI\Projects\Site\Utils::isSiteLink( $siteUrl ) )
    {
        $Wanted = \QUI\Projects\Site\Utils::getSiteByLink( $siteUrl );

        // so, we get the site with vhosts, and url dir
        $url = QUI::getRewrite()->getUrlFromSite(array(
            'site' => $Wanted
        ));

    } else
    {
        $parts = parse_url( $siteUrl );

        if ( !isset( $parts['scheme'] ) && strpos( $siteUrl, '//' ) !== 0 ) {
            $siteUrl = '//'. $siteUrl;
        }

        // external
        $url = $siteUrl;
    }

} catch ( QUI\Exception $Exception )
{

}


if ( isset( $url ) )
{
    // 303 = See Other
    header( "Location: ". $url, true, 303 );
}
