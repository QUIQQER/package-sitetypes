<?php

/**
 * 404 Error Site
 */

use QUI\Utils\Security\Orthos;

if (QUI::getRewrite()->getHeaderCode() === 404) {
    if (isset($_REQUEST['_url'])) {
        $requestUrl = $_REQUEST['_url'];
        $path       = pathinfo($requestUrl);

        if (isset($path['dirname'])) {
            $_REQUEST['search'] = $path['dirname'] . ' ' . $path['filename'];
        } else {
            $_REQUEST['search'] = $path['filename'];
        }
    }
}

/**
 * Search
 */

$searchValue = '';
$start       = 0;
$max         = $Site->getAttribute('quiqqer.settings.sitetypes.list.max');

$children = array();
$sheets   = 0;
$count    = 0;

if (!$max) {
    $max = 5;
}

if (isset($_REQUEST['sheet'])) {
    $start = ((int)$_REQUEST['sheet'] - 1) * $max;
}

if (isset($_REQUEST['search'])) {
    if (is_array($_REQUEST['search'])) {
        $searchValue = implode(' ', $_REQUEST['search']);
    } else {
        $searchValue = $_REQUEST['search'];
    }

    $searchValue = preg_replace("/[^a-zA-Z0-9äöüß]/", " ", $searchValue);
    $searchValue = Orthos::clear($searchValue);
    $searchValue = preg_replace('#([ ]){2,}#', "$1", $searchValue);
    $searchValue = trim($searchValue);
}


// search
if (!empty($searchValue)) {
    $children = $Project->getSites(array(
        'where' => array(
            'title' => array(
                'value' => $searchValue,
                'type'  => '%LIKE%'
            )
        ),
        'limit' => $start . ',' . $max
    ));

    // sheets and count
    $count = $Project->getSites(array(
        'count' => 'count',
        'where' => array(
            'title' => array(
                'value' => $searchValue,
                'type'  => '%LIKE%'
            )
        )
    ));

    if (is_array($count)) {
        $count = count($count);
    }

    $sheets = ceil($count / $max);
}


$Pagination = new QUI\Bricks\Controls\Pagination(array(
    'Site'      => $Site,
    'count'     => $count,
    'showLimit' => false,
    'limit'     => $max,
    'useAjax'   => false
));

$Pagination->loadFromRequest();
$Pagination->setGetParams('search', $searchValue);

$ChildrenList = new QUI\Controls\ChildrenList(array(
    'showTitle'      => false,
    'Site'           => $Site,
    'limit'          => $max,
    'showDate'       => $Site->getAttribute('quiqqer.settings.sitetypes.list.showDate'),
    'showCreator'    => $Site->getAttribute('quiqqer.settings.sitetypes.list.showCreator'),
    'showTime'       => false,
    'showSheets'     => false,
    'showImages'     => $Site->getAttribute('quiqqer.settings.sitetypes.list.showImages'),
    'showShort'      => true,
    'showHeader'     => true,
    'showContent'    => false,
    'itemtype'       => 'http://schema.org/ItemList',
    'child-itemtype' => 'http://schema.org/ListItem',
    'display'        => $Site->getAttribute('quiqqer.settings.sitetypes.list.template'),
    'children'       => $children,
));

$Engine->assign(array(
    'Pagination'  => $Pagination,
    'sheets'      => $sheets,
    'children'    => $children,
    'searchValue' => $searchValue,
    'ChildrenList'    => $ChildrenList
));
