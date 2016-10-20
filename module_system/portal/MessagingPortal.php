<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2016 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
*	$Id$									*
********************************************************************************************************/

namespace Kajona\System\Portal;

use Kajona\News\System\NewsCategory;
use Kajona\News\System\NewsFeed;
use Kajona\News\System\NewsNews;
use Kajona\Pages\Portal\PagesPortalController;
use Kajona\Pages\Portal\PagesPortaleditor;
use Kajona\Pages\System\PagesPortaleditorActionEnum;
use Kajona\Pages\System\PagesPortaleditorSystemidAction;
use Kajona\Postacomment\Portal\PostacommentPortal;
use Kajona\Postacomment\System\PostacommentPost;
use Kajona\Rating\Portal\RatingPortal;
use Kajona\System\Portal\PortalController;
use Kajona\System\Portal\PortalInterface;
use Kajona\System\System\ArraySectionIterator;
use Kajona\System\System\HttpResponsetypes;
use Kajona\System\System\Link;
use Kajona\System\System\MessagingMessage;
use Kajona\System\System\Objectfactory;
use Kajona\System\System\ResponseObject;
use Kajona\System\System\Rssfeed;
use Kajona\System\System\SystemModule;
use Kajona\System\System\TemplateMapper;

/**
 * Portal-class of the messaging framework
 *
 * @author sidler@mulchprod.de
 *
 * @module messaging
 * @moduleId _messaging_module_id_
 */
class MessagingPortal extends PortalController implements PortalInterface
{


    /**
     * Default implementation to avoid mail-spamming.
     *
     * @return void
     */
    protected function actionList()
    {

    }

    /**
     * @return string
     */
    protected function actionSetRead()
    {
        $objMessage = Objectfactory::getInstance()->getObject($this->getSystemid());

        if ($objMessage !== null && $objMessage instanceof MessagingMessage && $objMessage->getBitRead() == 0) {
            $objMessage->setBitRead(1);
            $objMessage->updateObjectToDb();
        }


        header("Content-type: image/gif");
        ResponseObject::getInstance()->setStrResponseType(HttpResponsetypes::STR_TYPE_GIF);
        ResponseObject::getInstance()->setStrContent(base64_decode("R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="));
        return base64_decode("R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==");
    }

}
