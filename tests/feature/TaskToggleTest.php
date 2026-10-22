<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\EventModel;
use App\Models\EventTaskModel;

/**
 * @internal
 */
final class TaskToggleTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    public function testToggleTaskAjaxReturnsUpdatedCounters(): void
    {
        $eventModel = new EventModel();
        $taskModel  = new EventTaskModel();

        // Create a test event
        $eventId = $eventModel->insert([
            'title'       => 'Counter Test Event',
            'event_type'  => 'birthday',
            'event_date'  => date('Y-m-d', strtotime('+30 days')),
            'status'      => 'confirmed',
            'budget'      => 5000,
            'client_name' => 'Tester',
        ]);

        // Insert 2 tasks for this event: one completed, one todo
        $task1Id = $taskModel->insert([
            'event_id' => $eventId,
            'title'    => 'Test Task 1',
            'priority' => 'medium',
            'status'   => 'completed',
        ]);

        $task2Id = $taskModel->insert([
            'event_id' => $eventId,
            'title'    => 'Test Task 2',
            'priority' => 'high',
            'status'   => 'todo',
        ]);

        // Toggle task 2 to completed via AJAX
        $result = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post("events/tasks/{$task2Id}/toggle");

        $result->assertStatus(200);
        $result->assertJSONExact([
            'status'         => 'success',
            'newStatus'      => 'completed',
            'eventId'        => (int) $eventId,
            'completedTasks' => 2,
            'totalTasks'     => 2,
            'taskPercentage' => 100,
        ]);

        // Toggle task 1 back to todo
        $result2 = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post("events/tasks/{$task1Id}/toggle");

        $result2->assertStatus(200);
        $result2->assertJSONExact([
            'status'         => 'success',
            'newStatus'      => 'todo',
            'eventId'        => (int) $eventId,
            'completedTasks' => 1,
            'totalTasks'     => 2,
            'taskPercentage' => 50,
        ]);

        // Clean up test records
        $taskModel->where('event_id', $eventId)->delete();
        $eventModel->delete($eventId);
    }
}
