<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace abexto\ydc\base\common;

/**
 * Abstract Base Class for yDoctrine Components
 * 
 * Component class which implements hierarchical components and is usually used to implement
 * configuration wrappers for Doctrine Classes
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
abstract class AbstractDoctrineComponent extends \yii\base\Component
{

    /**
     * @var AbstractDoctrineComponent  Parent object. Do not set this value in configuration. 
     */
    public $parent = null;
    
    public function __construct($config = array())
    {
        parent::__construct($config === true ? [] : $config);
    }    

    public static function create(AbstractDoctrineComponent $parentObject, $properties = [], $className = null)
    {
        if (!array_key_exists('class', $properties)) {
            if (!$className) {
                $className = static::className();
            }
        } else {
            $className = $properties['class'];
            unset($properties['class']);
        }
        $properties['parent'] = $parentObject;
        return new $className($properties);
    }

    /**
     * Returns the Root Component
     * 
     * @return \abexto\ydc\base\common\AbstractDoctrineComponent
     */
    public function getRootComponent()
    {
        if (!$this->parent instanceof AbstractDoctrineComponent) {
            return $this; // I am the root component ==> RETURN
        } else {
            return ($this->parent->getRootComponent()); // Ask the Parent and ==> RETURN
        }
        return NULL;
    }

}
