<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/helper/helperString.php');
require_once ('libs/helper/helperCatalogos.php');

class dalVistasRevelac {

    private $vista;
    private $filtro;
    private $search;

    public function __construct($data) {
        $this->vista = "view_revelac";
        $this->filtro = $data["filtro"];
        $this->search = $data["search"];
    }

    private function getDatos() {
        $sql = "
select
(case when uceti.eve_sindrome = 1 then '8' 
      when uceti.eve_centinela = 1 then '1' 
      when uceti.eve_inusitado = 1 then '0'
      when uceti.eve_imprevisto = 1 then '8'
      when uceti.eve_excesivo = 1 then '2'
      when uceti.eve_conglomerado = 1 then '8'
      when uceti.eve_neumo_bacteriana = 1 then '8'
      else '8' end) as surv,
(case when per.sexo = 'F' then '0' 
      when per.sexo = 'M' then '1' 
      else '' end) as sex,
(case when uceti.per_tipo_edad = 1 then '0' 
      when uceti.per_tipo_edad = 2 then ROUND((uceti.per_edad/12),2)
      when uceti.per_tipo_edad = 3 then uceti.per_edad
      else '' end) as age_yrs,
IFNULL(DATE_FORMAT(uceti.fecha_inicio_sintoma,'%d/%m/%Y'),'') as onset_date,
(case when uceti.riesgo_enf_cronica = '1' then '1' 
      when uceti.riesgo_enf_cronica = '0' then '0' 
      else '8' end) as preexist_cond,
uceti.vac_tarjeta as vacc_card_dose1,
IFNULL((select (case when antecedente.dosis > 0 then '1' 
                     when antecedente.dosis = 0 then '0'
                     else '0' end)
from flureg_antecendente_vacunal antecedente 
where antecedente.tipo_identificacion = per.tipo_identificacion 
      AND antecedente.numero_identificacion = per.numero_identificacion
      AND antecedente.id_cat_antecendente_vacunal = 2),'8') as curr_vacc,
IFNULL((select (case when antecedente.fecha != '' then DATE_FORMAT(antecedente.fecha,'%d/%m/%Y')
                     else '' end)
from flureg_antecendente_vacunal antecedente 
where antecedente.tipo_identificacion = per.tipo_identificacion 
      AND antecedente.numero_identificacion = per.numero_identificacion
      AND antecedente.id_cat_antecendente_vacunal = 2),'') as curr_vacc_date,
'8' as vacc_card_dose2,
(case when uceti.vac_fecha_ultima_dosis = '0000-00-00' then '0' 
      when uceti.vac_fecha_ultima_dosis = '9999-99-99' then '8' 
      when IFNULL(uceti.vac_fecha_ultima_dosis,'') != '' then '1'
      else '8' end) as curr_vacc_dose2,
(case when uceti.vac_fecha_ultima_dosis = '0000-00-00' then '' 
      when uceti.vac_fecha_ultima_dosis = '9999-99-99' then '' 
      when uceti.vac_fecha_ultima_dosis != '' then IFNULL(DATE_FORMAT(uceti.vac_fecha_ultima_dosis,'%d/%m/%Y'),'')
      else '' end) as curr_vacc_dose2_date,
(case when uceti.vac_fecha_anio_previo = '0000-00-00' then '0' 
      when uceti.vac_fecha_anio_previo = '9999-99-99' then '8' 
      when IFNULL(uceti.vac_fecha_anio_previo,'') != '' then '1'
      else '8' end) as prev_flu_vacc,
(case when uceti.vac_fecha_anio_previo = '0000-00-00' then '' 
      when uceti.vac_fecha_anio_previo = '9999-99-99' then '' 
      when uceti.vac_fecha_anio_previo != '' then IFNULL(DATE_FORMAT(uceti.vac_fecha_anio_previo,'%d/%m/%Y'),'')
      else '' end) as prev_flu_vacc_date,
IFNULL((select (case when COUNT(antecedente.dosis) > 0 then '1' 
                     when COUNT(antecedente.dosis) = 0 then '0'
                     else '0' end)
from flureg_antecendente_vacunal antecedente 
where antecedente.tipo_identificacion = per.tipo_identificacion 
      AND antecedente.numero_identificacion = per.numero_identificacion
      AND (antecedente.id_cat_antecendente_vacunal = 3 
      or antecedente.id_cat_antecendente_vacunal = 4 
      or antecedente.id_cat_antecendente_vacunal = 5
      or antecedente.id_cat_antecendente_vacunal = 6)
      and antecedente.dosis > 0
      ),'8') as pneumo_vacc,
IFNULL((select (case when COUNT(prueba.resultado_prueba) > 0 then '1' 
                     when COUNT(prueba.resultado_prueba) = 0 then '0'
                     else '0' end)
from flureg_muestra_prueba_silab prueba
where prueba.id_flureg = uceti.id_flureg
      and prueba.nombre_prueba like '%PCR%'
      and prueba.resultado_prueba = 'Positivo'
      ),'0') as 'case',
IFNULL(DATE_FORMAT(uceti.fecha_hospitalizacion,'%d/%m/%Y'),'') as admission_date,
IFNULL(DATE_FORMAT(uceti.fecha_egreso,'%d/%m/%Y'),'') as discharge_date,
(case when uceti.per_hospitalizado_lugar = '3' then '1' 
      when uceti.per_hospitalizado_lugar = '2' then '0' 
      when uceti.per_hospitalizado_lugar = '1' then '0' 
      else '8' end) as ICU,
(case when uceti.antiviral = '1' then '1' 
      when uceti.antiviral = '0' then '0' 
      else '8' end) as antiviral,
(case when uceti.antiviral = '1' then '1'
      else '8' end) as antiviral_type,
'8' as '1st_vacc_child',
IFNULL((select DATE_FORMAT(muestra.fecha_toma,'%d/%m/%Y')
from flureg_muestra_laboratorio muestra
where muestra.id_flureg = uceti.id_flureg limit 1),'') as sample_date,
IFNULL((select (case when count(prueba.id_flureg) > 0 then '0'
                     else null end)
from flureg_muestra_silab prueba
where prueba.id_flureg = uceti.id_flureg
      and (prueba.tipo1 like '%influenza a%'
      or prueba.tipo2 like '%influenza a%'
      or prueba.tipo3 like '%influenza a%')),
      IFNULL((select (case when count(prueba.id_flureg) > 0 then '1'
                     else null end)
      from flureg_muestra_silab prueba
      where prueba.id_flureg = uceti.id_flureg
            and (prueba.tipo1 like '%influenza b%'
            or prueba.tipo2 like '%influenza b%'
            or prueba.tipo3 like '%influenza b%')),'8')
      ) as flu_type,
IFNULL((select (case when count(prueba.id_flureg) > 0 then '0'
                     else null end)
from flureg_muestra_silab prueba
where prueba.id_flureg = uceti.id_flureg
      and (prueba.subtipo1 like '%h3n2%'
      or prueba.subtipo2 like '%h3n2%'
      or prueba.subtipo3 like '%h3n2%')),
      IFNULL((select (case when count(prueba.id_flureg) > 0 then '1'
                     else null end)
      from flureg_muestra_silab prueba
      where prueba.id_flureg = uceti.id_flureg
            and (prueba.subtipo1 like '%h1n1%'
            or prueba.subtipo2 like '%h1n1%'
            or prueba.subtipo3 like '%h1n1%')),'2')
      ) as flu_subtype,
IFNULL((select (case when count(prueba.id_flureg) > 0 then '1'
                     when count(prueba.id_flureg) = 0 then '0'
                     else null end)
from flureg_muestra_silab prueba
where prueba.id_flureg = uceti.id_flureg
      and (prueba.tipo1 not like '%influenza a%'
      or prueba.tipo2 not like '%influenza a%'
      or prueba.tipo3 not like '%influenza a%'
      or prueba.tipo1 not like '%influenza b%'
      or prueba.tipo2 not like '%influenza b%'
      or prueba.tipo3 not like '%influenza b%')),
      '8') as other_virus,
(case when IFNULL(uceti.fecha_defuncion,'') != '' then '1'
      when uceti.fecha_defuncion = '' then '0'
      else '8' end) as death,
(case when uceti.sintoma_fiebre = '0' then '0' 
      when uceti.sintoma_fiebre = '1' then '1' 
      when uceti.sintoma_fiebre = '2' then '8' 
      else '8' end) as fever,
(case when uceti.sintoma_tos = '0' then '0' 
      when uceti.sintoma_tos = '1' then '1' 
      when uceti.sintoma_tos = '2' then '8' 
      else '8' end) as cough,
(case when uceti.sintoma_garganta = '0' then '0' 
      when uceti.sintoma_garganta = '1' then '1' 
      when uceti.sintoma_garganta = '2' then '8' 
      else '8' end) as sore_throat,
(case when uceti.sintoma_respiratoria = '0' then '0' 
      when uceti.sintoma_respiratoria = '1' then '1' 
      when uceti.sintoma_respiratoria = '2' then '8' 
      else '8' end) as difficult_breath,
'8' as short_breath,
IFNULL(uceti.per_hospitalizado,'8') as hospitalization,
'' as adm_diagn,
'8' as diab,
'8' as cardio,
'8' as pulm_dis,
'8' as renal_dis,
'8' as liver_dis,
'8' as immuno,
'8' as obese,
'8' as other_chron,
IFNULL(uceti.riesgo_embarazo,'8') as pregnant
from flureg_form uceti
inner join tbl_persona per on per.tipo_identificacion = uceti.tipo_identificacion and per.numero_identificacion = uceti.numero_identificacion
where uceti.pendiente_uceti = '1' and ((uceti.per_tipo_edad = 2 and ROUND((uceti.per_edad/12),2) >= 0.5) or (uceti.per_tipo_edad = 3 and (uceti.per_edad <= 5 or uceti.per_edad >= 60)))";

        if ($this->filtro != "")
            $sql .= $this->filtro;
        $sql .= " order by uceti.id_flureg DESC";

//        if ($this->ficha != Configuration::vih){
//            $sql .= "and v.anio_epi = ? ";
//            $sql .= "and v.Semana >= ? ";
//            $sql .= "and v.Semana <= ? ";    
//        }
//        echo $sql;
//        exit;
//        $sql .= $this->filtro;

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();

        $conn->closeConn();

        return $data;
    }

    public function getData() {
        $data = $this->getDatos();
//        print_r($this->getDatos());
//        // Borrar los campos qu[e no se necesitan
//        unset($data["id_un"]); //id_un   
//        unset($data["semana_epi"]); //semana_epi
//        unset($data["anio"]); //anio
        return $data;
    }

}

?>