<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\EventModel;
use App\Models\EventProviderModel;
use App\Models\EventTaskModel;
use App\Models\ProviderModel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * @internal
 */
final class EventExportTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    public function testExportEventToExcelEnglish(): void
    {
        $eventModel    = new EventModel();
        $providerModel = new ProviderModel();
        $epModel       = new EventProviderModel();
        $taskModel     = new EventTaskModel();

        // Create an event
        $eventId = $eventModel->insert([
            'title'       => 'Grand Gala 2026',
            'event_type'  => 'corporate',
            'event_date'  => date('Y-m-d', strtotime('+45 days')),
            'status'      => 'confirmed',
            'budget'      => 25000,
            'client_name' => 'Alice Cooper',
            'client_email'=> 'alice@example.com',
            'client_phone'=> '555-0199',
            'guest_count' => 200,
        ]);

        // Create a provider and assign
        $providerId = $providerModel->insert([
            'name'           => 'Apex Audio & Lighting',
            'price_range'    => '$$$',
            'rating'         => 5,
            'status'         => 'active',
            'contact_person' => 'Bob Builder',
            'phone'          => '555-0122',
        ]);

        $epModel->insert([
            'event_id'            => $eventId,
            'provider_id'         => $providerId,
            'service_description' => 'Concert grade sound system and moving lights',
            'agreed_cost'         => 4500.00,
            'deposit_paid'        => 1500.00,
            'payment_status'      => 'partial',
            'contract_status'     => 'signed',
        ]);

        // Add a task
        $taskModel->insert([
            'event_id'    => $eventId,
            'provider_id' => $providerId,
            'title'       => 'Soundcheck with sound engineer',
            'priority'    => 'high',
            'status'      => 'todo',
            'due_date'    => date('Y-m-d', strtotime('+40 days')),
        ]);

        // Request Excel Export in English
        $result = $this->withSession(['locale' => 'en'])->get("events/{$eventId}/export");
        $result->assertStatus(200);
        $result->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $body = $result->response()->getBody();
        $this->assertNotEmpty($body);

        // Parse with PhpSpreadsheet to verify valid Excel structure
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        file_put_contents($tempFile, $body);

        $reader = new Xlsx();
        $spreadsheet = $reader->load($tempFile);
        unlink($tempFile);

        $sheetNames = $spreadsheet->getSheetNames();
        $this->assertCount(3, $sheetNames);
        $this->assertSame('Overview', $sheetNames[0]);
        $this->assertSame('Vendors & Providers', $sheetNames[1]);
        $this->assertSame('Checklist & Tasks', $sheetNames[2]);

        // Verify Overview values
        $sheetOverview = $spreadsheet->getSheet(0);
        $this->assertSame('Grand Gala 2026', $sheetOverview->getCell('A1')->getValue());

        // Verify Vendors sheet values
        $sheetVendors = $spreadsheet->getSheet(1);
        $this->assertSame('Apex Audio & Lighting', $sheetVendors->getCell('A4')->getValue());
        $this->assertEquals(4500.0, (float) $sheetVendors->getCell('G4')->getValue());
        $this->assertEquals(1500.0, (float) $sheetVendors->getCell('H4')->getValue());
        $this->assertEquals(3000.0, (float) $sheetVendors->getCell('I4')->getValue());

        // Verify Checklist sheet values
        $sheetTasks = $spreadsheet->getSheet(2);
        $this->assertSame('Soundcheck with sound engineer', $sheetTasks->getCell('B4')->getValue());
    }

    public function testExportEventToExcelSpanish(): void
    {
        $eventModel = new EventModel();

        $eventId = $eventModel->insert([
            'title'       => 'Fiesta de Aniversario',
            'event_type'  => 'anniversary',
            'event_date'  => date('Y-m-d', strtotime('+20 days')),
            'status'      => 'confirmed',
            'budget'      => 12000,
            'client_name' => 'Carlos Santana',
        ]);

        // Request Excel Export in Spanish
        $result = $this->withSession(['locale' => 'es'])->get("events/{$eventId}/export");
        $result->assertStatus(200);
        $result->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $body = $result->response()->getBody();
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_es_');
        file_put_contents($tempFile, $body);

        $reader = new Xlsx();
        $spreadsheet = $reader->load($tempFile);
        unlink($tempFile);

        $sheetNames = $spreadsheet->getSheetNames();
        $this->assertCount(3, $sheetNames);
        $this->assertSame('Resumen', $sheetNames[0]);
        $this->assertSame('Proveedores', $sheetNames[1]);
        $this->assertSame('Checklist y Tareas', $sheetNames[2]);
    }

    public function testExportNonExistentEvent(): void
    {
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->get('events/99999/export');
    }
}
