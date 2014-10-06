<?php

$searchValue = '';
$start       = 0;
$max         = $Site->getAttribute( 'quiqqer.settings.sitetypes.list.max' );

$children = array();
$sheets   = 0;

if ( !$max ) {
    $max = 5;
}

if ( isset( $_REQUEST[ 'sheet' ] ) ) {
    $start = ( (int)$_REQUEST[ 'sheet' ] - 1 ) * $max;
}

if ( isset( $_REQUEST[ 'search' ] ) ) {
    $searchValue = \QUI\Utils\Security\Orthos::clear( $_REQUEST[ 'search' ] );
}


// search
if ( !empty( $searchValue ) )
{
    $children = $Project->getSites(array(
        'where' => array(
            'title' => array(
                'value' => $searchValue,
                'type'  => '%LIKE%'
            )
        ),
        'limit' => $start .','. $max
    ));

    // sheets and count
    $count = $Project->getSites(array(
        'count'	=> 'count',
        'where' => array(
            'title' => array(
                'value' => $searchValue,
                'type'  => '%LIKE%'
            )
        )
    ));

    if ( is_array( $count ) ) {
        $count = count( $count );
    }

    $sheets = ceil( $count / $max );
}


$Engine->assign(array(
    'sheets'      => $sheets,
    'children'    => $children,
    'searchValue' => $searchValue
));

