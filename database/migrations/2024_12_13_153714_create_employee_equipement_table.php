<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_equipement', function (Blueprint $table) {
            $table->id();
            // Update the foreign key reference to the correct table name ('employe' instead of 'employees')
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->foreignId('equipement_id')->constrained('equipements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_equipement');
    }
};
