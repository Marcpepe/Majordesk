server 'staging.majorclass.fr', :app, :web, :primary => true
set :domain,      "staging.majorclass.fr"
set :deploy_to,   "/home/majorcla/var/www/majordesk/staging/#{domain}"
set :branch, "develop"