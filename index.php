<?php 

//Подключаем файлы с заданиями и инициализируем классы
require 'FirstTask/task.php';
require 'SecondTask/task2.php';
$firstTask = new FirstTask;
$secondTask = new SecondTask;

//Первое задание
$arrayProducts = $firstTask->generateArrayProducts(); /* Парсим сайт и генерируем массив всех товаров и подкатегорий*/
$firstTask->writeFile($arrayProducts); /* Генерируем JSON из массива и записываем в файл data.json */

//Второе задание
$averagePrice = $secondTask->getAveragePrice(); /* Считываем файл data.json и считаем среднюю стоимость (подкатегория "Входные двери Аргус") */
$array = $secondTask->generateAssocArray($averagePrice); /* Генерируем ассоциативный массив с датами и ценами */
$secondTask->showResult($array); /* Рисуем график */

?>