<?php

namespace Drupal\todo_checklist;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a todochecklist entity type.
 */
interface TodochecklistInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
