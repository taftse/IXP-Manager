<?php

namespace Proxies\__CG__\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class SecEvent extends \Entities\SecEvent implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function setType($type)
    {
        $this->__load();
        return parent::setType($type);
    }

    public function getType()
    {
        $this->__load();
        return parent::getType();
    }

    public function setMessage($message)
    {
        $this->__load();
        return parent::setMessage($message);
    }

    public function getMessage()
    {
        $this->__load();
        return parent::getMessage();
    }

    public function setRecordedDate($recordedDate)
    {
        $this->__load();
        return parent::setRecordedDate($recordedDate);
    }

    public function getRecordedDate()
    {
        $this->__load();
        return parent::getRecordedDate();
    }

    public function setTimestamp($timestamp)
    {
        $this->__load();
        return parent::setTimestamp($timestamp);
    }

    public function getTimestamp()
    {
        $this->__load();
        return parent::getTimestamp();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setCustomer(\Entities\Customer $customer = NULL)
    {
        $this->__load();
        return parent::setCustomer($customer);
    }

    public function getCustomer()
    {
        $this->__load();
        return parent::getCustomer();
    }

    public function setSwitch(\Entities\Switcher $switch = NULL)
    {
        $this->__load();
        return parent::setSwitch($switch);
    }

    public function getSwitch()
    {
        $this->__load();
        return parent::getSwitch();
    }

    public function setSwitchPort(\Entities\SwitchPort $switchPort = NULL)
    {
        $this->__load();
        return parent::setSwitchPort($switchPort);
    }

    public function getSwitchPort()
    {
        $this->__load();
        return parent::getSwitchPort();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'type', 'message', 'recorded_date', 'timestamp', 'id', 'Customer', 'Switch', 'SwitchPort');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}