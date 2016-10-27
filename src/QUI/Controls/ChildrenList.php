<?php

/**
 * This file contains QUI\Controls\ChildrenList
 */

namespace QUI\Controls;

use QUI;
use QUI\Projects\Site\Utils;

/**
 * Class ChildrenList
 *
 * @package quiqqer/sitetypes
 */
class ChildrenList extends QUI\Control
{
    /**
     * constructor
     *
     * @param array $attributes
     */
    public function __construct($attributes = array())
    {
        // default options
        $this->setAttributes(array(
            'class'           => 'qui-control-list',
            'limit'           => 2,
            'showSheets'      => true,
            'showImages'      => true,
            'showShort'       => true,
            'showHeader'      => true,
            'showContent'     => true,
            'showTime'        => false,
            'showCreator'     => false,
            'Site'            => true,
            'parentInputList' => false, // @todo comment
            'where'           => false,
            'itemtype'        => 'http://schema.org/ItemList',
            'child-itemtype'  => 'http://schema.org/NewsArticle',
            'display'         => 'childrenlist'
        ));

        parent::__construct($attributes);
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
        $Site   = $this->getSite();

        if (!$Site) {
            return '';
        }

        $Pagination = new QUI\Bricks\Controls\Pagination();
        $Pagination->loadFromRequest();
        $Pagination->setAttribute('Site', $Site);

        $start   = 0;
        $limit   = $this->getAttribute('limit');
        $parents = $this->getAttribute('parentInputList');
        $Project = $Site->getProject();

        if (!$parents) {
            $parents = $Site->getId();
        }

        if (!$limit) {
            $limit = 2;
        }

        if (isset($_REQUEST['sheet'])) {
            $start = ((int)$_REQUEST['sheet'] - 1) * $limit;
        }

        $count_children = Utils::getSitesByInputList($Project, $parents, array(
            'count' => 'count'
        ));

//        $count_children = $Site->getChildren(array(
//            'count' => 'count',
//            'where' => $this->getAttribute('where')
//        ));

        if (is_array($count_children)) {
            $count_children = count($count_children);
        }

        // sheets
        $sheets = ceil($count_children / $limit);
//
//        $children = $Site->getChildren(array(
//            'where' => $this->getAttribute('where'),
//            'limit' => $start . ',' . $limit
//        ));

        $children = Utils::getSitesByInputList($Project, $parents, array(
            'where' => $this->getAttribute('where'),
            'limit' => $start . ',' . $limit
        ));

        $Pagination->setAttribute('limit', $limit);
        $Pagination->setAttribute('sheets', $sheets);

        $Engine->assign(array(
            'this'       => $this,
            'Site'       => $this->getSite(),
            'Project'    => $this->getProject(),
            'sheets'     => $sheets,
            'children'   => $children,
            'Pagination' => $Pagination
        ));


        switch ($this->getAttribute('display')) {
            default:
            case 'childrenlist':
                $css      = dirname(__FILE__) . '/ChildrenList.css';
                $template = dirname(__FILE__) . '/ChildrenList.html';
                break;

            case 'longfooter':
                $css      = dirname(__FILE__) . '/ChildrenList.LongFooter.css';
                $template = dirname(__FILE__) . '/ChildrenList.LongFooter.html';
                break;

            case 'authortop':
                $css      = dirname(__FILE__) . '/ChildrenList.AuthorTop.css';
                $template = dirname(__FILE__) . '/ChildrenList.AuthorTop.html';
                break;

            case 'icons':
                $css      = dirname(__FILE__) . '/ChildrenList.Icons.css';
                $template = dirname(__FILE__) . '/ChildrenList.Icons.html';
                break;

            case 'simpleArticleList':
                $css      = dirname(__FILE__) . '/ChildrenList.SimpleArticleList.css';
                $template = dirname(__FILE__) . '/ChildrenList.SimpleArticleList.html';
                break;

            case 'advancedArticleList':
                $css      = dirname(__FILE__) . '/ChildrenList.AdvancedArticleList.css';
                $template = dirname(__FILE__) . '/ChildrenList.AdvancedArticleList.html';
                break;

            case 'listWithBorder':
                $css      = dirname(__FILE__) . '/ChildrenList.ListWithBorder.css';
                $template = dirname(__FILE__) . '/ChildrenList.ListWithBorder.html';
                break;

            case 'standardList':
                $css      = dirname(__FILE__) . '/ChildrenList.StandardList.css';
                $template = dirname(__FILE__) . '/ChildrenList.StandardList.html';
                break;
        }

        $this->addCSSFile($css);

        return $Engine->fetch($template);
    }

    /**
     * Check if the limit can execute
     *
     * @throws QUI\Exception
     */
    public function checkLimit()
    {
        $Site = $this->getSite();

        if (!$Site) {
            return;
        }

        $sheet = 1;
        $limit = $this->getAttribute('limit');

        if (!$limit) {
            $limit = 2;
        }

        if (isset($_REQUEST['sheet'])) {
            $sheet = (int)$_REQUEST['sheet'];
        }

        $count_children = $Site->getChildren(array(
            'count' => 'count'
        ));

        $sheets = ceil($count_children / $limit);

        if ($sheets < $sheet || $sheet < 0) {
            throw new QUI\Exception('Sites not found', 404);
        }
    }

    /**
     * @return mixed|QUI\Projects\Site
     */
    protected function getSite()
    {
        if ($this->getAttribute('Site')) {
            return $this->getAttribute('Site');
        }

        $Site = QUI::getRewrite()->getSite();

        $this->setAttribute('Site', $Site);

        return $Site;
    }
}
