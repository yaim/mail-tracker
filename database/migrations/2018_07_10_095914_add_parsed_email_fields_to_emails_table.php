<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParsedEmailFieldsToEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable()->after('id');
            $table->longText('parsed_content')->nullable()->after('content');
            $table->timestamp('sent_at')->nullable()->after('parsed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropUnique('emails_uuid_unique');

            $table->dropColumn(['uuid', 'parsed_content', 'sent_at',]);
        });
    }
}
