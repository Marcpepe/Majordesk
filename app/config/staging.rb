server 'staging.majorclass.fr', :app, :web, :primary => true
set :domain,      "staging.majorclass.fr"
set :deploy_to,   "/home/majorcla/var/www/majordesk/staging/staging.majorclass.fr"
set :branch, "master"