<?php

namespace Drupal\tester\Plugin\Tester;

use Drupal\Component\Plugin\PluginBase;
use Drupal\tester\Plugin\TesterPluginInterface;

/**
 * Defines routes owned by the Node module.
 *
 * @TesterPlugin(
 *   id = "node",
 * )
 */
class NodeTester extends PluginBase implements TesterPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function urls(array $options) {
    $urls = [];

    // @todo Figure out how to inject this service.
    $storage = \Drupal::entityTypeManager()->getStorage('node_type');
    $node_types = $storage->loadMultiple();
    foreach ($node_types as $type) {
      $urls[] = '/node/add/' . $type->id();
    }

    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodes = $storage->loadMultiple();

    foreach ($nodes as $node) {
      $urls[] = $node->toUrl()->toString();
    }

    if ($options['limit'] > 0 && count($urls) >= $options['limit']) {
      $urls = array_slice($urls, 0, $options['limit']);
    }

    return $urls;
  }

  /**
   * {@inheritdoc}
   */
  public function dependencies() {
    return [
      'modules' => [
        'node',
        'user',
      ],
    ];
  }

}
