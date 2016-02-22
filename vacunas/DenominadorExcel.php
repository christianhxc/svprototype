<?php
/**
 * XLS parsing uses php-excel-reader from http://code.google.com/p/php-excel-reader/
 */ 

  function convert_excel_array($path_file,$year_denominador){
      
        
        $Filepath = $path_file;

        
        if ($Filepath == "")
        {
            return $data=array();
            exit;
        }

	// Excel reader from http://code.google.com/p/php-excel-reader/
	require('../libs/excel_reader2.php');
	require('../libs/SpreadsheetReader.php');
//        require('DenominadorDetalleExcel.php');

	date_default_timezone_set('UTC');

	$StartMem = memory_get_usage();

	try
	{
		$Spreadsheet = new SpreadsheetReader($Filepath);
		$BaseMem = memory_get_usage();

		$Sheets = $Spreadsheet -> Sheets();

		foreach ($Sheets as $Index => $Name)
		{

			$Time = microtime(true);

			$Spreadsheet -> ChangeSheet($Index);
                        
//                        print_r($Spreadsheet);
                        $dummy = array();
                        $final = array();
                        $i_d=0;
			foreach ($Spreadsheet as $key_row)
			{
                                $dummy[$i_d]=$key_row;
                                $i_d=$i_d+1;
			}
                        
//                        print_r($dummy);
                        
                        if ($dummy[0][2]=="COD_DIST" && $dummy[0][4]=="COD_COREG" && $dummy[0][6]=="SEXO"){
                            
//                            echo "aquí";
                            
                            $i_d= 0;
                            foreach ($dummy as $key_dummy => $row_dummy)
                            {
                                    if ($row_dummy[6]=="TOTAL")
                                    {
                                            $final[$i_d]["anio"] = $year_denominador;
                                            $final[$i_d]["nivel"] = "5";
                                            $final[$i_d]["region"] = sprintf("%02d",$row_dummy[0]);
                                            $final[$i_d]["distrito"] = sprintf("%04d",$row_dummy[2]);
                                            $final[$i_d]["corregimiento"] = sprintf("%02d",$row_dummy[4]);
                                            $casosHombre = val_cero($dummy[$key_dummy+1][11])."##".val_cero($dummy[$key_dummy+1][12])."##".val_cero($dummy[$key_dummy+1][13])."##".val_cero($dummy[$key_dummy+1][14])."##".val_cero($dummy[$key_dummy+1][15])."##".val_cero($dummy[$key_dummy+1][16])."##".val_cero($dummy[$key_dummy+1][17])."##".val_cero($dummy[$key_dummy+1][18])."##".val_cero($dummy[$key_dummy+1][19])."##".val_cero($dummy[$key_dummy+1][20])."##".val_cero($dummy[$key_dummy+1][21])."##".val_cero($dummy[$key_dummy+1][22])."##".val_cero($dummy[$key_dummy+1][23])."##".val_cero($dummy[$key_dummy+1][24])."##".val_cero($dummy[$key_dummy+1][25])."##".val_cero($dummy[$key_dummy+1][26])."##".val_cero($dummy[$key_dummy+1][27]);
                                            $casosMujer = val_cero($dummy[$key_dummy+2][11])."##".val_cero($dummy[$key_dummy+2][12])."##".val_cero($dummy[$key_dummy+2][13])."##".val_cero($dummy[$key_dummy+2][14])."##".val_cero($dummy[$key_dummy+2][15])."##".val_cero($dummy[$key_dummy+2][16])."##".val_cero($dummy[$key_dummy+2][17])."##".val_cero($dummy[$key_dummy+2][18])."##".val_cero($dummy[$key_dummy+2][19])."##".val_cero($dummy[$key_dummy+2][20])."##".val_cero($dummy[$key_dummy+2][21])."##".val_cero($dummy[$key_dummy+2][22])."##".val_cero($dummy[$key_dummy+2][23])."##".val_cero($dummy[$key_dummy+2][24])."##".val_cero($dummy[$key_dummy+2][25])."##".val_cero($dummy[$key_dummy+2][26])."##".val_cero($dummy[$key_dummy+2][27]);
                                            $grupos_especiales = "14#-#".val_cero($dummy[$key_dummy+1][8])."#-#".val_cero($dummy[$key_dummy+2][8])."###"."15#-#".val_cero($dummy[$key_dummy+1][9])."#-#".val_cero($dummy[$key_dummy+2][9])."###"."16#-#".val_cero($dummy[$key_dummy+1][10])."#-#".val_cero($dummy[$key_dummy+2][10]);
                                            $final[$i_d]["casosHombre"]=$casosHombre;
                                            $final[$i_d]["casosMujer"]=$casosMujer;
                                            $final[$i_d]["grupos"]=$grupos_especiales;
                                            $i_d = $i_d + 1;
    //                                casosHombre	109##381##377##351##372##541##527##410##325##319##235##203##166##106##66##50##74##4612
    //                                casosMujer	91##339##331##357##447##441##471##353##241##235##202##188##142##83##71##44##70##4106
    //                                grupos	1#-#100#-#104###2#-#107#-#95###3#-#0#-#69
    //                                idForm	6
                                    }
                            }
                        }
                        
                        return $final;
		}
		
	}
	catch (Exception $E)
	{
		echo $E -> getMessage();
	}
        
  }
        
        
  function val_cero($valor)
    {
            if ($valor == ""){
                $valor = "0";
            }
            return $valor;
    }
                
?>
