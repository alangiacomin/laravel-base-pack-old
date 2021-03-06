<?php /** @noinspection PhpUnused */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('basepack.bus.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('correlation_id');
            $table->string('object_id')->index();
            $table->string('class')->nullable();
            $table->longText('payload')->nullable();
            $table->timestamp('created_at', 6)->nullable();
            $table->timestamp('done_at', 6)->nullable();
            $table->longText('message')->nullable();
            $table->longText('trace')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('basepack.bus.table'));
    }
}
