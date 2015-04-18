<?php

/**
 * Site list
 */

$ChildrenList = new \QUI\Controls\ChildrenList(array(
    'Site'        => $Site,
    'limit'       => $Site->getAttribute('quiqqer.settings.sitetypes.list.max'),
    'showSheets'  => $Site->getAttribute('quiqqer.settings.sitetypes.list.showSheets'),
    'showImages'  => $Site->getAttribute('quiqqer.settings.sitetypes.list.showImages'),
    'showShort'   => true,
    'showHeader'  => $Site->getAttribute('quiqqer.settings.sitetypes.list.showHeader'),
    'showContent' => false
));

try {
    $ChildrenList->checkLimit();

} catch (QUI\Exception $Exception) {
    QUI::getRewrite()->showErrorHeader(404, $Site->getUrlRewrited());
}


$Engine->assign(array(
    'ChildrenList' => $ChildrenList
));
