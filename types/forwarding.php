<?php

try
{
    $Wanted = \QUI\Projects\Site\Utils::getSiteByLink(
        $Site->getAttribute( 'quiqqer.settings.sitetypes.forwarding' )
    );

    // so, we get the site with vhosts, and url dir
    $url = QUI::getRewrite()->getUrlFromSite(array(
        'site' => $Wanted
    ));

    if ( isset( $url ) ) {
        header( "Location: ". $url, true, 302 );
    }

} catch ( QUI\Exception $Exception )
{

}
