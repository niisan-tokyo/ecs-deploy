<?php

require './vendor/autoload.php';

$options = getopt('c:s:i:f:t:r::');

$cluster = $options['c'] ?? getenv('CLUSTER_NAME');
$service = $options['s'] ?? getenv('SERVICE_NAME');
$region = $options['r'] ?? getenv('REGION_NAME');// 'ap-northeast-1';
$image  = $options['i'] ?? getenv('IMAGE_NAME');
$tag = $options['t'] ?? getenv('TAG_NAME');

foreach (['cluster', 'service', 'region', 'image'] as $name) {
    if ($$name === '' or $$name === false) {
        throw new RuntimeException('you need parameter: '. $name);
    }
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

$res2 = $client->describeServices([
    'cluster' => $cluster,
    'services' => [$service]
]);
unset($res2['services'][0]['events']);
//var_dump($res2);

$task_def = $res2['services'][0]['taskDefinition'];

$res3 = $client->describeTaskDefinition(['taskDefinition' => $task_def]);

//var_dump($res3);

$res3['taskDefinition']['containerDefinitions'][0]['image'] = $image;

$new_def = $client->registerTaskDefinition($res3['taskDefinition']);

//var_dump($new_def);

$res = $client->updateService([
    'cluster' => $cluster,
    'service' => $service,
    'taskDefinition' => $new_def['taskDefinition']['taskDefinitionArn']
]);

//var_dump($res);
echo "process end successfully!!!";
