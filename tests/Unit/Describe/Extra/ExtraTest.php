<?php

namespace Tests\Unit\Describe\Extra;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Zerotoprod\DataModel\Describe;

class ExtraTest extends TestCase
{
    #[Test] public function single_custom_key_captured(): void
    {
        $Describe = new Describe(['cast' => 'strtoupper', 'label' => 'Name']);

        $this->assertSame('Name', $Describe->extra['label']);
    }

    #[Test] public function multiple_custom_keys_captured(): void
    {
        $Describe = new Describe([
            'cast' => 'strtoupper',
            'label' => 'Name',
            'hint' => 'Enter your name',
            'priority' => 5,
        ]);

        $this->assertSame('Name', $Describe->extra['label']);
        $this->assertSame('Enter your name', $Describe->extra['hint']);
        $this->assertSame(5, $Describe->extra['priority']);
    }

    #[Test] public function extra_is_empty_when_no_custom_keys(): void
    {
        $Describe = new Describe(['cast' => 'strtoupper', 'required' => true]);

        $this->assertSame([], $Describe->extra);
    }

    #[Test] public function extra_is_empty_for_null_attributes(): void
    {
        $Describe = new Describe(null);

        $this->assertSame([], $Describe->extra);
    }

    #[Test] public function extra_is_empty_for_string_attribute(): void
    {
        $Describe = new Describe('required');

        $this->assertSame([], $Describe->extra);
    }

    #[Test] public function recognized_keys_not_in_extra(): void
    {
        $Describe = new Describe([
            'from' => 'key',
            'cast' => 'strtoupper',
            'required' => true,
            'nullable' => false,
            'ignore' => false,
            'default' => 'val',
            'pre' => 'trim',
            'post' => 'trim',
            'via' => 'from',
            'assign' => 'fixed',
            'custom' => 'metadata',
        ]);

        $this->assertArrayNotHasKey('from', $Describe->extra);
        $this->assertArrayNotHasKey('cast', $Describe->extra);
        $this->assertArrayNotHasKey('required', $Describe->extra);
        $this->assertArrayNotHasKey('nullable', $Describe->extra);
        $this->assertArrayNotHasKey('ignore', $Describe->extra);
        $this->assertArrayNotHasKey('default', $Describe->extra);
        $this->assertArrayNotHasKey('pre', $Describe->extra);
        $this->assertArrayNotHasKey('post', $Describe->extra);
        $this->assertArrayNotHasKey('via', $Describe->extra);
        $this->assertArrayNotHasKey('assign', $Describe->extra);
        $this->assertSame('metadata', $Describe->extra['custom']);
    }

    #[Test] public function custom_array_value_captured(): void
    {
        $Describe = new Describe(['rules' => ['min:2', 'max:100']]);

        $this->assertSame(['min:2', 'max:100'], $Describe->extra['rules']);
    }

    #[Test] public function custom_boolean_value_captured(): void
    {
        $Describe = new Describe(['searchable' => true]);

        $this->assertTrue($Describe->extra['searchable']);
    }

    #[Test] public function missing_as_null_not_in_extra(): void
    {
        $Describe = new Describe(['missing_as_null' => true]);

        $this->assertArrayNotHasKey('missing_as_null', $Describe->extra);
    }

    #[Test] public function extra_accessible_in_cast_via_reflection(): void
    {
        $model = ExtraCastModel::from(['name' => 'jane']);

        $this->assertSame('JANE', $model->name);
    }

    #[Test] public function extra_accessible_in_cast_via_instance(): void
    {
        $model = ExtraInstanceModel::from(['name' => 'jane']);

        $this->assertSame('JANE', $model->name);
    }
}
