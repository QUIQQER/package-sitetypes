<?php

/**
 * This file contains QUI\Controls\ChildrenList
 */

namespace QUI\Controls;

use QUI;

/**
 * Class ChildrenList
 *
 * @package quiqqer/sitetypes
 */
class ChildrenList extends QUI\Control
{
    /**
     * constructor
     * @param Array $attributes
     */
    public function __construct($attributes = array())
    {
        // default options
        $this->setAttributes(array(
            'class'       => 'qui-control-list',
            'limit'       => 2,

            'showSheets'  => true,
            'showImages'  => true,
            'showShort'   => true,
            'showHeader'  => true,
            'showContent' => true,
            'showTime'    => false,
            'showCreator' => false,

            'Site'  => true,
            'where' => false,

            'itemtype'       => 'http://schema.org/ItemList',
            'child-itemtype' => 'http://schema.org/NewsArticle'
        ));

        parent::setAttributes($attributes);

        $this->addCSSFile(
            dirname(__FILE__) . '/ChildrenList.css'
        );
    }

    /**
     * Return the inner body of the element
     * Can be overwritten
     *
     * @return String
     */
    public function getBody()
    {
        $Engine = QUI::getTemplateManager()->getEngine();
        $Site   = $this->_getSite();

        if ( !$Site ) {
            return '';
        }

        $start = 0;
        $limit = $this->getAttribute( 'limit' );

        if ( !$limit ) {
            $limit = 2;
        }

        if ( isset( $_REQUEST['sheet'] ) ) {
            $start = ( (int)$_REQUEST['sheet'] - 1 ) * $limit;
        }

        $count_children = $Site->getChildren(array(
            'count'	=> 'count',
            'where' => $this->getAttribute( 'where' )
        ));

        if ( is_array( $count_children ) ) {
            $count_children = count( $count_children );
        }

        // sheets
        $sheets = ceil( $count_children / $limit );

        $children = $Site->getChildren(array(
            'where' => $this->getAttribute( 'where' ),
            'limit' => $start .','. $limit
        ));


        $Engine->assign(array(
            'this'     => $this,
            'Site'     => $this->_getSite(),
            'sheets'   => $sheets,
            'children' => $children
        ));

        return $Engine->fetch( dirname( __FILE__ ) .'/ChildrenList.html' );
    }

    /**
     * Check if the limit can execute
     *
     * @throws QUI\Exception
     */
    public function checkLimit()
    {
        $Site   = $this->_getSite();

        if ( !$Site ) {
            return '';
        }

        $sheet = 1;
        $limit = $this->getAttribute( 'limit' );

        if ( !$limit ) {
            $limit = 2;
        }

        if ( isset( $_REQUEST['sheet'] ) ) {
            $sheet = (int)$_REQUEST['sheet'];
        }

        $count_children = $Site->getChildren(array(
            'count'	=> 'count'
        ));

        $sheets = ceil( $count_children / $limit );

        if ( $sheets < $sheet || $sheet < 0) {
            throw new QUI\Exception( 'Sites not found', 404 );
        }
    }

    /**
     * @return mixed|QUI\Projects\Site
     */
    protected function _getSite()
    {
        if ( $this->getAttribute( 'Site' ) ) {
            return $this->getAttribute( 'Site' );
        }

        $Site = \QUI::getRewrite()->getSite();

        $this->setAttribute( 'Site', $Site );

        return $Site;
    }
}


