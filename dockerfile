FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
      wget \
      git \
      fish

RUN apt-get update && apt-get install -y libzip-dev && docker-php-ext-install pdo zip

# Support de Postgre
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# Support de MySQL (pour la migration)
RUN docker-php-ext-install mysqli pdo_mysql

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer


#ENTRYPOINT wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony
# Symfony tool
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY . ./
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/
COPY .env ./
COPY composer.json composer.json symfony.lock ./

RUN composer install


WORKDIR /usr/src/api


CMD symfony serve --allow-http --no-tls --port=8000
#ENTRYPOINT symfony server:start