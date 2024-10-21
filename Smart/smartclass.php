<?php
ini_set('post_max_size', '1100M');       // Максимальный размер данных
ini_set('upload_max_filesize', '100M'); // Максимальный размер файлов
ini_set('max_input_vars', '10000');      // Максимальное количество переменных
ini_set('max_execution_time', '6000');   // Максимальное время выполнения скрипта
ini_set('max_input_time', '6000');       // Максимальное время обработки данных
ini_set('memory_limit', '2000M');        // Память для скрипта


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

class SmartProcess
{
  public static function getFactory($smartTypeId)
  {
    \Bitrix\Main\Loader::includeModule('crm');
    return Bitrix\Crm\Service\Container::getInstance()->getFactory($smartTypeId);
  }
  public static function getItems($smartTypeId,$select, $filter)
  {
    $factory = self::getFactory($smartTypeId);
    $elements = $factory->getItems(array(
      'filter' => $filter,
      'select' => $select
    ));
    return $elements;
  }
  public static function deleteItem($smartTypeId, $id)
  {
    $context = new \Bitrix\Crm\Service\Context();
    $factory = self::getFactory($smartTypeId);
    $item = $factory->getItem($id);
    $saveOperation = $factory->getDeleteOperation($item, $context);
    $operationResult = $saveOperation->launch();
    return $operationResult;
  }

}
$smartId = 182;
$requestDTO = SmartProcess::getItems($smartId,['ID'], ['<CREATED_TIME' => date('27.08.2024')]);
$ids = [];
foreach ($requestDTO as $element)
{
  //echo $element->getId() . "<br>";
  $ids[] = $element->getId();
}

foreach ($ids as $id){
  SmartProcess::deleteItem($smartId, $id);
}

echo count($ids);
echo count($requestDTO);
