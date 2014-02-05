require 'capifony_symfony2'

set :stages,        %w(production staging)
set :default_stage, "staging"
set :stage_dir,     "app/config"
require 'capistrano/ext/multistage'

# before "deploy:restart", "deploy:set_permissions"

# namespace :deploy do
  # namespace :web do
    # task :disable, :roles => :web, :except => { :no_release => true } do
      # require 'erb'
      # on_rollback { run "rm #{shared_path}/system/maintenance.html" }

      # reason = ENV['REASON']
      # deadline = ENV['UNTIL']

      # template = File.read("./app/Resources/layouts/maintenance.html.erb")
      # result = ERB.new(template).result(binding)

      # put result, "#{shared_path}/system/maintenance.html", :mode => 0644
    # end
  # end
# end

# set :domain,      "majorclass.fr"
# set :deploy_to,   "/home/majorcla/var/www/majordesk/#{domain}"
# set :branch, "master"
# server 'majorclass.fr', :app, :web, :primary => true

set :application, "MajordeskApp"
set :app_path,    "app"
set :user, "majorcla"  # The server's user for deploys
set :use_sudo,      false
default_run_options[:pty] = true
ssh_options[:port] = "2908"
# ssh_options[:forward_agent] = true
# set :ssh_options, { :forward_agent => true }
# set :ssh_options,   :keys => %w(c:/Users/USERNAME/.ssh/id_rsa)
# set :repository,  "ssh://git@github.com/Marcpepe/Majordesk.git"
# set :repository,  "git@github.com:Marcpepe/Majordesk.git"
set :repository,  "https://47376530582201bacd1ba77eba3ec31058cae308@github.com/Marcpepe/Majordesk.git"
# set :git_https_username, 'Marcpepe'
# set :git_https_password, 'GBlqcK19'
set :scm,         :git
set :scm_passphrase, "perrin"  # The deploy user's password
# set :git_enable_submodules, 1
# useful add on that speeds each deployment
# set :deploy_via, :remote_cache
# set :deploy_via, :rsync_with_remote_cache
# set :deploy_via, :copy

set :dump_assetic_assets,   true
set :writable_dirs,       [app_path + "/cache", app_path + "/logs"]
set :permission_method,   :chmod
set :set_permissions, true
set :shared_children,   [app_path + "/logs", app_path + "/sessions", "web/uploads"]

set :shared_files,      ["app/config/parameters.yml","web/.htaccess"]
# set :shared_files,      ["app/config/parameters.yml", "composer.phar"]
# set :use_composer,    false
set :use_composer,    true
# set :composer_options, "--no-dev --verbose --prefer-dist --optimize-autoloader --no-progress -d memory_limit=-1"
# set :composer_options, "--no-dev --verbose --prefer-dist --optimize-autoloader -d memory_limit=-1"
# set :composer_bin,    "/home/majorcla/var/www/majordesk/#{domain}/shared/composer.phar"
set :update_vendors,  false
set :vendors_mode,    "install"
set :copy_vendors, false


set :model_manager, "doctrine"
set :keep_releases,  3


# after "deploy:update_code" do
  # capifony_pretty_print "--> Ensuring cache directory permissions"
  # run "setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
  # run "setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
  # capifony_puts_ok
# end

# before 'symfony:composer:install', 'composer:copy_vendors'
# before 'symfony:composer:update', 'composer:copy_vendors'

# namespace :composer do
  # task :copy_vendors, :except => { :no_release => true } do
    # capifony_pretty_print "--> Copy vendor file from previous release"

    # run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    # capifony_puts_ok
  # end
# end

# task :push_deploy_tag do
  # user = `git config --get user.name`.chomp
  # email = `git config --get user.email`.chomp
  # puts `git tag #{stage}_#{release_name} #{current_revision} -m "Deployed by #{user} <#{email}>"`
  # puts `git push --tags origin`
# end




set :parameters_dir, "app/config"
set :parameters_file, false

task :upload_parameters do
  origin_file = parameters_dir + "/" + parameters_file if parameters_dir && parameters_file
  if origin_file && File.exists?(origin_file)
    ext = File.extname(parameters_file)
    relative_path = "app/config/parameters" + ext

    if shared_files && shared_files.include?(relative_path)
      destination_file = shared_path + "/" + relative_path
    else
      destination_file = latest_release + "/" + relative_path
    end
    try_sudo "mkdir -p #{File.dirname(destination_file)}"

    top.upload(origin_file, destination_file)
  end
end

after 'deploy:setup', 'upload_parameters'

task :upload_htaccess do
  origin_file = "web/.htaccess"
  destination_file = shared_path + "/web/.htaccess"

  try_sudo "mkdir -p #{File.dirname(destination_file)}"
  top.upload(origin_file, destination_file)
end

after "deploy:setup", "upload_htaccess"


# namespace :post_deployment do
  # desc "Set the right file permissions / ownership for deployed files"
  # task :app_permissions, :roles => :app do
   # run "#{sudo} chown -Rf apache.users #{deploy_to}"
   # run "#{sudo} chmod 775 -Rf #{deploy_to}"
   # run "#{sudo} chmod 777 -hRf #{deploy_to}/app/cache"
  # end
# end

# after "post_deployment:app_permissions","deploy:cleanup"

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL


# cap deploy:web:disable

# cap database:dump:remote

# cap deploy:web:enable

