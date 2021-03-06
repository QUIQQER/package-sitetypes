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
    public function __construct($attributes = [])
    {
        // default options
        $this->setAttributes([
            'class'                      => 'qui-control-list',
            'limit'                      => 2,
            'showSheets'                 => true,
            'showImages'                 => true,
            'showShort'                  => true,
            'showHeader'                 => true,
            'showContent'                => false,
            'showDate'                   => false,
            'showTime'                   => false,
            'showCreator'                => false,
            'Site'                       => false,
            'parentInputList'            => false,
            // if true returns all sites of certain type
            'byType'                     => false,
            'where'                      => false,
            'itemtype'                   => 'http://schema.org/ItemList',
            'child-itemtype'             => 'https://schema.org/ListItem',
            'child-itemprop'             => 'itemListElement',
            // layout / design
            'display'                    => 'childrenlist',
            // Custom children template (path to html file); overwrites "display"
            'displayTemplate'            => false,
            // Custom children template css (path to css file); overwrites "display"
            'displayCss'                 => false,
            'nodeName'                   => 'section',
            // list of sites to display,
            'children'                   => false,
            // load all children of list site if the 'children' attribute is empty
            'loadAllChildrenOnEmptyList' => true
        ]);

        parent::__construct($attributes);
    }

    /**
     * Return the inner body of the element
     * Can be overwritten
     *
     * @return String
     *
     * @throws QUI\Exception
     */
    public function getBody()
    {
        try {
            $Engine = QUI::getTemplateManager()->getEngine();
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::writeException($Exception);

            return '';
        }

        $Site = $this->getSite();

        if (!$Site && !$this->getAttribute('parentInputList')) {
            return '';
        }

        $Pagination = new QUI\Controls\Navigating\Pagination();
        $Pagination->loadFromRequest();
        $Pagination->setAttribute('Site', $Site);

        $start    = 0;
        $limit    = $this->getAttribute('limit');
        $parents  = $this->getAttribute('parentInputList');
        $Project  = $this->getProject();
        $children = [];

        if (!$parents) {
            $parents = $Site->getId();
        }

        if (!$limit) {
            $limit = 6;
        }

        if ($this->getAttribute('showSheets')) {
            if (isset($_REQUEST['sheet'])) {
                $start = ((int)$_REQUEST['sheet'] - 1) * $limit;
            }
        }

        if ($this->getAttribute('parentInputList')) {
            // for bricks
            $count_children = Utils::getSitesByInputList($Project, $parents, [
                'count' => 'count',
                'order' => $this->getAttribute('order')
            ]);
        } else {
            // for site types
            if ($this->getAttribute('byType')) {
                $count_children = $Project->getSitesIds([
                    'count' => 'count',
                    'where' => [
                        'type' => $this->getAttribute('byType')
                    ]
                ]);

                if (\is_array($count_children[0])) {
                    $count_children = $count_children[0]['count'];
                }
            } else {
                $count_children = $Site->getChildren([
                    'count' => 'count',
                    'where' => $this->getAttribute('where')
                ]);
            }
        }

        if (\is_array($count_children)) {
            $count_children = \count($count_children);
        }

        // sheets
        $sheets                     = \ceil($count_children / $limit);
        $loadAllChildrenOnEmptyList = $this->getAttribute('loadAllChildrenOnEmptyList');
        $where                      = $this->getAttribute('where');

        if (empty($where)) {
            $where = [];
        }

        $where['active'] = 1;

        if ($this->getAttribute('parentInputList')) {
            // for bricks
            $children = Utils::getSitesByInputList($Project, $parents, [
                'where' => $where,
                'limit' => $start.','.$limit,
                'order' => $this->getAttribute('order')
            ]);
        } elseif ($this->getAttribute('children') || !$loadAllChildrenOnEmptyList) {
            $children = $this->getAttribute('children');
        } else {
            // for site types
            if ($this->getAttribute('byType')) {
                // get all sites, not just the direct children of a site
                $childIds = $Project->getSitesIds([
                    'where' => [
                        'active' => 1,
                        'type'   => $this->getAttribute('byType'),
                    ],
                    'order' => 'release_from DESC',
                    'limit' => $start.','.$limit
                ]);

                foreach ($childIds as $id) {
                    $children[] = $Project->get($id['id']);
                }
            } else {
                // get only direct children of a site
                $children = $Site->getChildren([
                    'where' => $where,
                    'limit' => $start.','.$limit
                ]);
            }
        }

        $Pagination->setAttribute('limit', $limit);
        $Pagination->setAttribute('sheets', $sheets);

        $Engine->assign([
            'this'       => $this,
            'Site'       => $this->getSite(),
            'Project'    => $this->getProject(),
            'sheets'     => $sheets,
            'children'   => $children,
            'Pagination' => $Pagination,
            'MetaList'   => new QUI\Controls\Utils\MetaList(),
            'Events'     => $this->Events
        ]);

        // load custom template (if set)
        if ($this->getAttribute('displayTemplate')
            && \file_exists($this->getAttribute('displayTemplate'))
        ) {
            if ($this->getAttribute('displayCss')
                && \file_exists($this->getAttribute('displayCss'))
            ) {
                $this->addCSSFile($this->getAttribute('displayCss'));
            }

            return $Engine->fetch($this->getAttribute('displayTemplate'));
        }

        switch ($this->getAttribute('display')) {
            default:
            case 'childrenList':
                $css      = \dirname(__FILE__).'/ChildrenList.css';
                $template = \dirname(__FILE__).'/ChildrenList.html';
                break;

            case 'longFooter':
                $css      = \dirname(__FILE__).'/ChildrenList.LongFooter.css';
                $template = \dirname(__FILE__).'/ChildrenList.LongFooter.html';
                break;

            case 'authorTop':
                $css      = \dirname(__FILE__).'/ChildrenList.AuthorTop.css';
                $template = \dirname(__FILE__).'/ChildrenList.AuthorTop.html';
                break;

            case '1er':
                $css      = \dirname(__FILE__).'/ChildrenList.1er.css';
                $template = \dirname(__FILE__).'/ChildrenList.1er.html';
                break;

            case '2er':
                $css      = \dirname(__FILE__).'/ChildrenList.2er.css';
                $template = \dirname(__FILE__).'/ChildrenList.2er.html';
                break;

            case '3er':
                $css      = \dirname(__FILE__).'/ChildrenList.3er.css';
                $template = \dirname(__FILE__).'/ChildrenList.3er.html';
                break;

            case '4er':
                $css      = \dirname(__FILE__).'/ChildrenList.4er.css';
                $template = \dirname(__FILE__).'/ChildrenList.4er.html';
                break;

            case 'simpleArticleList':
                $css      = \dirname(__FILE__).'/ChildrenList.SimpleArticleList.css';
                $template = \dirname(__FILE__).'/ChildrenList.SimpleArticleList.html';
                break;

            case 'advancedArticleList':
                $css      = \dirname(__FILE__).'/ChildrenList.AdvancedArticleList.css';
                $template = \dirname(__FILE__).'/ChildrenList.AdvancedArticleList.html';
                break;

            case 'imageTopBorder':
                $css      = \dirname(__FILE__).'/ChildrenList.ImageTopBorder.css';
                $template = \dirname(__FILE__).'/ChildrenList.ImageTopBorder.html';
                break;

            case 'imageTop':
                $css      = \dirname(__FILE__).'/ChildrenList.ImageTop.css';
                $template = \dirname(__FILE__).'/ChildrenList.ImageTop.html';
                break;

            case 'cardRows':
                $css      = \dirname(__FILE__).'/ChildrenList.CardRows.css';
                $template = \dirname(__FILE__).'/ChildrenList.CardRows.html';
                break;

            case 'CSSGridCards':
                $css      = \dirname(__FILE__).'/ChildrenList.CSSGridCards.css';
                $template = \dirname(__FILE__).'/ChildrenList.CSSGridCards.html';
                break;

            case 'gallery':
                $css      = \dirname(__FILE__).'/ChildrenList.Gallery.css';
                $template = \dirname(__FILE__).'/ChildrenList.Gallery.html';
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

        if ($this->getAttribute('showSheets')) {
            if (isset($_REQUEST['sheet'])) {
                $sheet = (int)$_REQUEST['sheet'];
            }
        }

        $count_children = $Site->getChildren([
            'count' => 'count'
        ]);

        $sheets = \ceil($count_children / $limit);

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
