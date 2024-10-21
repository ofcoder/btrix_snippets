<?php
  /*
   * ТП Битрикс
  На будущее предоставлю вам такую рекомендацию об использовании rest-приложений на боевом и тестовом портале, которые используют один лицензионный ключ.

Чтобы корректно работали приложения из Маркета, рекомендуется на тестовом портале выполнить перерегистрацию rest и переустановку приложений. Для перерегистрации rest в командной php-строке выполните следующий код:
  */
\Bitrix\Main\Loader::includeModule('rest');
$oldClientId = \Bitrix\Main\Config\Option::get('rest', 'service_client_id', null);
$oldClientSecret = \Bitrix\Main\Config\Option::get('rest', 'service_client_secret', null);
print_r('old service_client_id: "'.$oldClientId.'"');
print_r('old service_client_secret: "'.$oldClientId.'"');

\Bitrix\Main\Config\Option::delete('rest', ['name' => 'service_client_id']);
\Bitrix\Main\Config\Option::delete('rest', ['name' => 'service_client_secret']);

try
{
  \Bitrix\Rest\OAuthService::register();
  \Bitrix\Rest\OAuthService::getEngine()->getClient()->getApplicationList();
}
catch(\Bitrix\Main\SystemException $e)
{
  echo 'error register portal';
  echo "\n <br> \n";
}
$newClientId = \Bitrix\Main\Config\Option::get('rest', 'service_client_id', null);
$newClientSecret = \Bitrix\Main\Config\Option::get('rest', 'service_client_secret', null);
if (
  !is_null($oldClientId)
  && !is_null($oldClientSecret)
  && is_null($newClientId)
  && is_null($newClientSecret)
)
{
  \Bitrix\Main\Config\Option::set('rest', 'service_client_id', $oldClientId);
  \Bitrix\Main\Config\Option::get('rest', 'service_client_secret', $oldClientSecret);
}