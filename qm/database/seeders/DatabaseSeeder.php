<?php
namespace Database\Seeders;

use App\Models\{Drug, Order, Pharmacy, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'QuickMed Admin', 'email' => 'admin@quickmed.test', 'password' => 'password', 'role' => 'admin', 'phone' => '08000000000']);
        $customer = User::create(['name' => 'Ada Customer', 'email' => 'customer@quickmed.test', 'password' => 'password', 'role' => 'customer', 'phone' => '08011111111']);

        $drugs = collect([
            ['Paracetamol 500mg', 'Paracetamol', 'Pain relief'], ['Ibuprofen 400mg', 'Ibuprofen', 'Pain relief'],
            ['Amoxicillin 500mg', 'Amoxicillin', 'Antibiotic'], ['Vitamin C 1000mg', 'Ascorbic acid', 'Vitamins'],
            ['Loratadine 10mg', 'Loratadine', 'Allergy'], ['ORS Sachet', 'Oral rehydration salts', 'Rehydration'],
            ['Artemether/Lumefantrine', 'Artemether/Lumefantrine', 'Antimalarial'], ['Metronidazole 400mg', 'Metronidazole', 'Antibiotic'],
            ['Cetirizine 10mg', 'Cetirizine', 'Allergy'], ['Zinc 20mg', 'Zinc sulfate', 'Vitamins'],
        ])->map(fn ($d) => Drug::create(['name' => $d[0], 'generic_name' => $d[1], 'category' => $d[2], 'description' => 'Listed for information only. Follow the label and ask a pharmacist for advice.']));

        $pharmacies = [
            ['owner' => 'owner1@quickmed.test', 'name' => 'MediCare Pharmacy', 'area' => 'Wuse', 'address' => '12 Aminu Kano Crescent, Wuse II, Abuja', 'status' => 'verified', 'hours' => 'Mon-Sun, 8:00 AM - 10:00 PM'],
            ['owner' => 'owner2@quickmed.test', 'name' => 'HealthPlus Pharmacy', 'area' => 'Garki', 'address' => '5 Gimbiya Street, Garki, Abuja', 'status' => 'verified', 'hours' => 'Mon-Sat, 8:00 AM - 9:00 PM'],
            ['owner' => 'owner3@quickmed.test', 'name' => 'City Pharmacy', 'area' => 'Maitama', 'address' => '20 Ibrahim Babangida Way, Maitama, Abuja', 'status' => 'pending', 'hours' => null],
        ];
        $made = [];
        foreach ($pharmacies as $i => $p) {
            $owner = User::create(['name' => 'Owner ' . ($i + 1), 'email' => $p['owner'], 'password' => 'password', 'role' => 'pharmacy_owner', 'phone' => '0802000000' . $i]);
            $ph = Pharmacy::create(['user_id' => $owner->id, 'name' => $p['name'], 'address' => $p['address'], 'area' => $p['area'], 'phone' => $owner->phone,
                'email' => $p['owner'], 'opening_hours' => $p['hours'], 'status' => $p['status'], 'description' => 'A community pharmacy serving the ' . $p['area'] . ' area.']);
            foreach ($drugs as $j => $drug) {
                if (($i + $j) % 4 === 3) continue; // not every pharmacy stocks everything
                $ph->listings()->create(['drug_id' => $drug->id, 'price' => 500 + (($j * 370 + $i * 130) % 2500), 'quantity' => ($j + $i) % 5 === 0 ? 0 : 3 + (($j * 7 + $i * 5) % 40), 'is_available' => true]);
            }
            $made[] = $ph;
        }

        $l = $made[0]->listings()->first();
        Order::create(['user_id' => $customer->id, 'pharmacy_id' => $made[0]->id, 'drug_id' => $l->drug_id, 'quantity' => 2, 'unit_price' => $l->price,
            'total' => $l->price * 2, 'fulfilment' => 'delivery', 'delivery_address' => 'Plot 4, Gwarinpa Estate, Abuja', 'phone' => $customer->phone, 'status' => 'pending']);
        Order::create(['user_id' => $customer->id, 'pharmacy_id' => $made[0]->id, 'drug_id' => $l->drug_id, 'quantity' => 1, 'unit_price' => $l->price,
            'total' => $l->price, 'fulfilment' => 'pickup', 'phone' => $customer->phone, 'status' => 'completed']);
    }
}
