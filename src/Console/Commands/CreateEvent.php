<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Throwable;

class CreateEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:event {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Event and EventHandler classes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $commandName = $this->argument('name');

        try {
            $this->createEventFile($commandName);
            $this->createEventHandlerFile($commandName);
            $this->info("Done!");
        } catch (Throwable $th) {
            $this->newline();
            $this->error("Failed");
            $this->newline();
            $this->line($th->getMessage());
        }
    }

    /**
     * @param $name
     * @return void
     */
    private function createEventFile($name)
    {
        $stubFile = __DIR__."\\stubs\\Event.php.stub";
        $newFile = base_path()."\\".Config::get('basepack.namespaces.events')."\\".$name.".php";
        if (!is_dir(dirname($newFile))) {
            mkdir(dirname($newFile), 0777, true);
        }

        $content = file_get_contents($stubFile);
        $content = $this->stubReplace("namespace", Config::get('basepack.namespaces.events'), $content);
        $content = $this->stubReplace("name", $name, $content);
        file_put_contents($newFile, $content);

        $this->comment("$name.php created");
    }

    /**
     * @param $name
     * @return void
     */
    private function createEventHandlerFile($name): void
    {
        $stubFile = __DIR__."\\stubs\\EventHandler.php.stub";
        $newFile = base_path()."\\".Config::get('basepack.namespaces.eventHandlers')."\\".$name."Handler.php";
        if (!is_dir(dirname($newFile))) {
            mkdir(dirname($newFile), 0777, true);
        }

        $content = file_get_contents($stubFile);
        $content = $this->stubReplace("handlerNamespace", Config::get('basepack.namespaces.eventHandlers'), $content);
        $content = $this->stubReplace("eventNamespace", Config::get('basepack.namespaces.events'), $content);
        $content = $this->stubReplace("event", $name, $content);
        file_put_contents($newFile, $content);

        $this->comment("{$name}Handler.php created");
    }

    /**
     * @param $key
     * @param $value
     * @param $content
     * @return string
     */
    private function stubReplace($key, $value, $content): string
    {
        $content_chunks = explode("{{ ".$key." }}", $content);
        return implode($value, $content_chunks);
    }
}
