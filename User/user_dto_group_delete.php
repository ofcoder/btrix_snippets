<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Global $APPLICATION;
function deleteUserDTOTrash1()
{


  //GROUP_ID
  //$userId = 2;
  $groupId = 27;
  $userGroupDTOids = AgentsHelpers::getGroupUsersIds($groupId);

  $userIDS = [];
  $taskIds = [];


  $userFilter = array("LOGIN" => "bx24_%", "ID" => $userGroupDTOids);
  $userSelect = array("ID");

  $userIDS = AgentsHelpers::getUsersIds($userSelect, $userFilter);


  if (CModule::IncludeModule("tasks"))
  {
    foreach ($userIDS as $userId)
    {
      $taskSelect = array('ID');
      /*
       $taskFilter = array(
        'LOGIC' => 'OR',
        array('=ACCOMPLICE' => $userId),
        array('=RESPONSIBLE_ID' => $userId),
      );
      */
      //$taskFilter = array('=RESPONSIBLE_ID' => $userId);
      $taskFilter = array('=ACCOMPLICE' => $userId);
      $resTasks = CTasks::GetList(
        array(),
        $taskFilter,
        $taskSelect,
        array("USER_ID" => 1)
      );
      while ($arTask = $resTasks->GetNext())
      {
        if(!empty($arTask['ID']))
        {
          $taskIds[] = $arTask['ID'];
          Bitrix\Main\Diag\Debug::dumpToFile($arTask['ID'], ' $arTask[ID]:', '/delete_user_dto_trash.txt');
        }
      }

      //$resTasks1 = Bitrix\Tasks\Internals\TaskTable::getList(array("order"=>array("ID"=>"ASC"),"filter"=>array("=RESPONSIBLE_ID" => $userId),"select"=>array("ID")))->fetchAll();

      //$resTasks2 = Bitrix\Tasks\Internals\TaskTable::getList(array("order"=>array("ID"=>"ASC"),"filter"=>array("=ACCOMPLICE" => $userId),"select"=>array("ID")))->fetchAll();

      //Bitrix\Main\Diag\Debug::dumpToFile(count($resTasks1), '$resTasks1:', '/delete_user_dto_trash.txt');
      //Bitrix\Main\Diag\Debug::dumpToFile(count($resTasks2), '$resTasks2:', '/delete_user_dto_trash.txt');

    }
    Bitrix\Main\Diag\Debug::dumpToFile(count($taskIds), 'taskIds:', '/delete_user_dto_trash.txt');
    foreach($taskIds as $ID)
    {
      CTasks::Delete($ID);
    }

    foreach($userIDS as $usId)
    {
      if (CModule::IncludeModule("blog"))
      {
        if(!CBlogUser::Delete($usId))
        {
          echo 'Профайл блога пользователя ['.$usId.'] не удален<br>';
        }
        else
        {
          echo 'Профайл блога пользователя ['.$usId.'] удален<br>';
        }
      }

      if(!CUser::Delete($usId))
      {
        /* if ($ex = $APPLICATION->GetException() && $APPLICATION->GetException())
           echo $ex->GetString();
        */
        echo 'Пользователь с id ['.$usId.'] не удален<br>';
      }
      else
      {
        echo 'Пользователь с id ['.$usId.'] удален<br>';
      }
    }
  }
  echo '<br>taskIds: ' . count($taskIds);
  echo '<br>userIDS: ' . count($userIDS);

  \Bitrix\Main\Diag\Debug::dumpToFile(count($taskIds), 'taskIdsCount:', '/delete_user_dto_trash.txt');
  \Bitrix\Main\Diag\Debug::dumpToFile($taskIds, '$taskIds:', '/delete_user_dto_trash.txt');
  \Bitrix\Main\Diag\Debug::dumpToFile(count($userIDS), '$userIDSCount', '/delete_user_dto_trash.txt');
  \Bitrix\Main\Diag\Debug::dumpToFile($userIDS, '$userIDS', '/delete_user_dto_trash.txt');
  //return 'deleteUserDTOTrash();';
}

deleteUserDTOTrash1();