# DB Dump command
export SERVER_HOST="10.10.23.11"
last_octet=${SERVER_HOST##*.}
SID=$(( last_octet - 1 ))
echo "SID: $SID"


ssh root@$SERVER_HOST
# use .env of laravel app
source /var/www/html/.env
mysqldump -u $DB_USERNAME -p$DB_PASSWORD --databases $DB_DATABASE > /var/www/html/database.sql

# Convert dump to tar archive
tar -czvf /var/www/html/database.sql.tar.gz -C /var/www/html database.sql
tar -czvf /var/www/html/database.tgz -C /var/www/html database.sql
# Optionally, remove the original SQL file
rm /var/www/html/database.sql
exit
scp root@"$SERVER_HOST":/var/www/html/database.tgz "./$(date +%Y%m%d)-${SID}.sql.tgz"
## .env Reset

php artisan optimize:clear
# or, if optimize:clear isn’t available:
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
