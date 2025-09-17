<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;
use App\Mail\PasswordResetCode;
use PHPUnit\Framework\Attributes\Test;  
class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
        use RefreshDatabase;

        //Register
        // ->register and Verify
        // ->register with email already exists
        // ->verifywith correct code
        // ->invalid code

#[Test]

    public function register(): void
    {
                Mail::fake();

         $response = $this->post('/api/register', [
               'fname' => 'Nayera',
            'lname' => 'Mohamed',
            'username' => 'Nayera_',
            'email' => 'nayera611@gmail.com',
            'password' => '1234567',
        ]);

 $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user' => ['id', 'email', 'fname', 'lname', 'username'],
                     'token',
                 ]);

     $this->assertDatabaseHas('users', [
            'email' => 'nayera611@gmail.com',
            'is_verified' => false,
        ]);

        Mail::assertSent(VerifyEmail::class);
    }
    #[Test]

         public function register_with_existingemail()
    {
        User::factory()->create(['email' => 'nayera611@gmail.com']);

        $response = $this->postJson('/api/register', [
            'fname' => 'Nira',
            'lname' => 'Mohamed',
            'username' => 'niraaa',
            'email' => 'nayera611@gmail.com',
            'password' => '1234567',
        ]);

        $response->assertStatus(422); 
    }
#[Test]

      public function verifyemail_with_correctcode()
    {
        $user = User::factory()->create([
            'email' => 'test@outlook.com',
            'verification_code' => '111111',
            'is_verified' => false,
        ]);

        $response = $this->postJson('/api/verifyOTP', [
            'email' => 'test@outlook.com',
            'verification_code' => '111111',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Email verified successfully.']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@outlook.com',
            'is_verified' => true,
        ]);
    }
#[Test]

     public function verify_with_invalidcode()
    {
        $user = User::factory()->create([
            'email' => 'verify@test.com',
            'verification_code' => '111111',
        ]);

        $response = $this->postJson('/api/verifyOTP', [
            'email' => 'verify@test.com',
            'verification_code' => '777777',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Invalid verification code.']);
    }
    //login
    //try to login with wrong pass
    // login before verfication

#[Test]


 public function login()
    {
        $user = User::factory()->create([
            'email' => 'login@gmail.com',
            'password' => Hash::make('1234567'),
            'is_verified' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@gmail.com',
            'password' => '1234567',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'user', 'token']);
    }

#[Test]

     public function login_with_wrongpassword()
    {
        $user = User::factory()->create([
            'email' => 'login@gmail.com',
            'password' => Hash::make('1234567'),
            'is_verified' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@gmail.com',
            'password' => '9999087',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Invalid credentials']);
    }
#[Test]

      public function login_without_verification()
    {
        $user = User::factory()->create([
            'email' => 'login5@gmail.com',
            'password' => Hash::make('1234567'),
            'is_verified' => false,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login5@gmail.com',
            'password' => '1234567',
        ]);

        $response->assertStatus(403)
                 ->assertJson(['message' => 'Please verify your email first']);
    }

    //reset
    //update pass
    //update with invalid code
#[Test]

      public function requestpassword_resetcode()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'reset@outlook.com']);

        $response = $this->postJson('/api/password/forgot', [
            'email' => 'reset@outlook.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Reset Code Sent to your Email kindly check']);

        Mail::assertSent(PasswordResetCode::class);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'reset@outlook.com',
        ]);
    }
#[Test]

      public function updatepassword_with_validcode()
    {
        $user = User::factory()->create(['email' => 'reset@gmail.com']);
        DB::table('password_reset_tokens')->insert([
            'email' => 'reset@gmail.com',
            'token' => '123456',
            'created_at' => now(),
        ]);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'reset@gmail.com',
            'code' => '123456',
            'new_password' => '543210',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Password reset successfully']);

        $this->assertTrue(Hash::check('543210', $user->fresh()->password));
    }
#[Test]


        public function updatepassword_with_invalidcode()
    {
        $user = User::factory()->create(['email' => 'reset@gmail.com']);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'reset@gmail.com',
            'code' => '444444',
            'new_password' => '543210',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Invalid code']);
    }

#[Test]
    //logout


        public function logout()
    {
        $user = User::factory()->create([
            'email' => 'logout@gmail.com',
            'password' => Hash::make('password'),
            'is_verified' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Logout successful']);
    }

    }

