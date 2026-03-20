<?php

namespace SleepingOwl\Tests\Admin\Form\Element;

use Mockery as m;

#[\PHPUnit\Framework\Attributes\CoversMethod(\SleepingOwl\Admin\Form\Element\Password::class, 'getValueFromModel()')]
class PasswordTest extends \TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /**
     * @throws \SleepingOwl\Admin\Exceptions\Form\FormElementException
     */
    public function test_gets_value_from_request()
    {
        $request = $this->app['request'];
        $element = new \SleepingOwl\Admin\Form\Element\Password('password', 'Password');

        $session = m::mock(\Illuminate\Session\Store::class);
        $request->setLaravelSession($session);
        $session->shouldReceive('getOldInput')->andReturn(null);

        $request->offsetSet('password', 'secret');

        $this->assertEquals('secret', $element->getValueFromModel());

        $request->offsetSet('password', null);

        $model = new TestModelForPasswordElement(['password' => 'hashed_password']);
        $element->setModel($model);
        $this->assertEquals(null, $element->getValueFromModel());
    }
}

class TestModelForPasswordElement extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = ['password'];
}
