<?php
/*"******************************************************************************************************
*   (c) 2007-2015 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
********************************************************************************************************/

/**
 * Returns a toggle button bar which can be used in the same way as an multiselect
 *
 * @author christoph.kappestein@gmail.com
 * @since 4.7
 * @package module_formgenerator
 */
class class_formentry_toggle_buttonbar extends class_formentry_multiselect {

    /**
     * Renders the field itself.
     * In most cases, based on the current toolkit.
     *
     * @return string
     */
    public function renderField() {
        $objToolkit = class_carrier::getInstance()->getObjToolkit("admin");
        $strReturn = "";
        if($this->getStrHint() != null) {
            $strReturn .= $objToolkit->formTextRow($this->getStrHint());
        }
        $strReturn .= $objToolkit->formToggleButtonBar($this->getStrEntryName(), $this->arrKeyValues, $this->getStrLabel(), explode(",", $this->getStrValue()), !$this->getBitReadonly());
        return $strReturn;
    }

}
