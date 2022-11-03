<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @test **/
    public function users_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users',[
                'id',
                'name',
                'email',
                'email_verified',
                'password',
                'remember_token',
                'created_at',
                'updated_at'
            ],1)
        );
    }
}
