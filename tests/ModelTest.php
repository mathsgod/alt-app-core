<?php

use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
{

    public function test_created_time()
    {
        $testing = new Testing();
        $testing->save();
        $this->assertNotEmpty($testing->created_time);
        $this->assertEmpty($testing->updated_time);


        $testing->save();
        $this->assertNotEmpty($testing->updated_time);
    }
}
