<?php

/**
 * This file contains QUI\SiteTypes\EventHandler
 */

namespace QUI\SiteTypes;

use QUI;

/**
 * Class Events
 *
 * @package quiqqer/sitetypes
 */
class EventHandler
{
    /**
     * event : on site save
     *
     * @param QUI\Projects\Site $Site
     */
    public static function onSiteSaveBefore($Site)
    {
        $type = $Site->getAttribute('type');

        // forward check
        if ($type == 'quiqqer/sitetypes:types/forwarding') {
            $Project = $Site->getProject();
            $siteUrl = $Site->getAttribute('quiqqer.settings.sitetypes.forwarding');

            if (QUI\Projects\Site\Utils::isSiteLink($siteUrl)) {
                // check if the site link is not itself
                try {
                    $Wanted        = QUI\Projects\Site\Utils::getSiteByLink($siteUrl);
                    $WantedProject = $Wanted->getProject();

                    if ($Wanted->getId() == $Site->getId() &&
                        $WantedProject->getName() == $Project->getName() &&
                        $WantedProject->getLang() == $Project->getLang()
                    ) {
                        QUI::getMessagesHandler()->addAttention(
                            QUI::getLocale()->get(
                                'quiqqer/sitetypes',
                                'message.forwarding.to.itself'
                            )
                        );

                        $Site->setAttribute('quiqqer.settings.sitetypes.forwarding', '');
                    }
                } catch (QUI\Exception $Exception) {
                }
            }
        }
    }

    /**
     * event : on site init
     *
     * @param QUI\Projects\Site $Site
     */
    public static function onSiteInit($Site)
    {
        if ($Site->getAttribute('type') == 'quiqqer/sitetypes:types/forwarding') {
            $Site->setAttribute('nocache', 1);
        }
    }
}
