<?php

/**
 * This file contains QUI\Controls\ChildrenList
 */

namespace QUI\Controls;

use QUI;

/**
 * Class Startpage2Box
 *
 * @package quiqqer/template-qui
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
            'class'      => 'qui-control-list',
            'limit'      => 2,
            'showSheets' => true,
            'showImages' => true,
            'showShort'  => true,
            'showHeader' => true,
            'Site'       => true
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
        $max   = $this->getAttribute( 'limit' );

        if ( isset( $_REQUEST['sheet'] ) ) {
            $start = ( (int)$_REQUEST['sheet'] - 1 ) * $max;
        }

        $count_children = $Site->getChildren(array(
            'count'	=> 'count'
        ));

        if ( is_array( $count_children ) ) {
            $count_children = count( $count_children );
        }

        // sheets
        $sheets = ceil( $count_children / $max );

        $children = $Site->getChildren(array(
            'limit' => $start .','. $max
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


