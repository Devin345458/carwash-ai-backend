#!/bin/sh

### This replaces tokens with corresponding environment variables
### should be used within the docker container
cp -rf ./config/.env ./config/.env.replace

if [[ ! -z "$AWS_SECRETS_MANAGER" ]]; then
for s in $(echo $AWS_SECRETS_MANAGER | jq -r "to_entries|map(\"\(.key)=\(.value|tostring)\")|.[]" ); do
    export $s
done
fi

### config needs to replace token, or we store all environments in secret manager
sed -i "s#_SECURITY_SALT_#${SECURITY_SALT}#g" ./config/.env.replace
sed -i "s#_REDIS_URL_#${REDIS_URL}#g" ./config/.env.replace
sed -i "s#_DB_HOST_#${DB_HOST}#g" ./config/.env.replace
sed -i "s#_DB_PORT_#${DB_PORT}#g" ./config/.env.replace
sed -i "s#_DB_DATABASE_#${DB_DATABASE}#g" ./config/.env.replace
sed -i "s#_DB_USERNAME_#${DB_USERNAME}#g" ./config/.env.replace
sed -i "s#_DB_PASSWORD_#${DB_PASSWORD}#g" ./config/.env.replace
sed -i "s#_SENTRY_DSN_#${SENTRY_DSN}#g" ./config/.env.replace

sed -i "s#_CHARGEBEE_KEY_#${CHARGEBEE_KEY}#g" ./config/.env.replace
sed -i "s#_CHARGEBEE_SITE_#${CHARGEBEE_SITE}#g" ./config/.env.replace
sed -i "s#_STRIPE_KEY_#${STRIPE_KEY}#g" ./config/.env.replace

sed -i "s#_PUSHER_APPKEY_#${PUSHER_APPKEY}#g" ./config/.env.replace
sed -i "s#_PUSHER_SECRET_#${PUSHER_SECRET}#g" ./config/.env.replace
sed -i "s#_PUSHER_CLUSTER_#${PUSHER_CLUSTER}#g" ./config/.env.replace
sed -i "s#_PUSHER_APPID_#${PUSHER_APPID}#g" ./config/.env.replace
sed -i "s#_ONE_SIGNAL_#${ONE_SIGNAL}#g" ./config/.env.replace
sed -i "s#_ONE_SIGNAL_API_#${ONE_SIGNAL_API}#g" ./config/.env.replace

sed -i "s#_EMAIL_TRANSPORT_DEFAULT_URL_#${EMAIL_TRANSPORT_DEFAULT_URL}#g" ./config/.env.replace
### END AWS SECRETS ###

### UNSET ####
unset SECURITY_SALT
unset REDIS_URL
unset DB_HOST
unset DB_PORT
unset DB_DATABASE
unset DB_USERNAME
unset DB_PASSWORD
unset SENTRY_DSN
unset EMAIL_TRANSPORT_DEFAULT_URL
unset CHARGEBEE_KEY
unset CHARGEBEE_SITE
unset STRIPE_KEY
unset PUSHER_APPKEY
unset PUSHER_SECRET
unset PUSHER_CLUSTER
unset PUSHER_APPID
unset ONE_SIGNAL
unset ONE_SIGNAL_API
### END UNSET ####

### COPY TO ENV ###
cp -rf ./config/.env.replace ./config/.env


### Clear Cache ###
bin/cake cache clear_all

### Start Cronjob ###
/usr/sbin/crond -l 8

### START APACHE FOREGROUND ###
/usr/sbin/httpd -D FOREGROUND
