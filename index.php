<?php
require_once 'workflows.php';
$w      = new Workflows();
$kw     = "{query}";
//$kw     = "array diff";
$funcInfoList   = include_once('function_list.php');
$funcList   = array_keys($funcInfoList);

$funcRes    = array();

$limit  = 100;

// 完全匹配
foreach ($funcList as $funcName) {
    if ($funcName == $kw) {
        $funcRes[$funcName]   = array();
    }
}

// 前缀匹配
foreach ($funcList as $funcName) {
    if (count($funcRes) >= $limit) {
        break;
    }

    if (strpos($funcName, $kw) === 0) {
        $funcRes[$funcName]   = array();
    }
}

// 部分匹配
foreach ($funcList as $funcName) {
    if (count($funcRes) >= $limit) {
        break;
    }
    
    if (strpos($funcName, $kw)) {
        $funcRes[$funcName]   = array();
    }
}

// 拆词匹配
foreach ($funcList as $funcName) {
    if (count($funcRes) >= $limit) {
        break;
    }
    
    $kwList = preg_split("/[\_\-\ ]/", $kw);
    foreach ($kwList as $kwItem) {
        if (strpos($funcName, $kwItem) === false) {
            continue(2);
        }
    }
    $funcRes[$funcName]   = array();
}

// 标题匹配
foreach ($funcInfoList as $funcInfo) {
    if (count($funcRes) >= $limit) {
        break;
    }
    
    if (strpos($funcInfo['title'], $kw)) {
        $funcRes[$funcInfo['name']] = array(
        );
    }
}

foreach((array) $funcRes as $funcName => $funcInfo) {
    $i++;
    $funcInfo   = $funcInfoList[$funcName];
    $title      = trim("{$funcName} - {$funcInfo['title']}");
    $sub        = trim("{$funcInfo['prot']}");
    
    $w->result($i, $funcName, $title, $sub, 'icon.png');
}


if (count($w->results()) == 0) {
  	$w->result('zhoufan.php', $kw, '糟糕…', '没找到, 去php.net搜搜看？', 'icon.png', 'yes');
}

echo $w->toxml();