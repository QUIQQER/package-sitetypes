<?php

/**
 * Site list
 */

$max = $Site->getAttribute( 'quiqqer.settings.sitetypes.list.max' );

if ( !$max ) {
    $max = 5;
}

$ChildrenList = new \QUI\Controls\ChildrenList(array(
    'Site'  => $Site,
    'limit' => $max
));

$Engine->assign(array(
    'ChildrenList' => $ChildrenList
));

//
//
//
//$count_children = $Site->getChildren(array(
//    'count'	=> 'count'
//));
//
//if ( is_array( $count_children ) ) {
//    $count_children = count( $count_children );
//}
//
//// sheets
//$sheets = ceil( $count_children / $max );
//
//$children = $Site->getChildren(array(
//    'limit' => $start .','. $max
//));
//
//$Engine->assign(array(
//    'sheets'   => $sheets,
//    'children' => $children
//));
