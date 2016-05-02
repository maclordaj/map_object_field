<?php
namespace Drupal\map_object_field\Plugin\Field;

trait TMapOptions {

  public function getMapObjectTypes() {
    return \Drupal::config('map.options')->get('drawing_object_types');
  }

  public function getMapObjectTypesWithLabels() {
    $result = [];
    $drawing_object_types = \Drupal::config('map.options')
      ->get('drawing_object_types');
    foreach ($drawing_object_types as $drawing_object_type) {
      $result[$drawing_object_type] = ucfirst($drawing_object_type);
    }
    return $result;
  }

  public function getMapTypes() {
    return \Drupal::config('map.options')->get('map_types');
  }

  public function getMapTypesWithLabels() {
    $result = [];
    $map_types = \Drupal::config('map.options')->get('map_types');
    foreach ($map_types as $map_type) {
      $result[$map_type] = ucfirst($map_type);
    }
    return $result;
  }
}
