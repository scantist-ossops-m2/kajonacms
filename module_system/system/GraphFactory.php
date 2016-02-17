<?php
/*"******************************************************************************************************
*   (c) 2007-2015 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
*	$Id$                                      *
********************************************************************************************************/

namespace Kajona\System\System;

use ReflectionClass;


/**
 * Generates a graph-instance based on the current config.
 * Therefore either ez components or pChart will be used.
 * Since pChart won't be shipped with kajona, the user has to download it manually.
 *
 * @author sidler@mulchprod.de
 * @since 3.4
 * @package module_system
 */
class GraphFactory {
    //put your code here

    public static $STR_TYPE_EZC = "ezc";
    public static $STR_TYPE_PCHART = "pchart";
    public static $STR_TYPE_FLOT = "flot";
    public static $STR_TYPE_JQPLOT = "jqplot";


    /**
     * Creates a graph-instance either based on the current config or
     * based on the passed param
     *
     * @param string $strType
     *
     * @throws Exception
     * @return GraphInterface
     */
    public static function getGraphInstance($strType = "") {

        if($strType == "") {
            if(SystemSetting::getConfigValue("_system_graph_type_") != "")
                $strType = SystemSetting::getConfigValue("_system_graph_type_");
            else
                $strType = "jqplot";
        }

        $strClassname = "class_graph_".$strType;
        $strPath = Resourceloader::getInstance()->getPathForFile("/system/".$strClassname.".php");
        if($strPath !== false) {
            $objReflection = new ReflectionClass($strClassname);
            if(!$objReflection->isAbstract() && $objReflection->implementsInterface("interface_graph"))
                return $objReflection->newInstance();
        }

        $strClassname = "Graph".$strType;
        $strPath = Resourceloader::getInstance()->getPathForFile("/system/".$strClassname.".php");
        if($strPath !== false) {
            $objReflection = new ReflectionClass($strClassname);
            if(!$objReflection->isAbstract() && $objReflection->implementsInterface("interface_graph"))
                return $objReflection->newInstance();
        }

        throw new Exception("Requested charts-plugin ".$strType." not existing", Exception::$level_FATALERROR);
    }
}
