FROM php:8.2-apache

# 必要なモジュールをインストール
RUN docker-php-ext-install pdo pdo_mysql
# mod_rewriteを有効化
RUN a2enmod rewrite
#AllowOverride Allに変換
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf