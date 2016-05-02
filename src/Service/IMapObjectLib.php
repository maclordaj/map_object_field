<?php
/**
 * @file Contains \Drupal\map_object_field\Service\IMapObjectLib.
 */
namespace Drupal\map_object_field\Service;

interface IMapObjectLib {
  public function getLibrariesForWidget();
  public function getLibrariesForWidgetConfig();

  public function getLibrariesForFormatter();
}
