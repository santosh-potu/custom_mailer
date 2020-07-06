<?php

function getMailData($template){
    $xml = simplexml_load_file($template,"SimpleXMLElement",LIBXML_NOCDATA);
    return $xml;
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

