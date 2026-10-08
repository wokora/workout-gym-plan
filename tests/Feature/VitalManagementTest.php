<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vital\InputType;
use App\Models\Vital\Unit;
use App\Models\Vital\UserVital;
use App\Models\Vital\Vital;
use Database\Seeders\InputTypeSeeder;
use Database\Seeders\VitalSeeder;
use Database\Seeders\VitalUnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VitalManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $weightVital;
    protected $heightVital;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(VitalUnitSeeder::class);
        $this->seed(InputTypeSeeder::class);
        $this->seed(VitalSeeder::class);

        $this->user = User::factory()->create();
        $this->weightVital = Vital::where('slug', 'weight')->first();
        $this->heightVital = Vital::where('slug', 'height')->first();
    }

    public function testVitalsIndexPageLoadsSuccessfully()
    {
        $response = $this->actingAs($this->user)->get(route('vital.index'));

        $response->assertStatus(200);
        $response->assertSee('VITALS LOG');
    }

    public function testVitalsCreatePageShowsDynamicVitalTypes()
    {
        $response = $this->actingAs($this->user)->get(route('vital.create'));

        $response->assertStatus(200);
        $response->assertSee('vital-selector-grid');
        $response->assertSee('Weight');
        $response->assertSee('Height');
        $response->assertSee('kg');
        $response->assertSee('cm');
    }

    public function testUserCanRecordSingleVitalUsingRadioButtonSelection()
    {
        $response = $this->actingAs($this->user)->post(route('vital.store'), [
            'date' => '2026-09-26',
            'vital_id' => $this->weightVital->id,
            'value' => '79.20',
            'action' => 'save',
        ]);

        $response->assertRedirect(route('vital.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_vitals', [
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 79.20,
            'date_measured' => '2026-09-26',
        ]);
    }

    public function testUserCanRecordAndSelectSaveAndRecordAnother()
    {
        $response = $this->actingAs($this->user)->post(route('vital.store'), [
            'date' => '2026-09-26',
            'vital_id' => $this->heightVital->id,
            'value' => '183.50',
            'action' => 'save_and_new',
        ]);

        $response->assertRedirect(route('vital.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_vitals', [
            'user_id' => $this->user->id,
            'vital_id' => $this->heightVital->id,
            'value' => 183.50,
            'date_measured' => '2026-09-26',
        ]);
    }

    public function testUserCanRecordMultipleVitalsAtOnce()
    {
        $response = $this->actingAs($this->user)->post(route('vital.store'), [
            'date' => '2026-09-26',
            'vitals' => [
                $this->weightVital->id => '78.50',
                $this->heightVital->id => '182.00',
            ],
        ]);

        $response->assertRedirect(route('vital.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_vitals', [
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 78.50,
            'date_measured' => '2026-09-26',
        ]);

        $this->assertDatabaseHas('user_vitals', [
            'user_id' => $this->user->id,
            'vital_id' => $this->heightVital->id,
            'value' => 182.00,
            'date_measured' => '2026-09-26',
        ]);
    }

    public function testUserCanViewAndEditVital()
    {
        $userVital = UserVital::create([
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 80.00,
            'date_measured' => '2026-09-20',
        ]);

        // Show page
        $showResponse = $this->actingAs($this->user)->get(route('vital.show', $userVital->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('80');

        // Edit page
        $editResponse = $this->actingAs($this->user)->get(route('vital.edit', $userVital->id));
        $editResponse->assertStatus(200);

        // Update
        $updateResponse = $this->actingAs($this->user)->patch(route('vital.update', $userVital->id), [
            'date' => '2026-09-21',
            'value' => '81.50',
        ]);

        $updateResponse->assertRedirect(route('vital.show', $userVital->id));
        $this->assertDatabaseHas('user_vitals', [
            'id' => $userVital->id,
            'value' => 81.50,
            'date_measured' => '2026-09-21',
        ]);
    }

    public function testUserCanDeleteVital()
    {
        $userVital = UserVital::create([
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 75.00,
            'date_measured' => '2026-09-22',
        ]);

        $response = $this->actingAs($this->user)->delete(route('vital.destroy', $userVital->id));

        $response->assertRedirect(route('vital.index'));
        $this->assertSoftDeleted('user_vitals', ['id' => $userVital->id]);
    }

    public function testBmiCalculationAndHelperMethods()
    {
        UserVital::create([
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 72.00,
            'date_measured' => '2026-09-20',
        ]);

        UserVital::create([
            'user_id' => $this->user->id,
            'vital_id' => $this->heightVital->id,
            'value' => 170.00,
            'date_measured' => '2026-09-15',
        ]);

        $latestWeight = $this->user->latestVital('weight');
        $latestHeight = $this->user->latestVital('height');

        $this->assertNotNull($latestWeight);
        $this->assertEquals(72.00, $latestWeight->value);

        $this->assertNotNull($latestHeight);
        $this->assertEquals(170.00, $latestHeight->value);

        // BMI = 72 / (1.7 * 1.7) = 24.91 -> 24.9
        $bmi = $this->user->bmi();
        $this->assertEquals(24.9, $bmi);
    }

    public function testExportAndImportVitals()
    {
        UserVital::create([
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 70.00,
            'date_measured' => '2026-09-01',
        ]);

        // Export
        $exportResponse = $this->actingAs($this->user)->get(route('vital.export'));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('Content-Disposition');

        // Import normalized format
        $jsonContent = json_encode([
            [
                'vital' => 'weight',
                'value' => 71.50,
                'date_measured' => '2026-09-02',
            ],
            [
                'vital' => 'height',
                'value' => 175.00,
                'date_measured' => '2026-09-02',
            ],
        ]);

        $file = UploadedFile::fake()->createWithContent('vitals.json', $jsonContent);

        $importResponse = $this->actingAs($this->user)->post(route('vital.import'), [
            'vitals_file' => $file,
        ]);

        $importResponse->assertRedirect(route('vital.index'));
        $this->assertDatabaseHas('user_vitals', [
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 71.50,
        ]);
        $this->assertDatabaseHas('user_vitals', [
            'user_id' => $this->user->id,
            'vital_id' => $this->heightVital->id,
            'value' => 175.00,
        ]);

        // Import legacy format
        $legacyJson = json_encode([
            [
                'weight' => '68.00',
                'height' => '174.00',
                'date_measured' => '2026-09-05',
            ]
        ]);

        $legacyFile = UploadedFile::fake()->createWithContent('legacy.json', $legacyJson);

        $legacyImportResponse = $this->actingAs($this->user)->post(route('vital.import'), [
            'vitals_file' => $legacyFile,
        ]);

        $legacyImportResponse->assertRedirect(route('vital.index'));
        $this->assertDatabaseHas('user_vitals', [
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 68.00,
        ]);
    }

    public function testDashboardRendersWithUserVitals()
    {
        UserVital::create([
            'user_id' => $this->user->id,
            'vital_id' => $this->weightVital->id,
            'value' => 74.00,
            'date_measured' => '2026-09-10',
        ]);
        UserVital::create([
            'user_id' => $this->user->id,
            'vital_id' => $this->heightVital->id,
            'value' => 180.00,
            'date_measured' => '2026-09-10',
        ]);

        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Vitals Progress');
        $response->assertSee('74');
    }
}
