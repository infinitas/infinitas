package { 'python':
	ensure => latest,
}

package { 'g++':
	ensure => latest,
}

package { 'make':
	ensure => latest,
}

package { 'wget':
	ensure => latest,
}

package { 'tar':
	ensure => latest,
}

package { 'git':
	ensure => latest,
}

class { 'mysql::server': }

mysql::db { 'infinitas':
  user     => 'infinitas',
  password => 'infinitas',
  host     => 'localhost',
  grant    => ['all'],
}

mysql::db { 'test':
  user     => 'test',
  password => 'test',
  host     => 'localhost',
  grant    => ['all'],
}
