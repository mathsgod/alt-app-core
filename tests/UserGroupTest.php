<?

use App\Core\User;
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

    public function testHasUser()
    {
        $ug = UserGroup::_("administrators");
        $this->assertTrue($ug->hasUser(User::_("admin")));
    }

    public function test_user_add_remove()
    {
        $ug = UserGroup::_("administrators");
        $raymond = User::_("raymond");

        $ug->addUser($raymond);
        $this->assertTrue($ug->hasUser($raymond));

        $ug->removeUser($raymond);
        $this->assertFalse($ug->hasUser($raymond));
    }
}
