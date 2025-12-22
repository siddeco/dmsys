<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SparePart;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        // البيانات الأساسية فقط
        $data = [
            [
                'name' => json_encode(['ar' => 'مصدر طاقة', 'en' => 'Power Supply']),
                'part_number' => 'SP-001',
                'quantity' => 25,
                'manufacturer' => 'Philips',
            ],
            [
                'name' => json_encode(['ar' => 'كابل أشعة', 'en' => 'X-Ray Cable']),
                'part_number' => 'SP-002',
                'quantity' => 10,
                'manufacturer' => 'Siemens',
            ],
            [
                'name' => json_encode(['ar' => 'مروحة تبريد', 'en' => 'Cooling Fan']),
                'part_number' => 'SP-003',
                'quantity' => 5,
                'manufacturer' => 'GE',
            ],
        ];

        foreach ($data as $item) {
            SparePart::firstOrCreate(
                ['part_number' => $item['part_number']],
                $item
            );
        }

        $this->command->info('✅ تم إنشاء 3 قطع غيار أساسية بنجاح.');
    }
}