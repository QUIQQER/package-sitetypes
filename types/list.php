<?php

/**
 * Site list
 */

$max = $Site->getAttribute( 'quiqqer.settings.sitetypes.list.max' );

if ( !$max ) {
    $max = 5;
}

$ChildrenList = new \QUI\Controls\ChildrenList(array(
    'Site'        => $Site,
    'limit'       => $max,
    'showSheets'  => true,
    'showImages'  => true,
    'showShort'   => true,
    'showHeader'  => true,
    'showContent' => false
));

$Engine->assign(array(
    'ChildrenList' => $ChildrenList
));
