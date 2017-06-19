<?php
namespace Deployer;

require 'recipe/laravel.php';

// Configuration

set('repository', 'https://github.com/Justez/laravel-shop-example.git');
set('git_tty', true); // [Optional] Allocate tty for git on first deployment
add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);


// Hosts

host('demo2.coingate.com')
    ->stage('production')
    ->user('deploy')
    ->set('deploy_path', '/home/deploy/justina_shop');


// Tasks

task('upload:env', function () {
    upload('.env.production', '{{deploy_path}}/shared/.env');
})->desc('Environment setup');

task('products:seed', function () {
run('cd {{deploy_path}}/current && composer dump && php artisan db:seed');
});

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo systemctl restart php7.0-fpm.service');
});
after('deploy:symlink', 'php-fpm:restart');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
