<?php

class GeologyModel
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

    // Fonction utilisée par le générateur, ne pas supprimer
    public function getAllGeologyNames()
    {
        require '../database/database.php';
        $query = $pdo->prepare("SELECT " . $this->getLanguage() . ",id, 0 AS custom,pattern
                               FROM geology_names
                               UNION
                               SELECT " . $this->getLanguage() . ",id, 1 AS custom,pattern
                               FROM geology_custom
                               WHERE 1
                               ");
        $query->execute();
        return $query->fetchAll();
    }

    public function findGeologyNameByIdCustom($geologyId, $custom, $geologyNames)
    {
        foreach ($geologyNames as $geologyName) {
            if ($geologyName['id'] === $geologyId && $geologyName['custom'] === $custom) {
                return $geologyName[$this->getLanguage()];
            }
        }
        return 'error';
    }

    public function findPatternIdByIdCustom($geologyId, $custom, $geologyNames)
    {
        foreach ($geologyNames as $geologyName) {
            if ($geologyName['id'] === $geologyId && $geologyName['custom'] === $custom) {
                return $geologyName['pattern'];
            }
        }
        return 'error';
    }
}
