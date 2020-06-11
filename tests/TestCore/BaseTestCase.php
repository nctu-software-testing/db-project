<?php


namespace Tests\TestCore;

use App\User;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected $resourcesPath;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->resourcesPath = realpath(__DIR__ . '/../resources');

        $this->freshDatabase();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    private function freshDatabase()
    {
        $templateDBFilePath = $this->resourcesPath . '/db-template.db';
        $con = \DB::connection();

        // drop old tables
        do {
            $con->update('PRAGMA foreign_keys = OFF');
            $tables = $con->table('main.sqlite_master')
                ->select('name')->get()
                ->pluck('name');

            foreach ($tables as $table) {
                $con->update("DROP TABLE main.`$table`");
            }

            $con->update('PRAGMA foreign_keys = ON');
        } while (false);

        $attachSQL = 'ATTACH DATABASE "' . $templateDBFilePath . '" AS template';
        $con->update($attachSQL);

        // copy tables
        do {
            $tables = $con
                ->table('template.sqlite_master')
                ->where('type', 'table')
                ->orderBy('rootpage')
                ->get();

            // create tables
            foreach ($tables as $table) {
                // skip internal table
                if (strpos($table->name, 'sqlite_') === 0)
                    continue;

                $con->update($table->sql);
            }

            // copy data
            foreach ($tables as $table) {
                $sql = "INSERT INTO main.`{$table->name}` SELECT * FROM template.`{$table->name}`";
                $con->update($sql);
            }
        } while (false);

        // update table index
        do {
            $tables = $con
                ->table('template.sqlite_master')
                ->where('type', 'index')
                ->whereNotNull('sql')
                ->orderBy('rootpage')
                ->get();

            // create indexes
            foreach ($tables as $table) {
                $con->update($table->sql);
            }
        } while (false);

        // detach template database
        $con->update('DETACH DATABASE template');
    }

    protected function withUser(?string $account): BaseTestCase
    {
        if ($account === null) {
            $this->withSession([
                'user' => null,
            ]);
        } else {
            $user = User::where('account', $account)->firstOrFail();

            $this->withSession([
                'user' => $user,
            ]);
        }

        return $this;
    }

    protected function doPassCaptcha()
    {
        $this->get('/captcha/full-image');
        $captchaValue = session('captcha');

        $this->post('/captcha/verify', [
            'value' => $captchaValue['selected']['x']
        ]);
    }
}
