<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_tasks', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->integer("processflow_history_id")->nullable()->comment("This column would hold the processflow history id, which comes from processflow service");
            $table->integer("formbuilder_data_id")->nullable()->comment("This column would hold the formbuilder data id, which can comes from processflow service or from formbuilder service");
            $table->integer("entity_id")->nullable()->comment("This column would hold the customer id or supplier , which can comes from  formbuilder service or from automator service");
            $table->string("entity_type")->nullable()->comment("This column helps us determine if the id entity is a customer or a supplier ");
            $table->integer("user_id")->nullable()->comment("This column would hold the user id, which can comes from  processflow service or from automator service");
            $table->integer("processflow_id")->nullable()->comment("This column would hold the processflow id, which can comes from  processflow service or from automator service");
            $table->integer("processflow_step_id")->nullable()->comment("This column would hold the processflow step id, which can comes from  processflow service or from automator service");
            $table->string("title")->comment("This column would hold the value of the task title ");
            $table->string("route")->nullable()->comment("This column would hold the value of the task route ");
            $table->date("start_time")->nullable()->comment("This column would hold the value of the task start time ");
            $table->date("end_time")->nullable()->comment("This column would hold the value of the task end time");
            $table->integer("task_status")->default(0)->comment("This column would hold the status of the task, which could be 0 as pending or 1 as done");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_tasks');
    }
};
