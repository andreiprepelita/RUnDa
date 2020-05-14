<?php
require_once "../../config/Database.php";
class Varste
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function selectAllVarste()
    {

        $query = "SELECT
        varste.judet, varste.id_judet, varste.month, varste.year,
        varste.total_someri,varste.sub_25_ani,varste.25_29_ani,
        varste.30_39_ani,varste.40_49_ani,varste.50_55_ani,varste.peste_55_ani
        FROM varste";

        return $this->db->fetchAllCounties($query);
    }
    public function selectOneCounty($parameter, $queryArray = null) //cu fetchOne scot un JSON!
    {
        $query = "SELECT
       orase.city AS ORASE, varste.id_judet, varste.month, varste.year,
        varste.total_someri,varste.sub_25_ani,varste.25_29_ani,
        varste.30_39_ani,varste.40_49_ani,varste.50_55_ani,varste.peste_55_ani
        FROM varste
        JOIN orase ON varste.id_judet = orase.id
        WHERE orase.city ='" . $parameter . "'";
        if ($this->db->existCity($query, $parameter) == 0)
            return 0;
        $array_sorted_by = [];
        $array_param = [];
        $valuesSort = "";
        foreach ($queryArray as $key => $value)
            switch ($key) {
                case "filtered_by":
                    break;
                case "sorted_by":
                    $valuesSort = $value;
                    break;
                default:
                    $query = $query . " AND varste." . $key . "='" . $value . "'";
                    array_push($array_param, $value);
            }
        if ($valuesSort != null)
            $array_sorted_by = explode(",", $valuesSort);
        if (count($array_sorted_by) > 0) {
            $query = $query . " ORDER BY ";
            for ($i = 0; $i < count($array_sorted_by); $i++) {
                if ($i + 1 == count($array_sorted_by))
                    $query = $query . "varste." . $array_sorted_by[$i];
                else
                    $query = $query . "varste." . $array_sorted_by[$i] . ",";
            }
        }


        return $this->db->fetchAllCounties($query);
    }

    //daca parametrul este CLUJ, atunci selectez toate id-urile unice si afisez JSON

    // public function selectOneCounty($parameter)
    // {
    //     $query = "SELECT
    //     educatie.id, educatie.judet, educatie.id_judet, educatie.month, educatie.year,
    //     educatie.total_someri,educatie.fara_studii, educatie.primar,
    //     educatie.gimnazial, educatie.liceal, educatie.postliceal,
    //     educatie.profesional_arte_meserii, educatie.universitar
    //     FROM educatie
    //     WHERE educatie.id_judet = ?";
    //     return $this->db->fetchOne($query, $parameter);
    // }
}