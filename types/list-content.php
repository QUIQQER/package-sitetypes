<?php

$ChildrenList = new QUI\Controls\ChildrenList(array(
    'Site' => $Site,
    'limit' => $Site->getAttribute('quiqqer.settings.sitetypes.list.max'),
    'showSheets' => $Site->getAttribute('quiqqer.settings.sitetypes.list.showSheets'),
    'showImages' => $Site->getAttribute('quiqqer.settings.sitetypes.list.showImages'),
    'showShort' => false,
    'showHeader' => $Site->getAttribute('quiqqer.settings.sitetypes.list.showHeader'),
    'showContent' => true,
    'itemtype' => 'http://schema.org/ItemList',
    'child-itemtype' => 'http://schema.org/ListItem"'
));

try {
    $ChildrenList->checkLimit();

} catch (QUI\Exception $Exception) {
    QUI::getRewrite()->showErrorHeader(404, $Site->getUrlRewritten());
}

$Engine->assign(array(
    'ChildrenList' => $ChildrenList
));
