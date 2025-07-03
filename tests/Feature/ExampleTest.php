<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;
     #[DataProvider('dataProvider')]
     public function test_user_name_matches(string $username): void
     {
         $user = User::where('name', 'kir')->first();
 
         $this->assertEquals($user->name, $username);
     }
     public static function dataProvider():array
     {
         return [
             ['kir'],
         ];
     }
}
