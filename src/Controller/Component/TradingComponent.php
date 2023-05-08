<?php

namespace App\Controller\Component;
################################################################################
#
# Companee :   Trading (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use ZipArchive;

class TradingComponent extends Component
{

    public function initialize(array $config)
    {
      parent::initialize($config);
    }

    public function processErrevicodesFile($filename, $verified)
    {
        $filePath = WWW_ROOT.'files'.DS.'errevicodes'.DS.$filename.'.csv';
        $out_indice_completo = WWW_ROOT.'files'.DS.'errevicodes'.DS.'indice_completo.csv';
        $out_indice_errevi = WWW_ROOT.'files'.DS.'errevicodes'.DS.'indice_errevi.csv';
        $out_zip = WWW_ROOT.'files'.DS.'errevicodes'.DS.'indice_codici.zip';

        if(file_exists($filePath)){
            //Array degli errevicode
            $tradingCodes = TableRegistry::get('TradingErrevicodes')->find();
            $errevicodes = [];
            foreach($tradingCodes as $code){
                $errevicodes[] = $code['errevicode'];
            }

            //Contenuto file
            $handle = fopen($filePath, 'r');

            while($data = fgetcsv($handle)){
                $contents[] = $data; 
            } 

            fclose($handle);

            //Verifica errori nel contenuto del file
            if(!$verified){
                $errors = [
                    [
                        "msg" => "Prima riga del file non è una marca",
                        "found" => false
                    ],
                    [
                        "msg" => "Dopo una marca non c'è marca o errevicode",
                        "found" => false,
                        "rows" => []
                    ],
                    [
                        "msg" => "Più di 3 marche di fila",
                        "found" => false,
                        "rows" => []
                    ],
                    [
                        "msg" => "Più di 3 errevicode di fila",
                        "found" => false,
                        "rows" => []
                    ],
                    [
                        "msg" => "Dopo una descrizione non c'è codice originale o errevicode",
                        "found" => false,
                        "rows" => []
                    ],
                    [
                        "msg" => "Più di 7 codici originali di fila",
                        "found" => false,
                        "rows" => []
                    ],
                    [
                        "msg" => "Campo codice originale troppo lungo",
                        "found" => false,
                        "rows" => []
                    ],
                    [
                        "msg" => "Una o più colonne vuote",
                        "found" => false,
                        "rows" => []
                    ],
                    [
                        "msg" => "Dopo un errevicode non c'è descrizione",
                        "found" => false,
                        "rows" => []
                    ],
                ];

                $foundErrors = false;

                $previous = '';
                $count = 0;
                foreach($contents as $key => $row){  
                    if($key == 0){
                        if($row[0][0] != '#'){
                            $errors[0]['found'] = true;
                            $foundErrors = true;
                            if(in_array($row[0][0], $errevicodes, true)){
                                $previous = 'errevicode';
                            }else{
                                $previous = 'codice';
                            }
                        }else{
                            $previous = 'marca';
                            $count++;
                        }
                        
                    }else{
                        switch($previous){
                            case 'marca':
                                if($row[0] == '' || $row[1] == ''){
                                    $errors[7]['found'] = true;
                                    $errors[7]['rows'][] = $key+1;
                                    $foundErrors = true;
                                }
                                if($row[0][0] == '#'){
                                    $previous = 'marca';
                                    $count++;

                                    if($count > 3){
                                        $errors[2]['found'] = true;
                                        $errors[2]['rows'][] = $key+1;
                                        $foundErrors = true;
                                    }
                                }else{ 
                                    if(in_array($row[0], $errevicodes, true)){
                                        $previous = 'errevicode';
                                    }else{
                                        $errors[1]['found'] = true;
                                        $errors[1]['rows'][] = $key+1;
                                        $foundErrors = true;

                                        $previous = 'codice';
                                    }
                                    $count = 1;
                                }
                                break;

                            case 'errevicode': 
                                if($row[0] == '' || $row[1] == ''){
                                    $errors[7]['found'] = true;
                                    $errors[7]['rows'][] = $key+1;
                                    $foundErrors = true;
                                }
                                if(in_array($row[0], $errevicodes, true)){
                                    $previous = 'errevicode';
                                    $count++;

                                    if($count > 3){
                                        $errors[3]['found'] = true;
                                        $errors[3]['rows'][] = $key+1;
                                        $foundErrors = true;
                                    }
                                    $errors[8]['found'] = true;
                                    $errors[8]['rows'][] = $key+1;
                                    $foundErrors = true;
                                }else{
                                    if($row[0][0] == '#'){
                                        $previous = 'marca';

                                        $errors[8]['found'] = true;
                                        $errors[8]['rows'][] = $key+1;
                                        $foundErrors = true;
                                    }else{
                                        $previous = 'descrizione';
                                    }
                                    $count = 1;
                                }
                                break;

                            case 'descrizione':
                                if($row[0] == '' || $row[1] == ''){
                                    $errors[7]['found'] = true;
                                    $errors[7]['rows'][] = $key+1;
                                    $foundErrors = true;
                                }
                                if($row[0][0] == '#'){
                                    $errors[4]['found'] = true;
                                    $errors[4]['rows'][] = $key+1;
                                    $foundErrors = true;

                                    $previous = 'marca';
                                }elseif(in_array($row[0], $errevicodes, true)){
                                    $previous = 'errevicode';
                                }else{
                                    $previous = 'codice';
                                }
                                $count = 1;
                                break;

                            case 'codice':
                                if($row[0] == '' || $row[1] == ''){
                                    $errors[7]['found'] = true;
                                    $errors[7]['rows'][] = $key+1;
                                    $foundErrors = true;
                                }
                                if($row[0][0] == '#'){
                                    $previous = 'marca';
                                    $count = 1;
                                }elseif(in_array($row[0], $errevicodes, true)){
                                    $previous = 'errevicode';
                                    $count = 1;
                                }else{
                                    $count++;
                                    if($count > 7){
                                        $errors[5]['found'] = true;
                                        $errors[5]['rows'][] = $key+1;
                                        $foundErrors = true;
                                    }
                                    if(strlen($row[0]) > 20){
                                        $errors[6]['found'] = true;
                                        $errors[6]['rows'][] = $key+1;
                                        $foundErrors = true;
                                    }
                                }
                                break;
                        }
                    }
                }

                //Messagio con gli errori riscontrati
                if($foundErrors){
                    $stringError = '';
                    foreach($errors as $key => $error){
                        if($error['found']){
                            if($key == 0){
                                $stringError .= $error['msg'] . '<br />';
                            }else{
                                $stringError .= $error['msg'] . ' alle righe ' . implode(',',$error['rows']) . '<br />';
                            }
                        }
                    }
                    die($stringError);
                }
            }

            //Elaborazione file per la creazione dei due output
            $previous = '';
            $output = [];
            $outputErrevi = [];
            $lastMarca = '';
            $lastErrevicode = '';

            foreach($contents as $key => $row){ 
                $row[0]=trim($row[0]);
                $row[1]=trim($row[1]);
                if($key == 0){
                    $previous = 'marca';
                    $output[substr($row[0], 1)] = [];
                    $lastMarca = substr($row[0], 1);
                }else{
                    switch($previous){
                        case 'marca':
                            if($row[0][0] == '#'){
                                $row[0]=strtoupper($row[0]);
                                $previous = 'marca';
                                if(!isset($output[substr($row[0], 1)])){
                                    $output[substr($row[0], 1)] = [];
                                }
                                $lastMarca = substr($row[0], 1);
                            }elseif(in_array($row[0], $errevicodes, true)){
                                $previous = 'errevicode';
                                if(!isset($output[$lastMarca][$row[0]])){
                                    $output[$lastMarca][$row[0]] = [];
                                }
                                if(!isset($outputErrevi[$row[0]])){
                                    $outputErrevi[$row[0]] = [];
                                }
                                $lastErrevicode = $row[0];
                            }
                            break;

                        case 'errevicode':
                            if($row[0][0] == '#'){
                                $row[0]=strtoupper($row[0]);
                                $previous = 'marca';
                                if(!isset($output[substr($row[0], 1)])){
                                    $output[substr($row[0], 1)] = [];
                                }
                                $lastMarca = substr($row[0], 1);
                            }elseif(in_array($row[0], $errevicodes, true)){
                                $previous = 'errevicode';
                                if(!isset($output[$lastMarca][$row[0]])){
                                    $output[$lastMarca][$row[0]] = [];
                                }
                                if(!isset($outputErrevi[$row[0]])){
                                    $outputErrevi[$row[0]] = [];
                                }
                                $lastErrevicode = $row[0];
                            }else{
                                $previous = 'descrizione';
                                $output[$lastMarca][$lastErrevicode]['descrizione'] = $row[0];
                            }
                            break;

                        case 'descrizione':
                            if(in_array($row[0], $errevicodes, true)){
                                $previous = 'errevicode';
                                if(!isset($output[$lastMarca][$row[0]])){
                                    $output[$lastMarca][$row[0]] = [];
                                }
                                if(!isset($outputErrevi[$row[0]])){
                                    $outputErrevi[$row[0]] = [];
                                }
                                $lastErrevicode = $row[0];
                            }else{
                                $previous = 'codice';
                                $output[$lastMarca][$lastErrevicode]['codici'][$row[0]][] = $row[1];
                                $outputErrevi[$lastErrevicode][] = $row[1];
                            }
                            $count = 1;
                            break;

                        case 'codice': 
                            if($row[0][0] == '#'){
                                $row[0]=strtoupper($row[0]);
                                $previous = 'marca';
                                if(!isset($output[substr($row[0], 1)])){
                                    $output[substr($row[0], 1)] = [];
                                }
                                $lastMarca = substr($row[0], 1);
                            }elseif(in_array($row[0], $errevicodes, true)){
                                $previous = 'errevicode';
                                if(!isset($output[$lastMarca][$row[0]])){
                                    $output[$lastMarca][$row[0]] = [];
                                }
                                if(!isset($outputErrevi[$row[0]])){
                                    $outputErrevi[$row[0]] = [];
                                }
                                $lastErrevicode = $row[0];
                            }else{
                                $previous = 'codice';
                                $output[$lastMarca][$lastErrevicode]['codici'][$row[0]][] = $row[1];
                                $outputErrevi[$lastErrevicode][] = $row[1];
                            }
                            break;
                    }
                }
            }

            //indice completo
            $rows = [];
            foreach($output as $marca => $errevicodes){
                foreach($errevicodes as $errevicode => $array){
                    if(isset($array['codici'])){
                        $descrizione = $array['descrizione'];
                        foreach($array['codici'] as $codice => $pagine){
                            sort($pagine);
                            $pagine = array_unique($pagine);
                            $rows[] = [trim($marca), trim($codice), trim($descrizione), trim($errevicode), implode(', ', $pagine)];
                        }
                    }
                }
            }

            usort($rows, function ($a, $b){
                if ($a[0] == $b[0]) {
                    if(strlen($a[1]) == strlen($b[1])){
                        return strcmp($a[1], $b[1]);
                    }
                    return strlen($a[1]) - strlen($b[1]);
                }
                return strcmp($a[0], $b[0]);
            });

            array_unshift($rows, ['Marca', 'Codice originale', 'Descrizione', 'Codice errevi', 'Pagine']);

            $indiceCompleto = fopen($out_indice_completo, 'wb');
            foreach ( $rows as $row ) {
                fputcsv($indiceCompleto, $row);
            }
            fclose($indiceCompleto);

            //indice errevicodes
            $rowsErrevi = [];
            foreach($outputErrevi as $errevi => $pagine){
                sort($pagine);
                $pagine = array_unique($pagine);
                $rowsErrevi[] = [trim($errevi), implode(', ', $pagine)];
            }

            usort($rowsErrevi, function ($a, $b){
                return strcmp($a[0], $b[0]);
            });

            array_unshift($rowsErrevi, ['Codice errevi', 'Pagine']);

            $indiceErrevi = fopen(  $out_indice_errevi, 'wb');
            foreach ( $rowsErrevi as $row ) {
                fputcsv($indiceErrevi, $row);
            }
            fclose($indiceErrevi);

            //zip per il download
            $zip = new ZipArchive();
            $zip->open($out_zip, ZipArchive::CREATE);
            $zip->addFile($out_indice_completo);
            $zip->addFile($out_indice_errevi);
            $zip->close();

            unlink($out_indice_completo);
            unlink($out_indice_errevi);

            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=indice_codici.zip');
            header('Content-Length: ' . filesize('indice_codici.zip'));
            if(readfile($out_zip)){
                unlink($out_zip);
            }

        }else{
            die('Il file indicato non esiste.');
        }
    }
}
