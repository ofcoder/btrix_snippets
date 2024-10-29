<?php
/*
* Приводит телефон к виду 79991117788
*
* @param string $phone - телефон
* @return sring телефон
*
*/
function phone_clear($t){
$t = mb_eregi_replace("[^0-9]", '', $t);
if(strlen($t) > 9){$data = '7'.substr($t, -10);}else{$data = '';}
return $data;
}

/*
* Вывод подробной информации о Магазинах
*
* @param string $phoneShop - телефон магазина
* @return array (массив) данных о Магазине
* [id Системотехника, id id Инженера, id Главного инженера, Адрес магазина, Номер магазина, Наименование магазина, координаты магазина и  др. ]
*
*/
function getFieldsShop($phoneShop, $ID=5)
{

if (\Bitrix\Main\Loader::includeModule('highloadblock')) {
$arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById($ID)->fetch();
$obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
$strEntityDataClass = $obEntity->getDataClass();
$data = $strEntityDataClass::getList(array('filter' => array('%UF_PHONE' => $phoneShop)))->fetchAll();

}
return $data;
}

//в начале объявляем текущий бизнес-процесс
$rootActivity = $this->GetRootActivity();

//достаем переменную из бизнес-процесса в php
$ShopPhone = phone_clear( $rootActivity->GetVariable('ShopPhone') );
//или
$TechnicPhone = '{=Variable:TechnicPhone_printable}';

//получаем данные магазина
$fieldsShopAr = getFieldsShop($ShopPhone );
$fieldsShop = $fieldsShopAr[0];
$technic =  "user_" . $fieldsShop["UF_USER_ID_ST"];
$engeneer = "user_" . $fieldsShop["UF_USER_ID_ENGINEER"];
$depHeadEngeneer =  "user_" . $fieldsShop["UF_USER_ID_DEPUTY_HEAD_ENGINEER"];
$headEngeneer =  "user_" . $fieldsShop["UF_USER_ID_HEAD_ENGINEER"];
$shopNumber =  (int)$fieldsShop["UF_NUMBER"];
$shopAddress =  $fieldsShop["UF_ADDRESS"];
$shopId =  $fieldsShop["ID"];
//$timeRequest = Bitrix\Main\Type\DateTime::createFromTimestamp((int)$rootActivity->GetVariable('TimeCall'))->toString();
$timeRequest = ConvertTimeStamp((int)$rootActivity->GetVariable('TimeCall') + CTimeZone::GetOffset() - 7200, 'FULL');


//присваиваем значение переменной в бизнес-процессе из php
$rootActivity->SetVariable("ShopNumber", $shopNumber);
$rootActivity->SetVariable("ShopAddress", $shopAddress);
$rootActivity->SetVariable("Technic", $technic);
$rootActivity->SetVariable("Engeneer", $engeneer);
$rootActivity->SetVariable("DepHeadEngeneer", $depHeadEngeneer);
$rootActivity->SetVariable("HeadEngeneer", $headEngeneer);
$rootActivity->SetVariable("ShopId", $shopId);
$rootActivity->SetVariable("TimeCall", $timeRequest);

/*
\Bitrix\Main\Diag\Debug::dumpToFile($fieldsShop, 'fieldsShop', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($shopId, 'shopId', 'bp_logs.txt');
*/
unset($fieldsShop, $fieldsShopAr, $ShopPhone, $TechnicPhone );
/*
\Bitrix\Main\Diag\Debug::dumpToFile($shopAddress, 'shopAddress', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($shopNumber, 'shopNumber', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($technic, 'technic', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($engeneer, 'engeneer', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($depHeadEngeneer, 'depHeadEngeneer', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($headEngeneer, 'headEngeneer', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($ShopPhone, 'ShopPhone', 'bp_logs.txt');
\Bitrix\Main\Diag\Debug::dumpToFile($TechnicPhone, 'TechnicPhone', 'bp_logs.txt');
*/