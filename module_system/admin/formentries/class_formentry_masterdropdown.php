<?php
/*"******************************************************************************************************
*   (c) 2007-2014 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
*	$Id$                               *
********************************************************************************************************/

/**
 * The master-dropdown may be used to fill a list of dropdowns in dependency of each other.
 *
 * @author sidler@mulchprod.de
 * @since 4.6
 * @package module_formgenerator
 */
class class_formentry_masterdropdown extends class_formentry_base implements interface_formentry_printable {

    const STR_VALUE_ANNOTATION = "@fieldValuePrefix";
    const STR_DEPENDS_ANNOTATION = "@fieldDependsOn";


    private $arrLabels = array();
    private $arrDepends = array();

    public function __construct($strFormName, $strSourceProperty, $objSourceObject = null) {
        parent::__construct($strFormName, $strSourceProperty, $objSourceObject);

        //set the default validator
        $this->setObjValidator(new class_text_validator());
    }

    /**
     * Renders the field itself.
     * In most cases, based on the current toolkit.
     *
     * @return string
     */
    public function renderField() {
        $objToolkit = class_carrier::getInstance()->getObjToolkit("admin");
        $strReturn = "";
        if($this->getStrHint() != null)
            $strReturn .= $objToolkit->formTextRow($this->getStrHint());

        $strValues = $this->arrLabels;
        $arrNewValues = array();
        array_walk($strValues, function(&$strValue, &$strKey) use(&$arrNewValues) {
            $strKey = uniStrtolower(uniStrReplace(array("int", "str"), "", $strKey));
            $strKey = $this->getStrFormName()."_".$strKey;
            $arrNewValues[$strKey] = $strValue;
        });
        $strValues = json_encode($arrNewValues);

        $strIdFirst = $this->getStrEntryName();
        $strNamespace = "a".generateSystemid();

        $arrFormentries = $this->arrDepends;
        array_walk($arrFormentries, function(&$strValue) {
            $strValue = uniStrtolower(uniStrReplace(array("int", "str"), "", $strValue));
            $strValue = $this->getStrFormName()."_".$strValue;
        });
        $arrFormentries = array_merge(array($this->getStrEntryName()), $arrFormentries);
        $strFormentries = json_encode($arrFormentries);

        $strJs = <<<JS
            var {$strNamespace} = {
                objValues : {$strValues},
                objDependent : {$strFormentries},
                strPrimary : '{$strIdFirst}',
                bitInitial : true,


                updateDependentEntries : function(objSource) {
                    if(objSource) {
                        //only update the subordinate levels
                        $.each(this.objDependent, function(key, value) {
                            if($(objSource).attr('id') == value) {
                                {$strNamespace}.updateNextEntry(key+1, $(objSource).val());
                            }
                        });
                    }
                    else {
                        //initial init
                        this.updateNextEntry(0, "");
                    }
                },

                updateNextEntry : function(intDependentIndex, strPrefix) {
                    arrNames = jQuery.makeArray(this.objDependent);
                    strTargetElement = arrNames[intDependentIndex];

                    if(!strTargetElement)
                        return;

                    objTargetElement = $('#'+strTargetElement)

                    if(strPrefix == "") {
                        arrValues = (this.objValues[strTargetElement]);
                    }
                    else {
                        if(strPrefix[0] != "_")
                            strPrefix = "_"+strPrefix;

                        arrValues = (this.objValues[strTargetElement][strPrefix]);
                    }

                    objTargetElement.empty();

                    if(!arrValues)
                        return;

                    $.each(arrValues, function(key, value) {
                         objTargetElement.append($("<option></option>").attr("value", key).text(value));
                    });

                    if(this.bitInitial) {
                        objTargetElement.val(objTargetElement.attr("data-kajona-selected"));
                    }

                    strSelected = objTargetElement.val();
                    if(strPrefix == "")
                        strSelected = "_"+strSelected;

                    this.updateNextEntry(intDependentIndex+1, strSelected);

                }
            };

        $(function(){
            //initial fillings
            {$strNamespace}.updateDependentEntries();
            {$strNamespace}.bitInitial = false;
            $.each({$strNamespace}.objDependent, function(key, value) {
                 $('#'+value).change(function() { {$strNamespace}.updateDependentEntries(this); });
            });
        });
JS;


        $strReturn .=  "<script type='text/javascript'>".$strJs."</script>".$objToolkit->formInputDropdown($this->getStrEntryName(), array(), $this->getStrLabel(), "", "", !$this->getBitReadonly(), " data-kajona-selected='".$this->getStrValue()."' ");
        return $strReturn;
    }

