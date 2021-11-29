<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\{ Container, SambaDirectory };

class SambaDirectoryName implements Rule
{
    const RESTRICTED_NAMES = [
        'messagebus',
        'systemd-network',
        'systemd-resolve',
        'systemd-timesync',
        'systemd-coredump',
        'root',
        'daemon',
        'bin',
        'sys',
        'sync',
        'games',
        'man',
        'lp',
        'mail',
        'news',
        'uucp',
        'proxy',
        'www-data',
        'backup',
        'list',
        'irc',
        'gnats',
        'nobody',
        '_apt',
        'syslog',
        'uuidd',
        'bind',
        '_rpc',
        'tcpdump',
        'Debian-exim',
        'fetchmail',
        'sshd',
        'homes',
        'printers',
        'prints',
        'public',
        'private',
        'web',
        'www',
        'html',
        'users',
        'statd',
        'nfs',
    ];

    private $message;

    private $serverContainer;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer)
    {
        $this->message = 'Unknown error.';

        $this->serverContainer = $serverContainer;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if name exists in database
        $container = $this->serverContainer;
        if (SambaDirectory::exists($container, $value)) {
            $this->message = 'This directory is already exists.';
            return false;
        }

        if (in_array($value, self::RESTRICTED_NAMES)) {
            $this->message = 'This is the restricted name.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
