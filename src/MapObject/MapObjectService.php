<?php
/**
 * @file contains \Drupal\map_object_field\MapObject\MapObjectService.
 */
namespace Drupal\map_object_field\MapObject;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;

class MapObjectService {
  const MAP_OBJECT_CACHE_KEY = 'map_object';
  /**
   * @var \Drupal\map_object_field\MapObject\MapObjectDataMapper $mapObjectDataMapper
   */
  protected $mapObjectDataMapper;
  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface $cache
   */
  protected $cache;

  public function __construct(MapObjectDataMapper $mapObjectDataMapper, CacheBackendInterface $cache) {
    $this->mapObjectDataMapper = $mapObjectDataMapper;
    $this->cache = $cache;
  }

  public function getMapObjectsByFieldData($entity_type, $entity_id, $revision_id, $delta) {
    $cache_key = self::MAP_OBJECT_CACHE_KEY . ":{$entity_type}:{$entity_id}:{$revision_id}:{$delta}";
    if (FALSE === ($map_objects = $this->cache->get($cache_key))) {
      /** @var MapObject $map_objects */
      $map_objects = $this->mapObjectDataMapper->getMapObjectsByFieldData($entity_type, $entity_id, $revision_id, $delta);
      if (!is_null($map_objects)) {
        $this->cache->set(
          $cache_key,
          $map_objects,
          Cache::PERMANENT,
          [
            self::MAP_OBJECT_CACHE_KEY . ":{$entity_type}:{$entity_id}:{$revision_id}",
            "{$entity_type}:{$entity_id}",
          ]
        );
        return $map_objects;
      }
      return NULL;
    }
    return $map_objects->data;
  }

  public function getMapObjectsByFieldDataAsString($entity_type, $entity_id, $revision_id, $delta) {
    $map_object_field_data = $this->getMapObjectsByFieldData($entity_type, $entity_id, $revision_id, $delta);
    //Json::serialize doesn't suit because of JSON_NUMERIC_CHECK
    return json_encode(
      $map_object_field_data,
      JSON_HEX_TAG
      | JSON_HEX_APOS
      | JSON_HEX_AMP
      | JSON_HEX_QUOT
      | JSON_NUMERIC_CHECK
    );
  }

  public function saveMapObjects($entity_type, $entity_id, $revision_id, $delta, $data) {
    $cache_key = self::MAP_OBJECT_CACHE_KEY . ":{$entity_type}:{$entity_id}:{$revision_id}";
    $this->cache->delete($cache_key);
    if (!empty($data) && is_array($data)) {
      //Delete all map objects for field
      foreach ($data as $map_object_data) {
        $map_object_data['entity_type'] = $entity_type;
        $map_object_data['entity_id'] = $entity_id;
        $map_object_data['entity_revision_id'] = $revision_id;
        $map_object_data['entity_field_delta'] = $delta;
        $map_object = $this->createMapObject($map_object_data);
        $this->saveMapObject($map_object);
        $result[] = $map_object;
      }
    }
    else {
      $this->mapObjectDataMapper->deleteMapObject(
        $entity_type,
        $entity_id,
        $revision_id,
        $delta
      );
    }
  }

  /**
   * Saves single map object
   *
   * @param MapObject $map_object
   * @return bool
   * @throws \Exception
   */
  public function saveMapObject(MapObject $map_object) {
    return $this->mapObjectDataMapper->saveMapObject($map_object);
  }

  /**
   * @param array $data
   *
   * @return \Drupal\map_object_field\MapObject\MapObject
   */
  public function createMapObject(array $data) {
    return new MapObject($data);
  }

  public function deleteAllMapObjectsForEntity($entity_type, $entity_id, $revision_id = NULL, $delta = NULL) {
    $this->mapObjectDataMapper->deleteMapObject($entity_type, $entity_id, $revision_id, $delta);
    $cache_tag = self::MAP_OBJECT_CACHE_KEY . ":{$entity_type}:{$entity_id}:{$revision_id}";
    $this->cache->delete($cache_tag);
  }
}
