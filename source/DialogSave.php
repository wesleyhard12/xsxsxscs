<?php

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

header("Content-Type: application/json");


class DialogSave 
{

    public static $file = '../data/keyword.json';
    public static $currentId = 0;
    public static $keyword  = [];


    /**
     * Recebe os dados  do formulario, cria um novo array e insere o novo valor no json com file_put_contents
     */
    public static function writeFromJson() 
    {   
        if (isset($_REQUEST)) {
           
            $request = json_decode($_POST['data'], true);
            $serialize = self::load();

            if (!empty($serialize)) {
                foreach($serialize as $key => $object) {
                    array_push(self::$keyword, $object);
                    self::$currentId = $serialize[$key]['id'];
                }
            }

            $dataKeyword =  array(
                array(
                    "id" => ++self::$currentId,
                    "keyword" => trim($request['keyword_name']),
                    "text" => trim($request['text_context'])
                )
            );

            if (empty($dataKeyword[0]['keyword'])) {
                file_put_contents(self::$file,  json_encode(self::$keyword, JSON_UNESCAPED_UNICODE),  LOCK_EX);
                return;
            } 

            $keywords = array_merge(self::$keyword, $dataKeyword);
            $data = json_encode($keywords, JSON_UNESCAPED_UNICODE);
            file_put_contents(self::$file, $data,  LOCK_EX);
        }
    }

    /**
     * Ler o arquivo json retorna como um array php
     */

    public static function load() 
    {
        return  file_get_contents(self::$file);
    }

} 
DialogSave::writeFromJson();
