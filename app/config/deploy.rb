set :application, "Majordesk App"
set :domain,      "majorclass.com"
set :deploy_to,   "/var/www/#{domain}"
set :app_path,    "app"

# set   :branch, "master"

# useful add on that speeds each deployment
# set :deploy_via,    :rsync_with_remote_cache

set :shared_files,      ["app/config/parameters.yml","composer.phar"]
set :use_composer, true
set :update_vendors, true
set :copy_vendors, true
set :composer_options, "--no-dev --verbose --prefer-dist --optimize-autoloader"
set :dump_assetic_assets,   true

set :writable_dirs,       ["app/cache", "app/logs"]
set :permission_method,   :chmod

set :shared_children,   [log_path, ..., app_path + "/sessions"]
before 'symfony:composer:install', 'composer:copy_vendors'
before 'symfony:composer:update', 'composer:copy_vendors'

namespace :composer do
  task :copy_vendors, :except => { :no_release => true } do
    capifony_pretty_print "--> Copy vendor file from previous release"

    run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    capifony_puts_ok
  end
end

set :repository,  "git@github.com:Marcpepe/Majordesk.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

# set  :user, "majorcla"
set  :use_sudo,      false
set  :keep_releases,  3
# set  :git_enable_submodules, 0

# namespace :post_deployment do
  # desc "Set the right file permissions / ownership for deployed files"
  # task :app_permissions, :roles => :app do
   # run "#{sudo} chown -Rf apache.users #{deploy_to}"
   # run "#{sudo} chmod 775 -Rf #{deploy_to}"
   # run "#{sudo} chmod 777 -hRf #{deploy_to}/app/cache"
  # end
# end

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL


cap deploy:web:disable





cap database:dump:remote






cap deploy:web:enable

