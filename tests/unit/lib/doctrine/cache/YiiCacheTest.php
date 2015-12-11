<?php

/*
 * Copyright 2015 Andreas Prucha, Abexto - Helicon Software Development.
 */

namespace abexto\ydc\base\tests\unit\lib\doctrine\cache;

/**
 * Description of YiiCache
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class YiiCacheTest extends \Doctrine\Tests\Common\Cache\CacheTest
{
    
    use \abexto\yepa\phpunit\MockApplicationTrait;
    
    /**
     *
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    protected $doctrineCache = null;
    
    protected function setUp()
    {
        parent::setUp();
        if (!\Yii::$app) {
            $this->mockWebApplication([
                'components' => [
                    'cache' => [
                        'class' => \yii\caching\ArrayCache::className()
                    ]
                ]
            ]);
        }
    }
    
    public function testGetStats()
    {
        $cache = $this->_getCacheDriver();
        $this->assertNull($cache->getStats(), 'Driver does not support getStats(), thus should return null');
    }

    protected function _getCacheDriver()
    {
        return new \abexto\ydc\base\doctrine\cache\YiiCache();
    }

}
