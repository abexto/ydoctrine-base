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
class YiiCacheTest extends \abexto\yepa\phpunit\TestCase
{
    /**
     *
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    protected $doctrineCache = null;
    
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication([
            'components' => [
                'cache' => [
                    'class' => \yii\caching\ArrayCache::className()
                ]
            ]
        ]);
        $this->doctrineCache = new \abexto\ydc\base\doctrine\cache\YiiCache();
    }
    
    public function testSave()
    {
        $this->doctrineCache->save('test', 'testdata');
    }
}
