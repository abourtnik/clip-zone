<?php

use Rappasoft\LaravelPatches\Patch;

class ExamplePatch extends Patch
{
    public function description(): ?string
    {
        return 'Patch description';
    }

    /**
     * Run the patch.
     *
     * @return void
     */
    public function up(): void
    {
        info('Hello!');

        // Useful methods
//        $this->log('Hello');
//        $this->call('my-command', ['my-option' => 'my-value']);
//        $this->seed('PatchV1_0_0Seeder');
//        $this->truncate('my-table');
    }

    /**
     * Reverse the patch.
     *
     * @return void
     */
    public function down(): void
    {
        info('Goodbye :(');

        // Useful methods
//        $this->call('my-command', ['my-option' => 'my-value']);
//        $this->seed('PatchV1_0_0Seeder');
//        $this->truncate('my-table');
    }
}
