<?php

/**
 * Site list
 */

$ChildrenList = new QUI\Controls\ChildrenList([
    'showTitle'      => false,
    'Site'           => $Site,
    'limit'          => $Site->getAttribute('quiqqer.settings.sitetypes.list.max'),
    'showCreator'    => $Site->getAttribute('quiqqer.settings.sitetypes.list.showCreator'),
    'showDate'       => $Site->getAttribute('quiqqer.settings.sitetypes.list.showDate'),
    'showTime'       => $Site->getAttribute('quiqqer.settings.sitetypes.list.showTime'),
    'showSheets'     => $Site->getAttribute('quiqqer.settings.sitetypes.list.showSheets'),
    'showImages'     => $Site->getAttribute('quiqqer.settings.sitetypes.list.showImages'),
    'showHeader'     => $Site->getAttribute('quiqqer.settings.sitetypes.list.showHeader'),
    'showShort'      => $Site->getAttribute('quiqqer.settings.sitetypes.list.showShort'),
    'showContent'    => false,
    'itemtype'       => 'http://schema.org/ItemList',
    'child-itemtype' => 'http://schema.org/ListItem',
    'display'        => $Site->getAttribute('quiqqer.settings.sitetypes.list.template')
]);

try {
    $ChildrenList->checkLimit();
} catch (QUI\Exception $Exception) {
    QUI\System\Log::addWarning($Exception->getMessage());
}

$Engine->assign([
    'ChildrenList' => $ChildrenList
]);
