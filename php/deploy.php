<?php

require './vendor/autoload.php';

$options = getopt('c:s:f:t:r:n:f:');

$cluster = $options['c'] ?? getenv('CLUSTER_NAME');
$service = $options['s'] ?? getenv('SERVICE_NAME');
$region = $options['r'] ?? getenv('REGION_NAME');// 'ap-northeast-1';
$namespace = $options['n'] ?? getenv('IMAGE_NAMESPACE');
$families  = $options['f'];
$tag = $options['t'] ?? getenv('TAG_NAME');

//var_dump($options);

foreach (['cluster', 'service', 'region', 'families'] as $name) {
    if ($$name === '' or $$name === false or $$name === []) {
        throw new RuntimeException('you need parameter: '. $name);
    }
}

if (! is_array($families)) {
    $families = [$families];
}

if ($tag !== '' and $tag !== false) {
    $image .= ":$tag";
}

$client = Aws\Ecs\EcsClient::factory([
    'version' => 'latest',
    'region' => $region
]);

$res = $client->describeClusters([
    'clusters' => [$cluster]
]);

//var_dump($res['clusters']);
var_dump($service);

$res2 = $client->describeServices([
    'cluster' => $cluster,
    'services' => [$service]
]);
unset($res2['services'][0]['events']);
//var_dump($res2);

$task_def = $res2['services'][0]['taskDefinition'];

$res3 = $client->describeTaskDefinition(['taskDefinition' => $task_def]);

// アップロードするイメージのリスト作成
$images = [];
foreach ($families as $family) {
    $image = "$namespace/$family".$tag;
    $images[$family] = $image;
}

// タスク定義の変更
foreach ($res3['taskDefinition']['containerDefinitions'] as &$def) {
    $name = $def['name'];
    $image = $images[$name] ?? null;
    if ($image === null) {
        continue;
    }

    $def['image'] = $image;
}

$new_def = $client->registerTaskDefinition($res3['taskDefinition']);

//var_dump($new_def);

$res = $client->updateService([
    'cluster' => $cluster,
    'service' => $service,
    'taskDefinition' => $new_def['taskDefinition']['taskDefinitionArn']
]);

//var_dump($res);
echo "process end successfully!!!\n";
