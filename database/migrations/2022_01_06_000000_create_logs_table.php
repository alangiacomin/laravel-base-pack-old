<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * @var string Table name
     */
    private string $table_name;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->table_name = config('basepack.log.table_prefix').'logs';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('correlation_id');
            $table->integer('type');
            $table->string('object_id')->index();
            $table->string('class');
            $table->longText('payload');
            $table->timestamp('timestamp', 6);
            // $table->longText('message')->nullable();
            // $table->longText('trace')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table_name);
    }
}
