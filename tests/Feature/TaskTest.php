// tests/Feature/TaskTest.php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Task description',
            'due_date' => now()->addDays(3),
            'status' => false,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'due_date' => now()->addDays(3),
            'status' => true,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Task']);
    }

    public function test_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
