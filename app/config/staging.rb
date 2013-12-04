server 'staging.majorclass.fr', :app, :web, :primary => true
set :domain,      "staging.majorclass.fr"
set :deploy_to,   "/var/www/staging/#{domain}"
set :branch, "develop"