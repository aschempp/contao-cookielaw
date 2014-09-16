<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2012
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'cookielawEnabled';
$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] .= ';{cookielaw_legend:hide},cookielawEnabled';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['cookielawEnabled'] = 'cookielawBody,cookielawButtons,cookielawRecipient,cookielawBlocked';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['cookielawEnabled'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_page']['cookielawEnabled'],
    'exclude'           => true,
    'inputType'         => 'checkbox',
    'eval'              => array('submitOnChange'=>true, 'tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_page']['fields']['cookielawBody'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_page']['cookielawBody'],
    'exclude'           => true,
    'inputType'         => 'textarea',
    'eval'              => array('mandatory'=>true, 'preserveTags'=>true, 'decodeEntities'=>true, 'style'=>'height:80px', 'tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_page']['fields']['cookielawButtons'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_page']['cookielawButtons'],
    'exclude'           => true,
    'inputType'         => 'text',
    'eval'              => array('multiple'=>true, 'size'=>2, 'maxlength'=>100, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_page']['fields']['cookielawRecipient'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_page']['cookielawRecipient'],
    'exclude'           => true,
    'inputType'         => 'text',
    'eval'              => array('maxlength'=>255, 'rgxp'=>'email', 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_page']['fields']['cookielawBlocked'] = array
(
    'label'             => &$GLOBALS['TL_LANG']['tl_page']['cookielawBlocked'],
    'exclude'           => true,
    'inputType'         => 'listWizard',
    'eval'              => array('tl_class'=>'clr'),
);