    /**
     * Overwritten in order to load key-value pairs declared by annotations
     */
    protected function updateValue() {
        parent::updateValue();

        //load all matching and possible values based on the prefix
        if($this->getObjSourceObject() != null && $this->getStrSourceProperty() != "") {

            $strPrefix = "";
            $arrDepends = array();

            $this->getValueForAnnotations($strPrefix, $arrDepends);

            if($strPrefix == "" || count($arrDepends) == 0)
                return;

            $this->arrDepends = $arrDepends;


            //load all language entries
            $this->arrLabels = array($this->getStrSourceProperty() => array());
            foreach($this->arrDepends as $strOneDepend)
                $this->arrLabels[$strOneDepend] = array();


            $intI = 1;
            $strText = $this->getObjSourceObject()->getLang($strPrefix."_".$intI);

            while($strText != "!".$strPrefix."_".$intI."!") {
                $this->arrLabels[$this->getStrSourceProperty()][$intI] = $this->getObjSourceObject()->getLang($strPrefix."_".$intI);

                //search sub keys
                $this->getSublevel($strPrefix, "_".$intI, 0);

                $strText = $this->getObjSourceObject()->getLang($strPrefix."_".++$intI);
            }

        }
    }


    private function getValueForAnnotations(&$strPrefix, &$arrDepends) {
        $objReflection = new class_reflection($this->getObjSourceObject());

        //try to find the matching source property
        $arrProperties = $objReflection->getPropertiesWithAnnotation(self::STR_VALUE_ANNOTATION);
        $strSourceProperty = null;
        foreach($arrProperties as $strPropertyName => $strValue) {
            if(uniSubstr(uniStrtolower($strPropertyName), (uniStrlen($this->getStrSourceProperty()))*-1) == $this->getStrSourceProperty())
                $strSourceProperty = $strPropertyName;
        }

        if($strSourceProperty == null)
            return;

        $strPrefix = trim($objReflection->getAnnotationValueForProperty($strSourceProperty, self::STR_VALUE_ANNOTATION));
        $strDependant = trim($objReflection->getAnnotationValueForProperty($strSourceProperty, self::STR_DEPENDS_ANNOTATION));
        $arrDepends = explode(" ", $strDependant);
        array_walk($arrDepends, function(&$strValue) { $strValue = trim($strValue); });
    }

    private function getSublevel($strVarLabel, $strPrefix, $intLevel) {
        if(!isset($this->arrDepends[$intLevel]))
            return;

        $intI = 1;
        $strText = $this->getObjSourceObject()->getLang($strVarLabel.$strPrefix."_".$intI);

        while($strText != "!".$strVarLabel.$strPrefix."_".$intI."!") {
            $this->arrLabels[$this->arrDepends[$intLevel]][$strPrefix][$strPrefix."_".$intI] = $this->getObjSourceObject()->getLang($strVarLabel.$strPrefix."_".$intI);

            //search sub keys
            $this->getSublevel($strVarLabel, $strPrefix."_".$intI, $intLevel+1);

            $strText = $this->getObjSourceObject()->getLang($strVarLabel.$strPrefix."_".++$intI);
        }
    }







    /**
     * Returns a textual representation of the formentries' value.
     * May contain html, but should be stripped down to text-only.
     *
     * @return string
     */
    public function getValueAsText() {
        //load all matching and possible values based on the prefix
        if($this->getObjSourceObject() != null && $this->getStrSourceProperty() != "") {

            $strPrefix = "";
            $arrDepends = array();

            $this->getValueForAnnotations($strPrefix, $arrDepends);

            $strValue = $this->getStrValue();
            if($strValue[0] != "_")
                $strValue = "_".$strValue;

            return $this->getObjSourceObject()->getLang($strPrefix.$strValue);

        }
        return "Error: No target object mapped or missing @fieldValuePrefix annotation!";
    }








}
