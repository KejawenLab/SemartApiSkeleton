<?php

declare(strict_types=1);

namespace App\Composer;

use App\Util\Encryptor;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
class ScriptHandler
{
    public static function preInstall(Event $event)
    {
        $composer = $event->getComposer();
        $rootPath = (string) realpath(sprintf('%s/../', $composer->getConfig()->get('vendor-dir')));
        $io = $event->getIO();

        $io->write('<comment>===========================================================</comment>');
        $io->write('<options=bold>Checking Environment Variable File</>');
        $io->write('<comment>===========================================================</comment>');

        $envPath = sprintf('%s/.env', $rootPath);
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($envPath)) {
            $io->write('<info>Creating new environment variable file</info>');

            $template = (string) file_get_contents(sprintf('%s/.env.template', $rootPath));

            self::createEnvironment($io, $envPath, $template);
        } else {
            $io->write('<info>Environment variable file is already exist</info>');
        }
    }

    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
        $io = $event->getIO();
        $rootPath = (string) realpath(sprintf('%s/../', $composer->getConfig()->get('vendor-dir')));
        $semartPath = sprintf('%s/.semart', $rootPath);

        if (1 === (int) file_get_contents($semartPath)) {
            return 0;
        }

        $io->write('<comment>===========================================================</comment>');
        $io->write('<options=bold>Semart Application Installation is finished</>');
        $io->write('<comment>===========================================================</comment>');
        $io->write('<comment>Run <info>symfony server:start</info> to start your server</comment>');
        $io->write('<comment>Login with username: <info>admin</info> and password: <info>semartadmin</info></comment>');

        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($semartPath, (string) 1);
    }

    private static function createEnvironment(IOInterface $io, string $envPath, string $template): void
    {
        $io->write('<comment>===========================================================</comment>');
        $io->write('<options=bold>Environment Setup</>');
        $io->write('<comment>===========================================================</comment>');

        $environment = $io->ask('Please enter your application environment [default: <info>dev</info>]: ', 'dev');
        $redisUlr = $io->ask('Please enter your redis url [default: <info>localhost</info>]: ', 'localhost');
        $dbDriver = $io->ask('Please enter your database driver [default: <info>mysql</info>]: ', 'mysql');
        $dbVersion = $io->ask('Please enter your database version [default: <info>5.7</info>]: ', '5.7');
        $dbCharset = $io->ask('Please enter your database charset [default: <info>utf8mb4</info>]: ', 'utf8mb4');
        $dbUser = $io->ask('Please enter your database user [default: <info>root</info>]: ', 'root');
        $dbPassword = $io->ask('Please enter your database password [default: <info>null</info>]: ', '');
        $dbName = $io->ask('Please enter your database name [default: <info>semart_app</info>]: ', 'semart_app');
        $dbHost = $io->ask('Please enter your database host [default: <info>localhost</info>]: ', 'localhost');
        $dbPort = $io->ask('Please enter your database port [default: <info>3306</info>]: ', '3306');
        $appShort = $io->ask('Please enter your application short title [default: <info>SEMART SKELETON</info>]: ', 'SEMART SKELETON');
        $appLong = $io->ask('Please enter your application long title [default: <info>KEJAWENLAB APPLICATION SKELETON</info>]: ', 'KEJAWENLAB APPLICATION SKELETON');
        $appVersion = $io->ask('Please enter your application version [default: <info>1@dev</info>]: ', '1@dev');

        $search = [
            '{{ENV}}',
            '{{SECRET}}',
            '{{REDIS_URL}}',
            '{{DB_DRIVER}}',
            '{{DB_VERSION}}',
            '{{DB_CHARSET}}',
            '{{DB_USER}}',
            '{{DB_PASSWORD}}',
            '{{DB_NAME}}',
            '{{DB_HOST}}',
            '{{DB_PORT}}',
            '{{APP_SHORT}}',
            '{{APP_LONG}}',
            '{{APP_VERSION}}',
        ];

        $secret = Encryptor::encrypt(sprintf('%s:%s', Application::APP_UNIQUE_NAME, date('YmdHis')), Application::APP_UNIQUE_NAME);
        $replace = [$environment, $secret, $redisUlr, $dbDriver, $dbVersion, $dbCharset, $dbUser, Encryptor::encrypt((string) $dbPassword, $secret), $dbName, $dbHost, $dbPort, $appShort, $appLong, $appVersion];

        $envString = str_replace($search, $replace, $template);

        $io->write('<options=bold>Dumping Environment Variables</>');

        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($envPath, $envString);
    }
}
