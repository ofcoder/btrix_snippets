<?php
  /*
* Перевод минут в формат часы минуты
*
* @param string $time - минуты
* @param string $
* @return string Часы минуты (12 ч. 52 мин.)
*
*/
  function convertToHoursMins($time, $format = '%02d:%02d')
  {
    if ($time < 1) {
      return 'Не начат отсчет времени в задаче';
    }
    $hours = floor($time / 60);
    $minutes = $time % 60;
    return sprintf($format, $hours, $minutes);
  }

//в начале объявляем текущий бизнес-процесс
  $rootActivity = $this->GetRootActivity();

//достаем переменную из бизнес-процесса в php
  $minute = $rootActivity->GetVariable('WorkTimeInShop');
  
  $workTime= convertToHoursMins($minute , '%02d ч. %02d мин.');

//присваиваем значение переменной в бизнес-процессе из php
  $rootActivity->SetVariable("workTimeInShop", $workTime);
