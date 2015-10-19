<?php

class ConfigurationHospitalInfluenza {

    const HospitalDefault = 4;
    const HospitalChicho = 1;
    const HospitalJoseDomingo = 2;
    const HospitalNino = 3;
    const HospitalNivelNacional = 5;
    const silabLocalHospitalDefault = "Por defecto: I. C. Gorgas";
    const silabLocalHospitalChicho = "Hospital Luis Chicho Fabrega";
    const silabLocalHospitalJoseDomingo = "Hospital Jos&eacute; Domingo de Obaldia";
    const silabLocalHospitalNino = "Hospital del Ni&ntilde;o";
    const HospitalActual = ConfigurationHospitalInfluenza::HospitalDefault;
    
    const SISVIG = 0;
    const SILABLOCAL = 1;
    const SILABREMOTO = 2;

    public static function getSilabLocal() {
        switch (ConfigurationHospitalInfluenza::HospitalActual) {
            case ConfigurationHospitalInfluenza::HospitalChicho:
                return ConfigurationHospitalInfluenza::silabLocalHospitalChicho;
                break;
            case ConfigurationHospitalInfluenza::HospitalJoseDomingo:
                return ConfigurationHospitalInfluenza::silabLocalHospitalJoseDomingo;
                break;
            case ConfigurationHospitalInfluenza::HospitalNino:
                return ConfigurationHospitalInfluenza::silabLocalHospitalNino;
                break;
            case ConfigurationHospitalInfluenza::HospitalDefault:
                return ConfigurationHospitalInfluenza::silabLocalHospitalDefault;
                break;
        }
    }

}

?>
