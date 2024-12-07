<?php
require('routeros_api.class.php');

$API = new RouterosAPI();
$API->debug = false;
$addressToActivity = $_GET['address']; // Получаем адрес для запроса

if ($API->connect('192.168.28.181', 'admin', 'admin')) {
$leases = $API->comm('/ip/dhcp-server/lease/print');
    
    foreach ($leases as $lease) {
        if ($lease['address'] == $addressToActivity) {
            $API->write('/ip/dhcp-server/lease/enable', false);
            $API->write('=.id=' . $lease['.id']);
            $API->read();
            break; // Выход из цикла после выполнения команды для заданного IP-адреса
        }
    }
    
    $API->disconnect();
}

?>