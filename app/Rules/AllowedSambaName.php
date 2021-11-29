<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedSambaName implements Rule
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

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return (! in_array($value, self::RESTRICTED_NAMES));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The name is restricted, please try another one.';
    }
}
