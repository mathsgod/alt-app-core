<?

use PHPUnit\Framework\TestCase;

use App\Core\User;
use App\Core\UserGroup;

final class UserTest extends TestCase
{

    public function test_login()
    {
        $user = User::Login("raymond", "111111");
        $this->assertEquals("raymond", $user->username);

        $this->expectException(Exception::class);
        $user = User::Login("raymond", "1sasfsdf");
    }

    public function test_()
    {
        $admin = User::_("admin");
        $this->assertInstanceOf(User::class, $admin);

        $not_found = User::_("wre");
        $this->assertNull($not_found);
    }

    public function test_verifyPassword()
    {
        $admin = User::_("admin");
        $this->assertFalse($admin->verifyPassword("abc"));
    }

    public function test_is()
    {
        $admin = User::_("admin");
        $this->assertTrue($admin->is("Administrators"));
        $this->assertFalse($admin->is("Users"));
        $this->assertFalse($admin->is("sdfsdfsdfwe"));

        $this->assertTrue($admin->is(UserGroup::_("Administrators")));
    }

    public function test_UserGroup()
    {

        $admin = User::_("admin");
        $ugs = $admin->UserGroup()->toArray();
        $this->assertEquals("Administrators", (string) $ugs[0]);
    }
}