class xdebug {
    package { 'xdebug':
        name    => 'php5-xdebug',
        ensure  => installed,
        require => Package['php'],
        notify  => Service['apache']
    }
}
