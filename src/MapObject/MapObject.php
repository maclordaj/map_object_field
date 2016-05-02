<?php
/**
 * @file contains \Drupal\map_object_field\MapObject\MapObject.
 */
namespace Drupal\map_object_field\MapObject;

use Drupal\Core\Database\Database;
use JsonSerializable;

class MapObject implements JsonSerializable {
  protected $id = '';
  protected $entityType = '';
  protected $entityId = '';
  protected $entityRevisionId = '';
  protected $entityFieldDelta = 0;
  protected $objectType = '';
  protected $objectCoordinates = [];
  protected $extraParams = [];
  protected $map = [
    'map_object_id' => 'id',
    'type' => 'objectType',
    'entity_type' => 'entityType',
    'entity_id' => 'entityId',
    'entity_revision_id' => 'entityRevisionId',
    'entity_field_delta' => 'entityFieldDelta',
    'coordinates' => 'objectCoordinates',
    'extraParams' => 'extraParams',
  ];

  public function __construct($data) {
    foreach ($data as $key => $val) {
      if (array_key_exists($key, $this->map)) {
        $set_method = 'set' . ucfirst($this->map[$key]);
        $this->$set_method($val);
      }
    }
  }


  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getEntityType() {
    return $this->entityType;
  }

  /**
   * @param mixed $entityType
   */
  public function setEntityType($entityType) {
    $this->entityType = $entityType;
  }

  /**
   * @return mixed
   */
  public function getEntityId() {
    return $this->entityId;
  }

  /**
   * @param mixed $entityId
   */
  public function setEntityId($entityId) {
    $this->entityId = $entityId;
  }

  /**
   * @return mixed
   */
  public function getEntityRevisionId() {
    return $this->entityRevisionId;
  }

  /**
   * @param mixed $entityRevisionId
   */
  public function setEntityRevisionId($entityRevisionId) {
    $this->entityRevisionId = $entityRevisionId;
  }

  /**
   * @return mixed
   */
  public function getEntityFieldDelta() {
    return $this->entityFieldDelta;
  }

  /**
   * @param mixed $entityFieldDelta
   */
  public function setEntityFieldDelta($entityFieldDelta) {
    $this->entityFieldDelta = $entityFieldDelta;
  }

  /**
   * @return mixed
   */
  public function getObjectType() {
    return $this->objectType;
  }

  /**
   * @param mixed $objectType
   */
  public function setObjectType($objectType) {
    $this->objectType = $objectType;
  }

  /**
   * @return array
   */
  public function getObjectCoordinates() {
    return $this->objectCoordinates;
  }

  /**
   * @param array $objectCoordinates
   */
  public function setObjectCoordinates($objectCoordinates) {
    $this->objectCoordinates = $objectCoordinates;
  }

  public function getExtraParam($key) {
    if (array_key_exists($key, $this->extraParams)) {
      return $this->extraParams[$key];
    }
    return NULL;
  }

  public function setExtraParam($key, $val) {
    $this->extraParams[$key] = $val;
  }

  public function getExtraParams() {
    return $this->extraParams;
  }

  public function setExtraParams($val) {
    $this->extraParams = $val;
  }

  public function jsonSerialize() {
    $inverted_map = array_flip($this->map);
    $result = [];
    foreach (get_object_vars($this) as $property_name => $property_value) {
      if (isset($inverted_map[$property_name])) {
        $result[$inverted_map[$property_name]] = $property_value;
      }
    }
    return $result;
  }
}
