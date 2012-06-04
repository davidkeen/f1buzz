<?php

/**
 * Description of Prediction
 *
 * @author david
 */
class Application_Form_Prediction extends Zend_Form
{
    private $prediction;

    public function  __construct(Application_Model_Prediction $prediction, $options = null)
    {
        $this->prediction = $prediction;

        // If predictionId exists we are updating so add the ID.
        if ($prediction->predictionId) {
            $this->addElement("hidden", "predictionId", array(
                "value" => $this->prediction->predictionId));
        }

        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod('post');

        // Add elements
        $this->addElement("hidden", "raceId", array(
            'required' => true));

        $this->addElement($this->getDriverSelect("pole", "Pole"));

        $firstSelect = $this->getDriverSelect("first", "First");
        $firstSelect->addPrefixPath("F1buzz", "F1buzz");
        $firstSelect->addValidator("DuplicateDrivers", false, array(array("second", "third")));
        $this->addElement($firstSelect);

        $secondSelect = $this->getDriverSelect("second", "Second");
        $secondSelect->addPrefixPath("F1buzz", "F1buzz");
        $secondSelect->addValidator("DuplicateDrivers", false, array(array("first", "third")));
        $this->addElement($secondSelect);

        $thirdSelect = $this->getDriverSelect("third", "Third");
        $thirdSelect->addPrefixPath("F1buzz", "F1buzz");
        $thirdSelect->addValidator("DuplicateDrivers", false, array(array("first", "second")));
        $this->addElement($thirdSelect);
        
        $this->addElement($this->getDriverSelect("fastest", "Fastest"));

        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label' => 'Submit'));

        $this->populateForm($this->prediction);
    }

    private function populateForm()
    {
        // TODO: add null checks to these assignments.
        $values = array();
        $values["predictionId"] = $this->prediction->predictionId;
        $values["raceId"] = $this->prediction->raceId;
        $values["pole"] = $this->prediction->pole;
        $values["first"] = $this->prediction->first;
        $values["second"] = $this->prediction->second;
        $values["third"] = $this->prediction->third;
        $values["fastest"] = $this->prediction->fastest;
        $this->setDefaults($values);
    }

    private function getDriverSelect($elementName, $elementLabel)
    {
        $driverMapper = new Application_Model_DriverMapper();
        $aDrivers = $driverMapper->fetchAll();
        usort($aDrivers, array('Application_Form_Prediction', 'compareDrivers'));

        $driverSelect = new Zend_Form_Element_Select($elementName);
        $driverSelect->setLabel($elementLabel);
        $driverSelect->addMultiOption("", "Select a driver");
        foreach ($aDrivers as $driver) {
            $driverSelect->addMultiOption($driver->driverId, $driver->name . " (" . $driver->team . ")");
        }
        $driverSelect->setRequired(true);
        return $driverSelect;
    }

    /**
     * Sorts drivers by name.
     * Should we sort by last name?
     *
     * @param Application_Model_Driver $driver
     * @param Application_Model_Driver $otherDriver
     * @return int
     */
    private static function compareDrivers(Application_Model_Driver $driver,
            Application_Model_Driver $otherDriver)
    {
        $driverLastName = substr(strrchr($driver->name, " "), 1);
        $otherDriverLastName = substr(strrchr($otherDriver->name, " "), 1);
        return strcasecmp($driverLastName, $otherDriverLastName);
    }

}
