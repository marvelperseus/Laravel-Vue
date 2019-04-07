<?php

use Illuminate\Database\Seeder;

class MailInboxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mail_inboxes')->delete();
        factory(\App\Models\Eloquent\MailInbox::class, 50)->create();
    }
}
