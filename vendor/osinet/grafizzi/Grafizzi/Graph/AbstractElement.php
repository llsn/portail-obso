<?php

/**
 * @file
 * Grafizzi\Graph\AbstractElement: a component of the Grafizzi library.
 *
 * (c) 2012 Frédéric G. MARAND <fgm@osinet.fr>
 *
 * Grafizzi is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
 * <http://www.gnu.org/licenses/>
 */

namespace Grafizzi\Graph;

/**
 * Status: working, some possibly useless code. Handle error situations better.
 */
abstract class AbstractElement extends AbstractNamed implements ElementInterface {
  const DEPTH_INDENT = 2;

  /**
   * @var \Grafizzi\Graph\AttributeInterface[]
   */
  public $fAttributes = array();

  /**
   * @var \Grafizzi\Graph\AbstractElement[] $fChildren
   */
  public $fChildren = array();

  /**
   * The nesting level of the element.
   *
   * An unbound element, like the root graph, has depth 0.
   *
   * @var int
   */
  public $fDepth = 0;

  /**
   * The parent element, when bound, or null otherwise.
   *
   * @var ElementInterface
   */
  public $fParent = null;

  /**
   * Possibly not needed with an efficient garbage collector, but might help in
   * case of dependency loops.
   *
   * XXX 20120512 check if really useful.
   */
  public function __destruct() {
    try {
      $name = $this->getName();
    }
    catch (AttributeNameException $e) {
      $name = 'unnamed';
    }
    $type = $this->getType();
    $this->logger->debug("Destroying $type $name");
    foreach ($this->fAttributes as &$attribute) {
      unset($attribute);
    }
    foreach ($this->fChildren as &$child) {
      unset($child);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addChild(ElementInterface $child) {
    $name = $this->getName();
    $childName = $child->getName();
    $childType = $child->getType();
    $this->logger->debug("Adding child $childName, type $childType, to $name, depth {$this->fDepth}.");
    if (!in_array($childType, $this->getAllowedChildTypes())) {
      $message = "Invalid child type $childType for element $name.";
      $this->logger->error($message);
      throw new ChildTypeException($message);
    }
    $this->fChildren[$childName] = $child;
    $child->adjustDepth($this->fDepth + 1);
    $child->setParent($this);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function adjustDepth($extra = 0) {
    $this->logger->debug("Adjusting depth {$this->fDepth} by $extra.");
    $this->fDepth += $extra;
    /** @var AbstractElement $child */
    foreach ($this->fChildren as $child) {
      $child->adjustDepth($extra);
    }
    return $this->fDepth;
  }

  /**
   * Build the DOT string for this subtree.
   *
   * Ignores $directed.
   *
   * @see NamedInterface::build()
   *
   * @param bool $directed
   *
   * @return string
   */
  public function build($directed = null) {
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building element $name.");
    $attributes = array_map(function (AttributeInterface $attribute) use ($directed) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    $name = $this->escape($name);
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . $this->buildAttributes($attributes, $type, $name)
      . ";\n";
    return $ret;
  }

  /**
   * @param array $attributes
   * @param string $type
   * @param string $name
   *
   * @return string
   */
  protected function buildAttributes($attributes, $type, $name) {
    $ret = '';
    if (!empty($attributes)) {
      $builtAttributes = implode(', ', array_filter($attributes));
      if (!empty($builtAttributes)) {
        $prefix = '';
        if ($type) {
          $prefix .= "$type ";
        }
        if ($name) {
          $prefix .= "$name ";
        }
        if (empty($prefix)) {
          $prefix = ' ';
        }

        $ret .= "{$prefix}[ $builtAttributes ]";
      }
    }

    $ret .= ";\n";
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public static function getAllowedChildTypes() {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeByName($name) {
    $ret = isset($this->fAttributes[$name]) ? $this->fAttributes[$name] : null;
    $this->logger->debug("Getting attribute [$name]: " . print_r($ret, true) . ".");
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public function getChildByName($name) {
    $ret = isset($this->fChildren[$name])
      ? $this->fChildren[$name]
      : null;
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public function getParent() {
    return $this->fParent;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoot() {
    $current = $this;
    // Beware of priorities: do not remove parentheses.
    while (($parent = $current->getParent()) instanceof ElementInterface) {
      $current = $parent;
    }
    return $current;
  }

  /**
   * {@inheritdoc}
   */
  public function removeAttribute(AttributeInterface $attribute) {
    $name = $attribute->getName();
    if (!isset($name)) {
      $message = 'Trying to remove unnamed attribute.';
      $this->logger->warning($message);
      throw new AttributeNameException($message);
    }
    $this->removeAttributeByName($name);
  }

  /**
   * {@inheritdoc}
   */
  public function removeAttributeByName($name) {
    $this->logger->debug("Removing attribute [$name].");
    unset($this->fAttributes[$name]);
  }

  /**
   * {@inheritdoc}
   */
  public function removeChild(ElementInterface $child) {
    $name = $child->getName();
    if (!isset($name)) {
      $message = 'Trying to remove unnamed child.';
      $this->logger->warning($message);
      throw new ChildNameException($message);
    }
    $ret = $this->removeChildByName($name);
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public function removeChildByName($name) {
    $this->logger->debug("Removing child [$name].");
    if (isset($this->fChildren[$name])) {
      $child = $this->fChildren[$name];
      $child->adjustDepth(- $this->fDepth - 1);
      unset($this->fChildren[$name]);
      $ret = $child;
    }
    else {
      $ret = null;
    }
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public function setAttribute(AttributeInterface $attribute) {
      $name = $attribute->getName();
      if (!isset($name)) {
        $message = 'Trying to set unnamed attribute.';
        $this->logger->warning($message, debug_backtrace(false));
        throw new ChildNameException($message);
      }
    $this->fAttributes[$name] = $attribute;
  }

  /**
   * {@inheritdoc}
   */
  public function setAttributes(array $attributes) {
    foreach ($attributes as $attribute) {
      if (!in_array('Grafizzi\\Graph\\AttributeInterface', class_implements($attribute))) {
        $message = 'Trying to set non-attribute as an attribute';
        $this->logger->warning($message);
        throw new AttributeNameException($message);
      }
      $this->setAttribute($attribute);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setParent(ElementInterface $parent) {
    $this->fParent = $parent;
  }

}
