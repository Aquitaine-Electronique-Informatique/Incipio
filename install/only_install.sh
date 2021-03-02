# Prepare docker-compose.yml
echo "What is the domain name that points on that server (crm.n7consulting.fr) :"
read subdomain
echo "What is your contact email adress (contact@n7consulting.fr) :"
read email

# Create instance of dist files and template them
cp docker-compose.yml.dist docker-compose.yml
sed -i "s/restart: \"no\"/restart: \"always\"/g" docker-compose.yml
sed -i "s/REPLACE_WITH_YOUR_EMAIL/$email/g" docker-compose.yml

cp .env.dist .env
sed -i "s/REPLACE_WITH_YOUR_HOST/$subdomain/g" .env
sed -i "s/REPLACE_WITH_YOUR_EMAIL/$email/g" .env
SECRET=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
sed -i "s/GENERATED_SECRET/$SECRET/g" .env
sed -i "s/#TRUSTED_HOSTS/TRUSTED_HOSTS/g" .env
sed -i "s/MAILER_URL=null:\/\/localhost/MAILER_URL=smtp:\/\/mailer:25/g" .env

# Set config.json
cp var/key_value_store/config.json.dist var/key_value_store/config.json

# Build images and launch them
docker-compose build
docker-compose up -d

# Give time to boot DB container then load database schema & fixtures
echo "Waiting some seconds before setting up the database"
sleep 60
docker-compose exec web composer install
echo "Installation is now complete. You can now log in with credentials admin/admin. Don't forget to change that password."

