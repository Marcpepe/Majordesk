server 'majorclass.fr', :app, :web, :primary => true
set :domain,      "majorclass.fr"
set :deploy_to,   "/home/majorcla/public_html/majordesk/production/#{domain}"
set :branch, "master"
set :parameters_file, "parameters_production.yml"