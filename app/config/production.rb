server 'majorclass.fr', :app, :web, :primary => true
set :domain,      "majorclass.fr"
set :deploy_to,   "/var/www/production/#{domain}"
set :branch, "master"