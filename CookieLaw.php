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


class CookieLaw extends Frontend
{

    public function injectScript($objPage, $objLayout, $objPageRegular)
    {
        if ($this->Input->cookie('legalcookie-' . str_replace('.', '_', $objPage->domain)) != 'true')
        {
            $objRootPage = $this->Database->execute("SELECT * FROM tl_page WHERE id=" . (int) $objPage->rootId);

            if ($objRootPage->numRows && $objRootPage->cookielawEnabled)
            {
                $arrButtons = deserialize($objRootPage->cookielawButtons, true);

                $GLOBALS['TL_HEAD'][] = '
<script type="text/javascript">
window.cookielaw = {
    messageBody: "' . str_replace(array('"', "\r", "\n"), array('\"', '', '<br />'), $this->replaceInsertTags($objRootPage->cookielawBody)) . '",
    acceptButton: "' . specialchars($arrButtons[0]) . '",
    declineButton: "' . specialchars($arrButtons[1]) . '",
    blockedUrls: ["' . implode('","', deserialize($objRootPage->cookielawBlocked, true)) . '"]' . ($objRootPage->cookielawRecipient == '' ? '' : ',
    confirmUrl: "ajax.php?action=cookielaw&pageId=' . $objPage->id) . '"
};
</script>
<script type="text/javascript" src="system/modules/cookielaw/assets/cookielaw.min.js"></script>';

                // Do not add the shim now
                return;
            }
        }

        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/cookielaw/assets/cookielaw-shim.js';
    }


    public function sendConfirmationMail()
    {
        global $objPage;

        if ($this->Input->get('action') == 'cookielaw' && !empty($_COOKIE) && $this->Input->cookie('legalcookie-' . str_replace('.', '_', $objPage->domain)) == 'true' && $this->Input->post('site') != '')
        {
            $objRootPage = $this->Database->execute("SELECT * FROM tl_page WHERE id=" . (int) $objPage->rootId);

            if ($objRootPage->numRows
                && $objRootPage->cookielawEnabled
                && $objRootPage->cookielawRecipient != ''
                && $this->isValidEmailAddress($objRootPage->cookielawRecipient))
            {
                $objEmail = new Email();
                $objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
                $objEmail->subject = 'Cookie Law';
                $objEmail->text = '
SiteID: ' . $this->Input->post('site') . '
IP-Address: ' . $this->Environment->ip . '
Time: ' . date('c') . '
Cookies: ' . implode(', ', array_keys($_COOKIE)) . '
';

                $objEmail->sendTo($objRootPage->cookielawRecipient);
            }

            return true;
        }

        return false;
    }
}
