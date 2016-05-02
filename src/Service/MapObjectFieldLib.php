<?php
/**
 * @file Contains Drupal\map_object_field\Service\MapObjectFieldLib.
 */
namespace Drupal\map_object_field\Service;

/**
 * Class MapObjectFieldLib
 *
 * @package Drupal\map_object_field\Service
 */
class MapObjectFieldLib implements IMapObjectLib {
  public function getLibrariesForWidget() {
    return [
      'map_object_field/map-object-field-default-widget.' . $this->getLibGroup(),
    ];
  }

  public function getLibrariesForWidgetConfig() {
    return [
      'map_object_field/map-object-field-default-widget-config.' . $this->getLibGroup(),
    ];
  }

  public function getLibrariesForFormatter() {
    return [
      'map_object_field/map-object-field-default-formatter.' . $this->getLibGroup(),
    ];
  }

  /**
   * We're going to add more map providers,
   * and allow admin to choose which to use.
   *
   * @return string
   */
  protected function getLibGroup() {
    return 'google';
  }
}
