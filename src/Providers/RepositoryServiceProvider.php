<?php

namespace Alangiacomin\LaravelBasePack\Providers;

use Alangiacomin\LaravelBasePack\Core\Enums\BindingType;
use Alangiacomin\LaravelBasePack\Facades\LaravelBasePackFacade;
use Exception;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings
     *
     * @return void
     * @throws Exception
     */
    public function register()
    {
        $repositories = config('basepack.repositories', []);
        if (!is_array($repositories))
        {
            throw new Exception("Repositories must be 'array', but '".gettype($repositories)."' found");
        }

        foreach ($repositories as $repo)
        {
            if (is_string($repo))
            {
                $repo = [
                    'interface' => $repo,
                    'bindingType' => BindingType::Bind,
                ];
            }

            LaravelBasePackFacade::checkObjectType($repo, 'array');

            $interface = $repo['interface'];
            $bindingType = $repo['bindingType'] ?? BindingType::Bind;

            LaravelBasePackFacade::checkObjectType($interface, 'string');
            LaravelBasePackFacade::checkObject($bindingType, function ($obj) use (&$bindingType) {
                if (is_string($obj))
                {
                    $bindingType = BindingType::from($obj);
                    return true;
                }
                return $obj instanceof BindingType;
            });

            $interfaceTokens = explode('\\', $interface);
            $lastToken = array_pop($interfaceTokens);

            $class = implode('\\', array_merge($interfaceTokens, [substr($lastToken, 1)]));
            if (!class_exists($class))
            {
                throw new Exception("Repository binding: $class not found");
            }

            $this->app->{$bindingType->value}($interface, $class);
        }
    }
}
