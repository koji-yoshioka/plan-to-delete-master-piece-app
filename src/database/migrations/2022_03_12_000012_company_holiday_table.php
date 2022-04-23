<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompanyHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_holiday', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('holiday_id');
            $table->timestamps();

            $table->unique(['company_id', 'holiday_id']);
            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
            $table->foreign('holiday_id')
                ->references('id')
                ->on('holidays');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_holiday');
    }
}
