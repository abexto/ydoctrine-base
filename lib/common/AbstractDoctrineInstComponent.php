<?php

/*
 * Copyright (c) 2015, Andreas Prucha, Abexto - Helicon Software Development
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * *  Redistributions of source code must retain the above copyright notice, this 
 *    list of conditions and the following disclaimer.
 * *  Redistributions in binary form must reproduce the above copyright notice, 
 *    this list of conditions and the following disclaimer in the documentation 
 *    and/or other materials provided with the distribution.
 * *  Neither the name of Abexto, Helicon Software Development, Andreas Prucha
 *    nor the names of its contributors may 
 *    be used to endorse or promote products derived from this software without 
 *    specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace abexto\ydc\base\common;

/**
 * Abstract configuration of a Doctrine property which reflects an instance of a Doctrine object
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 * 
 * @property-read \Doctrine\DBAL\Logging\SQLLogger $inst    Associated Doctrine object.
 */
abstract class AbstractDoctrineInstComponent extends AbstractDoctrineComponent
{

    const EVENT_BEFORE_NEW_DOCTRINE_INST = 'beforeNewDoctrineInst';
    const EVENT_BEFORE_CONFIGURE_DOCTRINE_INST = 'beforeConfigureDoctrineInst';
    const EVENT_AFTER_CONFIGURE_DOCTRINE_INST = 'beforeConfigureDoctrineInst';

    /**
     * @var object  Instance of the Doctrine object associated with this configuration
     */
    private $_inst = null;

    /**
     * Internal helper function for mass-assignment of values to a doctrie object
     * 
     * If the function declares a method named set
     */
    protected function assignValuesToInst($oi, array $values = [], $checkIfDeclared = true)
    {
        $reflection = new \ReflectionObject($oi);
        foreach ($values as $p => $v) {
            $setter = 'set' . ucfirst(($p));
            if ($reflection->hasMethod($setter)) {
                $oi->$setter($v);
            } elseif ($reflection->hasProperty($p) || !$checkIfDeclared) {
                $oi->$p = $v;
            } else {
                throw new \yii\base\InvalidConfigException('Doctrine object of class ' . get_class($oi) . ' does not declare ' . $setter . ' or $' . $p);
            }
        }
    }

    /**
     * Creates the Doctrine object associated with this configuration
     * @return object
     */
    abstract protected function newInst();

    /**
     * Performed after newInst
     * 
     * Override this method in order to perform the configuration of the associated Doctrine object.
     * 
     * @param object $inst
     */
    protected function configureInst($inst)
    {
        
    }

    /**
     * Returns the assoicated Doctrine object
     * 
     * This function performs the following steps:
     * 
     * 1.  Triggers the beforeNewDoctrineInst event. If the handler sets the property {@link \yii\base\Event::$handled}
     *     to true, the object returned in {@link NewDoctrineInstEvent::$inst} is used as object instance, otherwise
     *     {@link AbstractDoctrineInstComponent::newInst()} is called. If the Handler sets 
     *     {@link InitDoctrineInstEvent::$configurationDone} to true, no further steps are performed.
     *     and no further steps are performed
     * 2.  Triggers the beforeConfigureDoctrineInst event. If the handler sets {@link \yii\base\Event::$handled} to true,
     *     the no further steps are performed.
     * 3.  Calls {@link configureInst()}
     * 4.  Triggers the event afterConfigureDoctrineInst;
     * 
     * @return object|null  Associated Doctrine object
     */
    public function getInst()
    {
        if (!$this->_inst) {
            $event = new InitDoctrineInstEvent();
            $this->trigger(self::EVENT_BEFORE_NEW_DOCTRINE_INST, $event);
            if (!$event->handled) {
                $this->_inst = $this->newInst();
            } else {
                $this->_inst = $event->inst;
            }
            if (!$event->configurationDone) {
                $event->handled = false;
                $event->inst = $this->_inst;
                $this->trigger(self::EVENT_BEFORE_CONFIGURE_DOCTRINE_INST, $event);
                if (!$event->handled) {
                    $this->configureInst($this->_inst);
                    $this->trigger(self::EVENT_AFTER_CONFIGURE_DOCTRINE_INST, $event);
                }
            }
        }
        return $this->_inst;
    }

    /**
     * Sets the associated Doctrine Object.
     * 
     * Note: Usually there is no need to call this function as the object is created automatically
     * 
     * @param object|null $value
     */
    public function setInst($value)
    {
        $this->_inst = $value;
    }

}
