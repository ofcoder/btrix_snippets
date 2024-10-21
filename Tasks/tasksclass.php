<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

class TasksClass
{
  public static function getTasks($select,$filter)
  {
    if (!CModule::IncludeModule("tasks"))
      return false;
      /*
       $taskFilter = array(
        'LOGIC' => 'OR',
        array('=ACCOMPLICE' => $userId),
        array('=RESPONSIBLE_ID' => $userId),
      );
      */
    $taskSelect = $select;
    $taskFilter = $filter;
    $resTasks = CTasks::GetList(
        array(),
        $taskFilter,
        $taskSelect,
        array("USER_ID" => 1)
    );
    $tasks = array();
    while ($arTask = $resTasks->GetNext())
    {
          $tasks[] = $arTask;
    }
      return $tasks;
  }
}


