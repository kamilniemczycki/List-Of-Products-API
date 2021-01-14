<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsToListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_to_lists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->bigInteger('list_id')->unsigned()->index()->nullable();
            $table->boolean("is_done")->default(false);

            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('list_id')->references('id')->on('lists');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_to_lists');
    }
}
