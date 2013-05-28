exec { 'apt-get update':
  command => 'apt-get update',
  path    => '/usr/bin/',
  timeout => 60,
  tries   => 3,
}

class { 'apt':
  always_apt_update => true,
}

package { ['python-software-properties']:
  ensure  => 'installed',
  require => Exec['apt-get update'],
}

file { '/home/vagrant/.bash_aliases':
  source => 'puppet:///modules/puphpet/dot/.bash_aliases',
  ensure => 'present',
}

package { ['build-essential', 'vim', 'curl']:
  ensure  => 'installed',
  require => Exec['apt-get update'],
}

class { 'apache': }

apache::dotconf { 'custom':
  content => 'EnableSendfile Off',
}

apache::module { 'rewrite': }

apache::vhost { 'cloudtransphper':
  server_name   => 'cloudtransphper',
  serveraliases => [],
  docroot       => '/var/www/web',
  port          => '80',
  env_variables => [],
  priority      => '1',
}

apt::ppa { 'ppa:ondrej/php5':
  before  => Class['php']
}

class { 'php':
  service => 'apache',
  require => Package['apache'],
}

php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }
php::module { 'php5-mysql': }

class { 'php::devel':
  require => Class['php'],
}

class { 'php::pear':
  require => Class['php'],
}



class { 'xdebug': }

xdebug::config { 'cgi': }
xdebug::config { 'cli': }

class { 'php::composer': }

php::ini { 'default':
  value  => [
    'date.timezone = America/Chicago',
    'display_errors = On',
    'error_reporting = -1'
  ],
  target => 'error_reporting.ini',
}

class { 'mysql':
  root_password => 'testtest',
}

mysql::grant { 'cloudtransphper':
  mysql_privileges     => 'ALL',
  mysql_db             => 'cloudtransphper',
  mysql_user           => 'cloudtransphper',
  mysql_password       => 'testtest',
  mysql_host           => 'localhost',
  mysql_grant_filepath => '/home/vagrant/puppet-mysql',
}
