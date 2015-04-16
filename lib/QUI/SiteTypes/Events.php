<?php

/**
 * This file contains QUI\SiteTypes\Events
 */

namespace QUI\SiteTypes;

use QUI;

/**
 * Class Events
 *
 * @package quiqqer/sitetypes
 */
class Events
{
    /**
     * event : on site save
     */
    static function onSiteSaveBefore($Site)
    {
        $type = $Site->getAttribute('type');

        // forward check
        if ( $type == 'quiqqer/sitetypes:types/forwarding' )
        {
            $Project = $Site->getProject();
            $siteUrl = $Site->getAttribute( 'quiqqer.settings.sitetypes.forwarding' );

            if ( \QUI\Projects\Site\Utils::isSiteLink( $siteUrl ) )
            {
                // check if the site link is not itself
                try
                {
                    $Wanted        = \QUI\Projects\Site\Utils::getSiteByLink( $siteUrl );
                    $WantedProject = $Wanted->getProject();

                    if ( $Wanted->getId() == $Site->getId() &&
                         $WantedProject->getName() == $Project->getName() &&
                         $WantedProject->getLang() == $Project->getLang() )
                    {
                        QUI::getMessagesHandler()->addAttention(
                            QUI::getLocale()->get(
                                'quiqqer/sitetypes',
                                'message.forwarding.to.itself'
                            )
                            'Eine Weiterleitung kann nicht auf sich selbst zeigen. '.
                            'Die Weiterleitungs-Einstellung wurden nicht gespeichert.'
                        );

                        $Site->setAttribute( 'quiqqer.settings.sitetypes.forwarding', '' );
                    }

                } catch ( QUI\Exception $Exception )
                {

                }
            }
        }
    }
}