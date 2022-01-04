<?php /** @noinspection PhpUnused */

namespace Alangiacomin\LaravelBasePack\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Throwable;

class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basepack:command {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Command and CommandHandler classes';

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
            $this->createCommandFile($commandName);
            $this->createCommandHandlerFile($commandName);
            $this->info("Done!");
        } catch (Throwable $th) {
            $this->newline();
            $this->error("Failed");
            $this->newline();
            $this->line($th->getMessage());
        }
    }

    private function createCommandFile($name): void
    {
        $stubFile = __DIR__."\\stubs\\Command.php.stub";
        $newFile = base_path()."\\".Config::get('basepack.namespaces.commands')."\\".$name.".php";
        if (!is_dir(dirname($newFile))) {
            mkdir(dirname($newFile), 0777, true);
        }

        $content = file_get_contents($stubFile);
        $content = $this->stubReplace("namespace", Config::get('basepack.namespaces.commands'), $content);
        $content = $this->stubReplace("name", $name, $content);
        file_put_contents($newFile, $content);

        $this->comment("$name.php created");
    }

    private function createCommandHandlerFile($name): void
    {
        $stubFile = __DIR__."\\stubs\\CommandHandler.php.stub";
        $newFile = base_path()."\\".Config::get('basepack.namespaces.commandHandlers')."\\".$name."Handler.php";
        if (!is_dir(dirname($newFile))) {
            mkdir(dirname($newFile), 0777, true);
        }

        $content = file_get_contents($stubFile);
        $content = $this->stubReplace("handlerNamespace", Config::get('basepack.namespaces.commandHandlers'), $content);
        $content = $this->stubReplace("commandNamespace", Config::get('basepack.namespaces.commands'), $content);
        $content = $this->stubReplace("command", $name, $content);
        file_put_contents($newFile, $content);

        $this->comment("{$name}Handler.php created");
    }

    private function stubReplace($key, $value, $content): string
    {
        $content_chunks = explode("{{ ".$key." }}", $content);
        return implode($value, $content_chunks);
    }
}
