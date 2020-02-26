<?

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

    public function test_bind()
    {

        $testing = new Testing();
        $testing->bind([
            "name" => "testing"
        ]);

        $this->assertEquals("testing", $testing->name);
    }

    public function test_bind2()
    {

        $testing = new Testing();
        $testing->bind([
            "name" => ["testing"]
        ]);
        $this->assertEquals("testing", $testing->name[0]);
    }
}
