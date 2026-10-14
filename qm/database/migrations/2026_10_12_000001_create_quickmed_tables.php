<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role')->default('customer'); // customer, pharmacy_owner, admin
            $t->string('phone')->nullable();
            $t->string('address')->nullable();
        });

        Schema::create('pharmacies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('address');
            $t->string('area')->nullable();
            $t->string('phone');
            $t->string('email')->nullable();
            $t->text('description')->nullable();
            $t->string('opening_hours')->nullable();
            $t->string('status')->default('pending'); // pending, verified, rejected
            $t->timestamps();
        });

        Schema::create('drugs', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('generic_name')->nullable();
            $t->string('category')->nullable();
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('pharmacy_drugs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $t->foreignId('drug_id')->constrained()->cascadeOnDelete();
            $t->decimal('price', 12, 2);
            $t->unsignedInteger('quantity')->default(0);
            $t->boolean('is_available')->default(true);
            $t->timestamps();
            $t->unique(['pharmacy_id', 'drug_id']);
        });

        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $t->foreignId('drug_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('quantity');
            $t->decimal('unit_price', 12, 2);
            $t->decimal('total', 12, 2);
            $t->string('fulfilment')->default('pickup'); // pickup, delivery
            $t->text('delivery_address')->nullable();
            $t->string('phone');
            $t->text('note')->nullable();
            $t->string('status')->default('pending'); // pending, confirmed, out_for_delivery, completed, cancelled, rejected
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['orders', 'pharmacy_drugs', 'drugs', 'pharmacies'] as $t) Schema::dropIfExists($t);
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['role', 'phone', 'address']));
    }
};
