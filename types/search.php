<?php

/**
 * 404 Error Site
 */

use QUI\Utils\Security\Orthos;

if ( \QUI::getRewrite()->getHeaderCode() === 404 )
{
    if ( isset( $_REQUEST[ '_url' ] ) )
    {
        $requestUrl = $_REQUEST[ '_url' ];
        $requestUrl = Orthos::clear( $requestUrl );

        $path = pathinfo( $requestUrl );

        $_REQUEST[ 'search' ] = $path['filename'];
    }
}

/**
 * Search
 */

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
    $searchValue = Orthos::clear( $_REQUEST[ 'search' ] );
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

