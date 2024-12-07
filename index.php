<?php

ini_set('max_execution_time', 60);

require('routeros_api.class.php');

$routerIp = '192.168.28.181'; // IP-адрес Mikrotik
$routerUser = 'admin'; // Имя пользователя для доступа к устройству
$routerPass = 'admin'; // Пароль для доступа к устройству

$API = new RouterosAPI();
$API->debug = false;

if ($API->connect($routerIp, $routerUser, $routerPass)) {
    $leases = $API->comm("/ip/dhcp-server/lease/print");

    if ($leases) {
        echo "Список адресов в DHCP server-leases:<br>";
        foreach ($leases as $lease) {
            echo $lease['address'] . " - " . $lease['comment'] . " <button onclick=\"disconnectAddress('{$lease['address']}')\">Деактивировать</button> <button onclick=\"activateAddress('{$lease['address']}')\">Активировать</button><br>";
        }
    } else {
        echo "Не удалось получить данные из DHCP server-leases\n";
    }

    $API->disconnect();
} else {
    echo "Не удалось подключиться к устройству Mikrotik\n";
}
?>

<script>
function disconnectAddress(address) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'disconnect_address.php?address=' + address, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            console.log('Адрес ' + address + ' был отключен');
        }
    };

    xhr.send();
}

function activateAddress(address) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'activate_address.php?address=' + address, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            console.log('Адрес ' + address + ' был активирован');
        }
    };

    xhr.send();
}
</script>