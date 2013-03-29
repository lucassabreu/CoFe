<?php

$arr1 = include 'module/Admin/data/test.data.php';
$arr2 = array('user'=>array('create' => array('create')));

print_r(array_merge_recursive($arr1, $arr2));

?>
