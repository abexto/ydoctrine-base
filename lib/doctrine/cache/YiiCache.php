<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace abexto\ydc\base\doctrine\cache;

/**
 * Provides a Cache bridge from Doctrine to Yii
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class YiiCache extends \Doctrine\Common\Cache\CacheProvider
{
    /**
     * @var string  Name of the cache component to use. If set to null, the default cache "cache" is used.
     */
    public $cacheId = null;
    
    /**
     * 
     * @return \yii\caching\Cache
     * @throws \yii\base\InvalidConfigException if Yii Cache is not configured
     */
    protected function getYiiCacheComponent()
    {
        if ($this->cacheId === null) {
            $result = \Yii::$app->getCache();
        } else {
            $result = \Yii::$app->{$this->$cacheId};
        }
        if ($result === null) {
            throw new \yii\base\InvalidConfigException('Yii application cache component is not configured');
        }
        return $result;
    }
    
    protected function doContains($id)
    {
        return $this->getYiiCacheComponent()->exists($id);
    }

    protected function doDelete($id)
    {
        return $this->getYiiCacheComponent()->delete($id);
    }

    protected function doFetch($id)
    {
        return $this->getYiiCacheComponent()->get($id);
    }

    protected function doFlush()
    {
        return $this->getYiiCacheComponent()->flush();
    }

    protected function doSave($id, $data, $lifeTime = 0)
    {
        return $this->getYiiCacheComponent()->set($id, $data, $lifeTime);
    }
    
    protected function doGetStats()
    {
        return null;
    }
    


}
