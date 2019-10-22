<?

use PHPUnit\Framework\TestCase;

use App\Core\UserGroup;

final class UserGroupTest extends TestCase
{

    public function test_()
    {
        $admin = UserGroup::_("administrators");
        $this->assertInstanceOf(UserGroup::class, $admin);

        $this->assertEquals("Administrators", $admin->name);

        $not_found = UserGroup::_("wre");
        $this->assertNull($not_found);
    }
}
