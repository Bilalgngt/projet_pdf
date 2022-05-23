<?php


class GlobalParameterModel
{
    private $_language;

    public function __construct()
    {
        $this->setLanguage();
    }

    private function setLanguage()
    {
        $this->_language = 'name_fr';
    }

    private function getLanguage()
    {
        return $this->_language;
    }


    public function getAllGlobalParameterNamesOptions()
    {
        require '../database/database.php';
        $query = $pdo->prepare("SELECT " . $this->getLanguage() . ", id,unit,apply_on_soil,apply_on_eluate,apply_on_water,apply_on_air, 0 AS custom
                               FROM global_parameter_names
                               UNION 
                               SELECT " . $this->getLanguage() . ", id,unit,apply_on_soil,apply_on_eluate,apply_on_water,apply_on_air, 1 AS custom
                               FROM global_parameter_custom
                               WHERE 1
                           ");
        $query->execute();
        return $query->fetchAll();
    }


    public function findParameterNameByIdCustom($id, $custom, $globalParameters)
    {
        foreach ($globalParameters as $globalParameter) {
            if ($globalParameter['id'] === $id && $globalParameter['custom'] === $custom) {
                return $globalParameter[$this->getLanguage()];
            }
        }
        return 'error';
    }
}