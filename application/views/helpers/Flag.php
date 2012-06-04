<?php

class Net_Sharedmemory_View_Helper_Flag
{
    // Map of shortname to icon name.
    private $aFlagMap = array(
        "BHR" => "bh.gif",
        "AUS" => "au.gif",
        "MYS" => "my.gif",
        "CHN" => "cn.gif",
        "ESP" => "es.gif",
        "MCO" => "mc.gif",
        "TUR" => "tr.gif",
        "CAN" => "ca.gif",
        "EUR" => "europeanunion.gif",
        "GBR" => "gb.gif",
        "DEU" => "de.gif",
        "HUN" => "hu.gif",
        "BEL" => "be.gif",
        "ITA" => "it.gif",
        "SGP" => "sg.gif",
        "JPN" => "jp.gif",
        "KOR" => "kr.gif",
        "BRA" => "br.gif",
        "ARE" => "ae.gif",
        "IND" => "in.gif");

    public function Flag(Application_Model_Location $location)
    {
        return $this->aFlagMap[$location->shortName];
    }
}
