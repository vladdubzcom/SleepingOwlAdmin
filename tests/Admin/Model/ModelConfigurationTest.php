<?php

use Mockery as m;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

class ModelConfigurationTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /**
     * @param  string  $class
     * @return \PHPUnit\Framework\MockObject\MockObject
     *
     * @throws ReflectionException
     */
    protected function getConfiguration($class = ModelConfigurationTestModel::class)
    {
        return new class($this->app, $class) extends ModelConfiguration {};
    }

    public function test_gets_and_sets_alias()
    {
        $model = $this->getConfiguration();

        $this->assertEquals('model_configuration_test_models', $model->getAlias());
        $this->assertEquals($model, $model->setAlias('test'));
        $this->assertEquals('test', $model->getAlias());
    }

    public function test_gets_and_sets_title()
    {
        $model = $this->getConfiguration();

        $this->assertEquals('Model Configuration Test Models', $model->getTitle());
        $this->assertEquals($model, $model->setTitle('test'));
        $this->assertEquals('test', $model->getTitle());
    }

    public function test_gets_and_sets_create_title()
    {
        $model = $this->getConfiguration();

        $this->getTranslatorMock()
            ->shouldReceive(version_compare(\Illuminate\Support\Facades\App::version(), '6.0', '>=') ? 'get' : 'trans')
            ->once()
            ->with('sleeping_owl::lang.model.create', ['title' => $model->getTitle()], null)
            ->andReturn('string');
        $this->assertEquals('string', $model->getCreateTitle());

        $this->assertEquals($model, $model->setCreateTitle('test'));
        $this->assertEquals('test', $model->getCreateTitle());
    }

    public function test_gets_and_sets_edit_title()
    {
        $model = $this->getConfiguration();

        $this->getTranslatorMock()
            ->shouldReceive(version_compare(\Illuminate\Support\Facades\App::version(), '6.0', '>=') ? 'get' : 'trans')
            ->once()
            ->with('sleeping_owl::lang.model.edit', ['title' => $model->getTitle()], null)
            ->andReturn('string');
        $this->assertEquals('string', $model->getEditTitle($model->getModel()));

        $this->assertEquals($model, $model->setEditTitle('test'));
        $this->assertEquals('test', $model->getEditTitle($model->getModel()));
    }

    public function test_display()
    {
        $model = $this->getConfiguration();

        $display = m::mock(DisplayInterface::class);
        $display->shouldReceive('setModelClass')->once()->with($model->getClass());
        $display->shouldReceive('initialize')->once();

        $this->assertEquals($model, $model->onDisplay(function () use ($display) {
            return $display;
        }));

        $this->assertEquals($display, $model->fireDisplay());

        // -------------------

        $display = m::mock(ModelConfigurationTestModel::class);
        $display->shouldNotReceive('setModelClass');
        $display->shouldNotReceive('initialize');

        $model->onDisplay(function () use ($display) {
            return $display;
        });

        $this->assertEquals($display, $model->fireDisplay());

        // -------------------

        $display = m::mock(\SleepingOwl\Admin\Contracts\Initializable::class);
        $display->shouldNotReceive('setModelClass');
        $display->shouldReceive('initialize');

        $model->onDisplay(function () use ($display) {
            return $display;
        });

        $this->assertEquals($display, $model->fireDisplay());
    }

    public function test_create()
    {
        $model = $this->getConfiguration();

        $display = m::mock(DisplayInterface::class);
        $display->shouldReceive('setModelClass')->once()->with($model->getClass());
        $display->shouldReceive('initialize')->once();

        $this->assertEquals($model, $model->onCreate(function () use ($display) {
            return $display;
        }));

        $this->assertEquals($display, $model->fireCreate());

        // -------------------

        $display = m::mock(ModelConfigurationTestModel::class);
        $display->shouldNotReceive('setModelClass');
        $display->shouldNotReceive('initialize');

        $model->onCreate(function () use ($display) {
            return $display;
        });

        $this->assertEquals($display, $model->fireCreate());

        // -------------------

        $display = m::mock(\SleepingOwl\Admin\Contracts\Initializable::class);
        $display->shouldNotReceive('setModelClass');
        $display->shouldReceive('initialize')->once();

        $model->onCreate(function () use ($display) {
            return $display;
        });

        $this->assertEquals($display, $model->fireCreate());

        // -------------------

        $display = m::mock(FormInterface::class);
        $display->shouldNotReceive('setModelClass');
        $display->shouldReceive('initialize')->once();
        $display->shouldReceive('setAction')->once()->with($model->getStoreUrl());

        $model->onCreate(function () use ($display) {
            return $display;
        });

        $this->assertEquals($display, $model->fireCreate());
    }

    public function test_edit()
    {
        $model = $this->getConfiguration();

        $display = m::mock(DisplayInterface::class);
        $display->shouldReceive('setModelClass')->once()->with($model->getClass());
        $display->shouldReceive('initialize')->once();

        $this->assertEquals($model, $model->onEdit(function ($id) use ($display) {
            $this->assertEquals(1, $id);

            return $display;
        }));

        $this->assertEquals($display, $model->fireEdit(1));

        // -------------------

        $display = m::mock(FormInterface::class);
        $display->shouldNotReceive('setModelClass');
        $display->shouldReceive('initialize')->once();
        $display->shouldReceive('setAction')->once()->with($model->getUpdateUrl(1));
        $display->shouldReceive('setId')->once()->with(1);

        $model->onEdit(function ($id) use ($display) {
            $this->assertEquals(1, $id);

            return $display;
        });

        $this->assertEquals($display, $model->fireEdit(1));
    }

    public function test_create_and_edit()
    {
        $model = $this->getConfiguration();

        $callback = function () {
        };

        $this->assertEquals($model, $model->onCreateAndEdit($callback));

        $this->assertEquals($callback, $model->getEdit());
        $this->assertEquals($callback, $model->getCreate());
    }

    public function test_delete()
    {
        $model = $this->getConfiguration();

        $callback = function ($id) {
            $this->assertEquals(1, $id);

            return 'deleted';
        };

        $this->assertEquals($model, $model->onDelete($callback));

        // nit:daan need fix
        // $this->assertEquals('deleted', $model->fireDelete(1));
    }

    public function test_destroy()
    {
        $model = $this->getConfiguration();

        $callback = function ($id) {
            $this->assertEquals(1, $id);

            return 'destroyed';
        };

        $this->assertEquals($model, $model->onDestroy($callback));

        // nit:daan need fix
        // ... @error Unable to resolve dependency [Parameter #0 [ <required> $id ]]
        // $this->assertEquals('destroyed', $model->fireDestroy(1));
    }

    public function test_restore()
    {
        $model = $this->getConfiguration();

        $callback = function ($id) {
            $this->assertEquals(1, $id);

            return 'restored';
        };

        $this->assertEquals($model, $model->onRestore($callback));

        // nit:daan need fix
        // $this->assertEquals('restored', $model->fireRestore(1));
    }

    public function test_displayable()
    {
        $model = $this->getConfiguration();
        $this->assertTrue($model->isDisplayable());

        $this->assertEquals($model, $model->disableDisplay());
        $this->assertFalse($model->isDisplayable());
    }

    public function test_creatable()
    {
        $model = $this->getConfiguration();
        $this->assertFalse($model->isCreatable());

        $model->onCreate(function () {
        });
        $this->assertTrue($model->isCreatable());

        $this->assertEquals($model, $model->disableCreating());
        $this->assertFalse($model->isCreatable());
    }

    public function test_editable()
    {
        $model = $this->getConfiguration();

        $this->assertFalse($model->isEditable($model->getModel()));

        $model->onEdit(function ($id) {
        });
        $this->assertTrue($model->isEditable($model->getModel()));

        $this->assertEquals($model, $model->disableEditing());
        $this->assertFalse($model->isEditable($model->getModel()));
    }

    public function test_deletable()
    {
        $model = $this->getConfiguration();

        $this->assertTrue($model->isDeletable($model->getModel()));

        $this->assertEquals($model, $model->disableDeleting());
        $this->assertFalse($model->isDeletable($model->getModel()));

        $this->assertEquals($model, $model->setDeletable(true));
        $this->assertTrue($model->isDeletable($model->getModel()));

        $model->setDeletable(0);
        $this->assertFalse($model->isDeletable($model->getModel()));

        $model->setDeletable(1);
        $this->assertTrue($model->isDeletable($model->getModel()));

        $model->setDeletable('test');
        $this->assertTrue($model->isDeletable($model->getModel()));
    }

    public function test_destroyable()
    {
        $model = $this->getConfiguration();

        $this->assertFalse($model->isDestroyable($model->getModel()));
    }

    public function test_destroyable_soft_delete()
    {
        $model = $this->getConfiguration(ModelConfigurationTestModelRestorable::class);

        $this->assertTrue($model->isDestroyable($model->getModel()));

        $this->assertEquals($model, $model->disableDestroying());
        $this->assertFalse($model->isDestroyable($model->getModel()));
    }

    public function test_restorable()
    {
        $model = $this->getConfiguration();

        $this->assertFalse($model->isRestorable($model->getModel()));
    }

    public function test_restorable_soft_delete()
    {
        $model = $this->getConfiguration(ModelConfigurationTestModelRestorable::class);

        $this->assertTrue($model->isDestroyable($model->getModel()));

        $this->assertEquals($model, $model->disableRestoring());
        $this->assertFalse($model->isDestroyable($model->getModel()));
    }

    public function test_restorable_model()
    {
        $model = $this->getConfiguration();

        $this->assertFalse($model->isRestorableModel());
    }

    public function test_restorable_model_soft_delete()
    {
        $model = $this->getConfiguration(ModelConfigurationTestModelRestorable::class);

        $this->assertTrue($model->isRestorableModel());
    }

    public function test_gets_and_sets_message_on_create()
    {
        $model = $this->getConfiguration();

        $this->getTranslatorMock()
            ->shouldReceive(version_compare(\Illuminate\Support\Facades\App::version(), '6.0', '>=') ? 'get' : 'trans')
            ->once()
            ->with('sleeping_owl::lang.message.created', null, null)
            ->andReturn('string');
        $this->assertEquals('string', $model->getMessageOnCreate());

        $this->assertEquals($model, $model->setMessageOnCreate('test'));
        $this->assertEquals('test', $model->getMessageOnCreate());
    }

    public function test_gets_and_sets_message_on_update()
    {
        $model = $this->getConfiguration();

        $this->getTranslatorMock()
            ->shouldReceive(version_compare(\Illuminate\Support\Facades\App::version(), '6.0', '>=') ? 'get' : 'trans')
            ->once()
            ->with('sleeping_owl::lang.message.updated', null, null)
            ->andReturn('string');

        $this->assertEquals('string', $model->getMessageOnUpdate());

        $this->assertEquals($model, $model->setMessageOnUpdate('test'));
        $this->assertEquals('test', $model->getMessageOnUpdate());
    }

    public function test_gets_and_sets_message_on_delete()
    {
        $model = $this->getConfiguration();

        $this->getTranslatorMock()
            ->shouldReceive(version_compare(\Illuminate\Support\Facades\App::version(), '6.0', '>=') ? 'get' : 'trans')
            ->once()
            ->with('sleeping_owl::lang.message.deleted', null, null)
            ->andReturn('string');

        $this->assertEquals('string', $model->getMessageOnDelete());

        $this->assertEquals($model, $model->setMessageOnDelete('test'));
        $this->assertEquals('test', $model->getMessageOnDelete());
    }

    public function test_gets_and_sets_message_on_destroy()
    {
        $model = $this->getConfiguration();

        $this->getTranslatorMock()
            ->shouldReceive(version_compare(\Illuminate\Support\Facades\App::version(), '6.0', '>=') ? 'get' : 'trans')
            ->once()
            ->with('sleeping_owl::lang.message.destroyed', null, null)
            ->andReturn('string');

        $this->assertEquals('string', $model->getMessageOnDestroy());

        $this->assertEquals($model, $model->setMessageOnDestroy('test'));
        $this->assertEquals('test', $model->getMessageOnDestroy());
    }

    public function test_gets_and_sets_message_on_restore()
    {
        $model = $this->getConfiguration();

        $this->getTranslatorMock()
            ->shouldReceive(version_compare(\Illuminate\Support\Facades\App::version(), '6.0', '>=') ? 'get' : 'trans')
            ->once()
            ->with('sleeping_owl::lang.message.restored', null, null)
            ->andReturn('string');

        $this->assertEquals('string', $model->getMessageOnRestore());

        $this->assertEquals($model, $model->setMessageOnRestore('test'));
        $this->assertEquals('test', $model->getMessageOnRestore());
    }
}

class ModelConfigurationTestModel extends \Illuminate\Database\Eloquent\Model
{
}

class ModelConfigurationTestModelRestorable extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
}
